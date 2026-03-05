<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 1. Apply Search Filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Apply Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 3. Apply Sorting Logic
        $sort = $request->get('sort', 'recent');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $activeUsers = (clone $query)->where('is_active', 1)->paginate(10)->withQueryString();
        $inactiveUsers = (clone $query)->where('is_active', 0)->paginate(10)->withQueryString();

        return view('admin.accounts.index', compact('activeUsers', 'inactiveUsers'));
    }

    // Add a new staff member
    // Add a new user
    public function storeStaff(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.accounts')->with('success', 'User account created successfully!');
    }

    // ==========================================
    // ACCOUNT MANAGEMENT SYSTEM
    // ==========================================

    // Show the Create User Form
    public function createAccount()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('admin.accounts.create');
    }

    // 1. Show the Account Management Page
    public function manageAccounts(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // 1. Capture search, filter, and sort inputs
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $sort = $request->get('sort', 'recent'); // Matches your "Sort By" dropdown

        $activeQuery = User::where('is_active', true);
        $inactiveQuery = User::where('is_active', false);

        // 2. Apply Search Filter
        if ($search) {
            $activeQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
            $inactiveQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Apply Role Filter
        if ($roleFilter) {
            $activeQuery->where('role', $roleFilter);
            $inactiveQuery->where('role', $roleFilter);
        }

        // 4. UPDATED: Multi-option Sorting Logic
        switch ($sort) {
            case 'oldest':
                $activeQuery->orderBy('created_at', 'asc');
                $inactiveQuery->orderBy('created_at', 'asc');
                break;
            case 'az': // Alphabetical A-Z
                $activeQuery->orderBy('name', 'asc');
                $inactiveQuery->orderBy('name', 'asc');
                break;
            case 'za': // Alphabetical Z-A
                $activeQuery->orderBy('name', 'desc');
                $inactiveQuery->orderBy('name', 'desc');
                break;
            default: // Defaults to 'recent' (Newest First)
                $activeQuery->orderBy('created_at', 'desc');
                $inactiveQuery->orderBy('created_at', 'desc');
                break;
        }

        // 5. Execute with dual-pagination
        $activeUsers = $activeQuery->paginate(10, ['*'], 'active_page')->withQueryString();
        $inactiveUsers = $inactiveQuery->paginate(10, ['*'], 'inactive_page')->withQueryString();

        return view('admin.accounts.index', compact('activeUsers', 'inactiveUsers'));
    }

    // 2. Show the Edit Form for a specific user
    public function editAccount(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.accounts.edit', compact('user'));
    }

    // 3. Save the updated user data
    public function updateAccount(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Added 'password' => 'nullable' so it doesn't force a change every time
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // If the admin typed a new password, hash it and add it to the update list
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.accounts')->with('success', 'Account updated successfully!');
    }

    // 4. Toggle the user's active status (Deactivate/Reactivate)
    public function toggleStatus(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Security Check: Prevent the admin from deactivating themselves!
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account!']);
        }

        // Flip the status boolean
        $user->update([
            'is_active' => !$user->is_active
        ]);

        // NEW: Log the status change
        \App\Models\UserStatusLog::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(), // Record which admin performed the action
            'new_status' => $user->is_active, // Stores 0 or 1
            'reason' => request('reason', 'System status toggle'),
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Account {$status} successfully!");
    }

    // 5. Permanently Delete a User
    public function deleteAccount(User $user)
    {
        // 1. Mandatory Security Check: Prevents self-deletion at the server level
        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'Security Violation: You cannot delete your own account.']);
        }

        // 2. Log details into the permanent Audit Trail
        \App\Models\UserDeleteLog::create([
            'user_name' => $user->name,
            'user_email' => $user->email,
            'admin_id' => Auth::id(),
            'reason' => request('reason') // Captured from the JS prompt box
        ]);

        // 3. Final Destruction
        $user->delete();

        return back()->with('success', 'User permanently removed and deletion reason logged.');
    }

    //Check deactivated staff account.
    public function history(User $user)
    {
        // Fetches logs for the specific user and includes the Admin's name
        $logs = \App\Models\UserStatusLog::with('admin')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json($logs);
    }

    public function deletionHistory()
    {
        // Fetches the permanent deletion records, newest first
        $logs = \App\Models\UserDeleteLog::with('admin')->latest()->paginate(15);

        return view('admin.accounts.deletions', compact('logs'));
    }

    // Fetch Staff Performance Statistics
    public function performance(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // 1. Fetch all tickets assigned to this specific user
        // IMPORTANT: Verify '\App\Models\Ticket' and 'assigned_to' match your database
        $tickets = \App\Models\Ticket::where('assigned_to', $user->id)->get();

        // 2. Calculate Top Cards
        $totalAssigned = $tickets->count();
        $resolvedCount = $tickets->where('status', 'Resolved')->count();
        $pendingCount = $tickets->whereIn('status', ['Open', 'Assigned', 'On Hold'])->count();

        // 3. Prepare data for the Visual Graphics (Charts)
        $chartData = [
            'status' => [
                'Open' => $tickets->where('status', 'Open')->count(),
                'Assigned' => $tickets->where('status', 'Assigned')->count(),
                'On Hold' => $tickets->where('status', 'On Hold')->count(),
                'Resolved' => $resolvedCount,
            ],
            'categories' => [
                'Hardware' => $tickets->where('category', 'Hardware')->count(),
                'Software' => $tickets->where('category', 'Software')->count(),
                'Network' => $tickets->where('category', 'Network')->count(),
            ],
            'priorities' => [
                'High' => $tickets->where('priority', 'High')->count(),
                'Medium' => $tickets->where('priority', 'Medium')->count(),
                'Low' => $tickets->where('priority', 'Low')->count(),
            ]
        ];

        // 4. Get recent tasks
        $recentTasks = \App\Models\Ticket::where('assigned_to', $user->id)
                            ->where('status', 'Resolved')
                            ->latest('updated_at')
                            ->take(5)
                            ->get();

        return view('admin.accounts.performance', compact('user', 'totalAssigned', 'resolvedCount', 'pendingCount', 'recentTasks', 'chartData'));
    }
}
