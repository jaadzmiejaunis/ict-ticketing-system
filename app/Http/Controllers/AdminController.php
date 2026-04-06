<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLog;
use App\Models\UserStatusLog;
use App\Models\UserDeleteLog;
use App\Models\Ticket;
use App\Notifications\WelcomeStaffNotification;
use App\Notifications\AdminActivityNotification; // Ensure this notification class exists
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Helper to notify all administrators of an action.
     */
    private function notifyAdmins($notification)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify($notification);
        }
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $sort = $request->get('sort', 'recent');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $activeUsers = (clone $query)->where('is_active', 1)->paginate(10, ['*'], 'active_page')->withQueryString();
        $inactiveUsers = (clone $query)->where('is_active', 0)->paginate(10, ['*'], 'inactive_page')->withQueryString();

        return view('admin.accounts.index', compact('activeUsers', 'inactiveUsers'));
    }

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 1. Notify the new staff member
        $user->notify(new WelcomeStaffNotification($user, Auth::user()->name));

        // 2. Notify all admins of the new account creation
        $this->notifyAdmins(new AdminActivityNotification('created', $user, Auth::user()->name));

        return redirect()->route('admin.accounts')->with('success', 'User account created successfully!');
    }

    public function createAccount()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('admin.accounts.create');
    }

    public function manageAccounts(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $sort = $request->get('sort', 'recent');

        $activeQuery = User::where('is_active', true);
        $inactiveQuery = User::where('is_active', false);

        if ($search) {
            $activeQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
            $inactiveQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $activeQuery->where('role', $roleFilter);
            $inactiveQuery->where('role', $roleFilter);
        }

        switch ($sort) {
            case 'oldest':
                $activeQuery->orderBy('created_at', 'asc');
                $inactiveQuery->orderBy('created_at', 'asc');
                break;
            case 'az':
                $activeQuery->orderBy('name', 'asc');
                $inactiveQuery->orderBy('name', 'asc');
                break;
            case 'za':
                $activeQuery->orderBy('name', 'desc');
                $inactiveQuery->orderBy('name', 'desc');
                break;
            default:
                $activeQuery->orderBy('created_at', 'desc');
                $inactiveQuery->orderBy('created_at', 'desc');
                break;
        }

        $activeUsers = $activeQuery->paginate(10, ['*'], 'active_page')->withQueryString();
        $inactiveUsers = $inactiveQuery->paginate(10, ['*'], 'inactive_page')->withQueryString();

        return view('admin.accounts.index', compact('activeUsers', 'inactiveUsers'));
    }

    public function editAccount(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.accounts.edit', compact('user'));
    }

    public function updateAccount(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

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

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        // Notify admins that an account was updated
        $this->notifyAdmins(new AdminActivityNotification('updated', $user, Auth::user()->name));

        return redirect()->route('admin.accounts')->with('success', 'Account updated successfully!');
    }

    public function toggleStatus(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account!']);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        UserStatusLog::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'new_status' => $user->is_active,
            'reason' => request('reason', 'System status toggle'),
        ]);

        $statusAction = $user->is_active ? 'activated' : 'deactivated';

        // Notify admins of the status change
        $this->notifyAdmins(new AdminActivityNotification($statusAction, $user, Auth::user()->name));

        return back()->with('success', "Account {$statusAction} successfully!");
    }

    public function deleteAccount(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'Security Violation: You cannot delete your own account.']);
        }

        // Capture user data before deletion for the notification message
        $userData = ['name' => $user->name, 'email' => $user->email];

        UserDeleteLog::create([
            'user_name' => $user->name,
            'user_email' => $user->email,
            'admin_id' => Auth::id(),
            'reason' => request('reason')
        ]);

        $user->delete();

        // Notify admins of the permanent deletion
        $this->notifyAdmins(new AdminActivityNotification('permanently deleted', $userData, Auth::user()->name));

        return back()->with('success', 'User permanently removed and deletion reason logged.');
    }

    public function history(User $user)
    {
        $logs = UserStatusLog::with('admin')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json($logs);
    }

    public function deletionHistory()
    {
        $logs = UserDeleteLog::with('admin')->latest()->paginate(15);

        return view('admin.accounts.deletions', compact('logs'));
    }

    public function performance(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $tickets = Ticket::where('assigned_to', $user->id)->get();

        $totalAssigned = $tickets->count();
        $resolvedCount = $tickets->where('status', 'Resolved')->count();
        $pendingCount = $tickets->whereIn('status', ['Open', 'Assigned', 'On Hold'])->count();

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

        $recentTasks = Ticket::where('assigned_to', $user->id)
                            ->where('status', 'Resolved')
                            ->latest('updated_at')
                            ->take(5)
                            ->get();

        return view('admin.accounts.performance', compact('user', 'totalAssigned', 'resolvedCount', 'pendingCount', 'recentTasks', 'chartData'));
    }
}
