<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdateNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        // Filtering Logic
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->get('filter') === 'assigned_by_me') {
            $query->where('assigned_to', Auth::id());
        } elseif ($request->get('filter') === 'owned') {
            $query->where('user_id', Auth::id());
        }

        // Search Logic
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('reporter_name', 'like', '%' . $request->search . '%')
                ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        // Always sort by newest created first so new tickets appear immediately
        $sort = $request->get('sort', 'id_desc');
        if ($sort === 'id_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tickets = $query->with(['assignee', 'assigner', 'resolver', 'user'])->paginate(10);
        $users = User::all();

        return view('tickets.index', compact('tickets', 'users'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'priority'      => 'required|in:Low,Medium,High',
            'category'      => 'required|in:Hardware,Software,Network',
            'due_date'      => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Open';

        // Use a transaction to ensure the ticket is saved before redirecting
        $ticket = DB::transaction(function () use ($validated) {
            return Ticket::create($validated);
        });

        // Update the 'Recently Created' session widget
        $recent = session()->get('recent_created', []);
        array_unshift($recent, [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'date' => now()->format('d M, h:i A')
        ]);
        session()->put('recent_created', array_slice($recent, 0, 3));

        return redirect()->route('tickets.index')->with('success', 'Ticket #' . $ticket->id . ' created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $users = User::all();
        return view('tickets.show', compact('ticket', 'users'));
    }

    public function edit(Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized access.');
        }

        $users = User::all();
        return view('tickets.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $oldStatus = $ticket->status;
        $ticket->update($request->all());

        if ($oldStatus !== $ticket->status && $ticket->user) {
            $ticket->user->notify(new TicketUpdateNotification($ticket, 'status_change', Auth::user()->name));
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket #' . $ticket->id . ' has been updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticketId = $ticket->id;
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket #' . $ticketId . ' has been moved to trash.');
    }

    public function statistics(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));

        try {
            $targetDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth);
        } catch (\Exception $e) {
            $targetDate = now();
            $selectedMonth = $targetDate->format('Y-m');
        }

        $monthlyTickets = Ticket::whereMonth('created_at', $targetDate->month)
                                ->whereYear('created_at', $targetDate->year)
                                ->get();

        $stats = [
            'total'     => $monthlyTickets->count(),
            'open'      => $monthlyTickets->where('status', 'Open')->count(),
            'assigned'  => $monthlyTickets->where('status', 'Assigned')->count(),
            'on_hold'   => $monthlyTickets->where('status', 'On Hold')->count(),
            'resolved'  => $monthlyTickets->where('status', 'Resolved')->count(),
            'hardware'  => $monthlyTickets->where('category', 'Hardware')->count(),
            'software'  => $monthlyTickets->where('category', 'Software')->count(),
            'network'   => $monthlyTickets->where('category', 'Network')->count(),
            'high'      => $monthlyTickets->where('priority', 'High')->count(),
            'medium'    => $monthlyTickets->where('priority', 'Medium')->count(),
            'low'       => $monthlyTickets->where('priority', 'Low')->count(),
            'month_name'    => $targetDate->format('F Y'),
            'selected_month'=> $selectedMonth,
        ];

        return view('statistics', compact('stats'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'dashboard_image' => 'required|string',
            'month' => 'required|string'
        ]);

        $imageData = $request->input('dashboard_image');
        $selectedMonth = $request->input('month');

        try {
            $targetDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth);
        } catch (\Exception $e) {
            $targetDate = now();
        }

        $monthlyTickets = Ticket::whereMonth('created_at', $targetDate->month)
                                ->whereYear('created_at', $targetDate->year)
                                ->get();

        $stats = [
            'total'     => $monthlyTickets->count(),
            'open'      => $monthlyTickets->where('status', 'Open')->count(),
            'assigned'  => $monthlyTickets->where('status', 'Assigned')->count(),
            'on_hold'   => $monthlyTickets->where('status', 'On Hold')->count(),
            'resolved'  => $monthlyTickets->where('status', 'Resolved')->count(),
        ];

        $pdf = Pdf::loadView('tickets.pdf_report', compact('imageData', 'stats', 'monthlyTickets', 'targetDate'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('ICT_Monthly_Report_'.$targetDate->format('M_Y').'.pdf');
    }

    public function calendar()
    {
        $tickets = Ticket::whereNotNull('due_date')->get();

        $events = $tickets->map(function ($ticket) {
            $color = '#3b82f6';
            if ($ticket->priority === 'High') $color = '#ef4444';
            if ($ticket->priority === 'Medium') $color = '#FF8B5A';
            if ($ticket->priority === 'Low') $color = '#3b82f6';
            if ($ticket->status === 'Resolved') $color = '#9ca3af';

            return [
                'title' => 'Due: #' . $ticket->id . ' ' . $ticket->title,
                'start' => $ticket->due_date,
                'url'   => route('tickets.show', $ticket->id),
                'color' => $color,
                'allDay'=> true,
            ];
        });

        return view('calendar', compact('events'));
    }

    public function assignTask(Ticket $ticket)
    {
        $ticket->update([
            'assigned_to' => Auth::id(),
            'assigned_by' => Auth::id(),
            'status' => 'Assigned'
        ]);

        if ($ticket->user) {
            $ticket->user->notify(new TicketUpdateNotification($ticket, 'assigned', Auth::user()->name));
        }

        return back()->with('success', 'Task claimed successfully.');
    }

    public function unassignTask(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->assigned_to) {
            abort(403, 'Unauthorized action.');
        }

        $ticket->update([
            'status' => 'Open',
            'assigned_to' => null
        ]);

        return back()->with('success', 'Ticket dropped and set to Open.');
    }

    public function resolveTask(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->assigned_to && strtolower(Auth::user()->role) !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $ticket->update([
            'status' => 'Resolved',
            'resolved_by' => Auth::id()
        ]);

        if ($ticket->user) {
            $ticket->user->notify(new TicketUpdateNotification($ticket, 'resolved', Auth::user()->name));
        }

        return back()->with('success', 'Ticket Resolved.');
    }

    public function transferTask(Request $request, Ticket $ticket)
    {
        $request->validate(['new_user_id' => 'required|exists:users,id']);

        $ticket->update([
            'assigned_to' => $request->new_user_id,
            'assigned_by' => Auth::id(),
            'status' => 'Assigned'
        ]);

        $newAssignee = User::find($request->new_user_id);
        if ($newAssignee) {
            $newAssignee->notify(new TicketUpdateNotification($ticket, 'transferred', Auth::user()->name));
        }

        return back()->with('success', 'Task reassigned.');
    }

    public function undoResolve(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'Assigned',
            'resolved_by' => null,
        ]);

        return back()->with('success', 'Resolution undone.');
    }

    public function trash(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::onlyTrashed();

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('reporter_name', 'like', '%' . $request->search . '%')
                ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy('deleted_at', 'desc');
        $deletedTickets = $query->paginate(10);

        return view('tickets.deleted', compact('deletedTickets'));
    }

    public function forceDelete($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $ticket = Ticket::withTrashed()->findOrFail($id);
        $ticketTitle = $ticket->title;

        $purged = session()->get('recent_purges', []);
        array_unshift($purged, ['id' => $id, 'title' => $ticketTitle, 'date' => now()->format('d M, h:i A')]);
        session()->put('recent_purges', array_slice($purged, 0, 3));

        $ticket->forceDelete();

        return back()->with('success', "Ticket #$id permanently erased.");
    }

    public function restore($id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $ticket->restore();
        return redirect()->route('tickets.index')->with('success', 'Ticket restored successfully!');
    }
}
