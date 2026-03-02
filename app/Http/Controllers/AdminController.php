<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // Security check: Kick out non-admins
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Get ONLY ACTIVE staff and admin members
        $staffMembers = User::where('role', 'staff')->where('is_active', true)->get();
        $adminMembers = User::where('role', 'admin')->where('is_active', true)->get();

        // Get the latest 50 login logs
        $logs = UserLog::with('user')->latest('login_at')->take(50)->get();

        return view('admin.dashboard', compact('staffMembers', 'adminMembers', 'logs'));
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

        // Capture search and filter inputs from the URL
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        // Build the query for Active Users
        $activeQuery = User::where('is_active', true);

        // Build the query for Inactive Users
        $inactiveQuery = User::where('is_active', false);

        // Apply Search Filter (Name or Email)
        if ($search) {
            $activeQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });

            $inactiveQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply Role Filter
        if ($roleFilter) {
            $activeQuery->where('role', $roleFilter);
            $inactiveQuery->where('role', $roleFilter);
        }

        // Execute queries: Sort by role category first, then alphabetically by name
        $activeUsers = $activeQuery->orderBy('role')->orderBy('name')
            ->paginate(10, ['*'], 'active_page')
            ->withQueryString(); // Keeps search terms in URL when clicking "Next Page"

        $inactiveUsers = $inactiveQuery->orderBy('role')->orderBy('name')
            ->paginate(10, ['*'], 'inactive_page')
            ->withQueryString();

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

        // Flip the status (if true, make false. If false, make true)
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Account {$status} successfully!");
    }

    // 5. Permanently Delete a User
    public function deleteAccount(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Security Check: Prevent the admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account!']);
        }

        $user->delete();

        return back()->with('success', 'User account permanently deleted.');
    }
}
