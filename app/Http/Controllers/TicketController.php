<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        // 1. Handle Priority Filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // 2. Handle Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Handle Filters (Assigned to Me vs My Created Tickets)
        if ($request->get('filter') === 'assigned_by_me') {
            $query->where('assigned_to', Auth::id());
        } elseif ($request->get('filter') === 'owned') {
            $query->where('user_id', Auth::id());
        }

        // 4. Handle Search Keywords
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('reporter_name', 'like', '%' . $request->search . '%')
                ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        // 5. Sequential ID Sorting
        $sort = $request->get('sort', 'id_desc');
        $query->orderBy('id', $sort === 'id_asc' ? 'asc' : 'desc');

        $tickets = $query->with(['assignee', 'assigner', 'resolver'])->paginate(10);
        $users = \App\Models\User::all();

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
        $ticket = Ticket::create($validated);

        $recent = session()->get('recent_created', []);
        array_unshift($recent, [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'date' => now()->format('d M, h:i A')
        ]);
        session()->put('recent_created', array_slice($recent, 0, 3));

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $users = \App\Models\User::all();
        return view('tickets.show', compact('ticket', 'users'));
    }

    public function edit(Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized access.');
        }

        $users = \App\Models\User::all();
        return view('tickets.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket #' . $ticket->id . ' has been updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticketId = $ticket->id;
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket #' . $ticketId . ' has been trashed.');
    }

    public function statistics(\Illuminate\Http\Request $request)
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

    // UPDATED EXPORT PDF METHOD
    public function exportPdf(Request $request)
    {
        $request->validate([
            'dashboard_image' => 'required|string',
            'month' => 'required|string'
        ]);

        $imageData = $request->input('dashboard_image');
        $selectedMonth = $request->input('month');

        // Parse the date safely
        try {
            $targetDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth);
        } catch (\Exception $e) {
            $targetDate = now();
        }

        // Fetch the detailed tickets for the table
        $monthlyTickets = Ticket::whereMonth('created_at', $targetDate->month)
                                ->whereYear('created_at', $targetDate->year)
                                ->get();

        // Re-calculate the stats for the crisp HTML summary boxes
        $stats = [
            'total'     => $monthlyTickets->count(),
            'open'      => $monthlyTickets->where('status', 'Open')->count(),
            'assigned'  => $monthlyTickets->where('status', 'Assigned')->count(),
            'on_hold'   => $monthlyTickets->where('status', 'On Hold')->count(),
            'resolved'  => $monthlyTickets->where('status', 'Resolved')->count(),
        ];

        $pdf = Pdf::loadView('tickets.pdf_report', compact('imageData', 'stats', 'monthlyTickets', 'targetDate'));

        // Portrait often looks more like a formal document, but we will use landscape to fit the charts nicely
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

        return back()->with('success', 'Task claimed successfully.');
    }

    public function unassignTask(Ticket $ticket)
    {
        if (\Illuminate\Support\Facades\Auth::id() !== $ticket->assigned_to) {
            abort(403, 'Unauthorized action. You can only unassign your own tickets.');
        }

        $ticket->update([
            'status' => 'Open',
            'assigned_to' => null
        ]);

        return back()->with('success', 'You have dropped this ticket. It is now Open for others to claim.');
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

        return back()->with('success', 'Task reassigned.');
    }

    public function undoResolve(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'Assigned',
            'resolved_by' => null,
        ]);

        return back()->with('success', 'Resolution undone. Ticket is now active again.');
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

        $sort = $request->get('sort', 'deleted_desc');
        if ($sort === 'deleted_asc') {
            $query->orderBy('deleted_at', 'asc');
        } else {
            $query->orderBy('deleted_at', 'desc');
        }

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

        return back()->with('success', "Ticket #$id has been permanently erased from the system.");
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
