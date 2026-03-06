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
            // Tickets currently assigned to you for work
            $query->where('assigned_to', Auth::id());
        } elseif ($request->get('filter') === 'owned') {
            // Tickets you personally logged in the system
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

        // Final result with relationships and pagination
        $tickets = $query->with(['assignee', 'assigner', 'resolver'])->paginate(10);

        // Fetch users for any dropdowns you might have on the index page
        $users = \App\Models\User::all();

        return view('tickets.index', compact('tickets', 'users'));
    }

    public function create()
    {
        // Show the form to create a new ticket
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

        // Save to a "Recent Created" session list
        $recent = session()->get('recent_created', []);
        array_unshift($recent, [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'date' => now()->format('d M, h:i A')
        ]);
        session()->put('recent_created', array_slice($recent, 0, 3)); // Keep only the last 3

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    // Display the specified ticket
    public function show(Ticket $ticket)
    {
        // Get all users so we can populate the "Transfer To" dropdown
        $users = \App\Models\User::all();
        return view('tickets.show', compact('ticket', 'users'));
    }

    // Show the form to edit an existing ticket
    public function edit(Ticket $ticket)
    {
        // Block unauthorized edits
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            return redirect()->route('tickets.index')->with('error', 'Unauthorized access.');
        }

        $users = \App\Models\User::all();
        return view('tickets.edit', compact('ticket', 'users'));
    }

    // Update the ticket in the database
    public function update(Request $request, Ticket $ticket)
    {
        // Permission check...
        $ticket->update($request->all());

        // Send the message here
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket #' . $ticket->id . ' has been updated successfully!');
    }

    //Delete the ticket in the database
    public function destroy(Ticket $ticket)
    {
        // Permission check...
        $ticketId = $ticket->id;
        $ticket->delete();

        // Send the message here
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket #' . $ticketId . ' has been trashed.');
    }

    public function statistics(\Illuminate\Http\Request $request)
    {
        // 1. Get the requested month from the URL (Format: YYYY-MM)
        // If none is selected, default to the current month and year
        $selectedMonth = $request->query('month', now()->format('Y-m'));

        // 2. Safely parse the date using Carbon
        try {
            $targetDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth);
        } catch (\Exception $e) {
            // Fallback just in case someone types nonsense in the URL
            $targetDate = now();
            $selectedMonth = $targetDate->format('Y-m');
        }

        // 3. Fetch tickets for that exact month and year
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

            // Send formatted data to the view
            'month_name'    => $targetDate->format('F Y'), // e.g., "March 2026"
            'selected_month'=> $selectedMonth,             // e.g., "2026-03" (used for the HTML input)
        ];

        return view('statistics', compact('stats'));
    }

    public function exportPdf()
    {
        // 1. Get the data (Same as statistics)
        $monthlyTickets = Ticket::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->get();

        $stats = [
            'total'     => $monthlyTickets->count(),
            'open'      => $monthlyTickets->where('status', 'Open')->count(),
            'resolved'  => $monthlyTickets->where('status', 'Resolved')->count(),
            'high'      => $monthlyTickets->where('priority', 'High')->count(),
        ];

        // 2. Load the View into the PDF generator
        $pdf = Pdf::loadView('tickets.pdf_report', compact('stats', 'monthlyTickets'));

        // 3. Download the file
        return $pdf->download('ICT_Monthly_Report.pdf');
    }

    public function calendar()
    {
        // 1. Get ONLY tickets that have a Due Date
        $tickets = Ticket::whereNotNull('due_date')->get();

        // 2. Map them for the calendar
        $events = $tickets->map(function ($ticket) {

            // Color Logic: Red for High Priority, Blue for others
            $color = '#3b82f6';
            if ($ticket->priority === 'High') $color = '#ef4444';
            if ($ticket->priority === 'Medium') $color = '#FF8B5A';
            if ($ticket->priority === 'Low') $color = '#3b82f6';
            if ($ticket->status === 'Resolved') $color = '#9ca3af';

            return [
                // Title shows "Due:" so you know it's a deadline
                'title' => 'Due: #' . $ticket->id . ' ' . $ticket->title,

                // CRITICAL: This puts the event on the Due Date, not the Created Date
                'start' => $ticket->due_date,

                'url'   => route('tickets.show', $ticket->id),
                'color' => $color,
                'allDay'=> true, // Forces it to show as a block/banner
            ];
        });

        return view('calendar', compact('events'));
    }

    // 1. Claim a ticket
    // Update your assignment method (Claim Task)
    public function assignTask(Ticket $ticket)
    {
        $ticket->update([
            'assigned_to' => Auth::id(),
            'assigned_by' => Auth::id(),
            'status' => 'Assigned'
        ]);

        return back()->with('success', 'Task claimed successfully.');
    }

    // 4. Drop a ticket (Unassign)
    public function unassignTask(Ticket $ticket)
    {
        // Security Check: Only the person assigned to it can drop it
        if (\Illuminate\Support\Facades\Auth::id() !== $ticket->assigned_to) {
            abort(403, 'Unauthorized action. You can only unassign your own tickets.');
        }

        // Reset it back to the general pool
        $ticket->update([
            'status' => 'Open',
            'assigned_to' => null
        ]);

        return back()->with('success', 'You have dropped this ticket. It is now Open for others to claim.');
    }

    // 2. Resolve a ticket (Strict Security!)
    public function resolveTask(Ticket $ticket)
    {
        // Ensure security check remains
        if (Auth::id() !== $ticket->assigned_to && strtolower(Auth::user()->role) !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $ticket->update([
            'status' => 'Resolved',
            'resolved_by' => Auth::id() // Record who finished the task
        ]);

        return back()->with('success', 'Ticket Resolved.');
    }

    public function transferTask(Request $request, Ticket $ticket)
    {
        $request->validate(['new_user_id' => 'required|exists:users,id']);

        $ticket->update([
            'assigned_to' => $request->new_user_id,
            'assigned_by' => Auth::id(), // Tracks that YOU were the one who moved it
            'status' => 'Assigned'
        ]);

        return back()->with('success', 'Task reassigned.');
    }

    // NEW METHOD: Allows Nathan Drake to reopen a task
    public function undoResolve(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'Assigned',
            'resolved_by' => null,
        ]);

        return back()->with('success', 'Resolution undone. Ticket is now active again.');
    }
    //Trash ticket
    // 1. Search and Sort the Recycle Bin
    public function trash(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::onlyTrashed();

        // Restricted View: Staff only see their own trash
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Handle Search inside Trash
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('reporter_name', 'like', '%' . $request->search . '%')
                ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        // Handle Sorting (Latest Deleted first by default)
        $sort = $request->get('sort', 'deleted_desc');
        if ($sort === 'deleted_asc') {
            $query->orderBy('deleted_at', 'asc');
        } else {
            $query->orderBy('deleted_at', 'desc');
        }

        $deletedTickets = $query->paginate(10);
        return view('tickets.deleted', compact('deletedTickets'));
    }

    // 2. Permanent Delete (Admin Only)
    public function forceDelete($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $ticket = Ticket::withTrashed()->findOrFail($id);
        $ticketTitle = $ticket->title; // Save name before it's gone

        // Save to a "Recent Purges" session list
        $purged = session()->get('recent_purges', []);
        array_unshift($purged, ['id' => $id, 'title' => $ticketTitle, 'date' => now()->format('d M, h:i A')]);
        session()->put('recent_purges', array_slice($purged, 0, 3)); // Keep only the last 3

        $ticket->forceDelete();

        return back()->with('success', "Ticket #$id has been permanently erased from the system.");
    }
    //Restore ticket
    public function restore($id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);

        // Permission check
        if (Auth::user()->role !== 'admin' && Auth::id() !== $ticket->user_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $ticket->restore();
        return redirect()->route('tickets.index')->with('success', 'Ticket restored successfully!');
    }
}
