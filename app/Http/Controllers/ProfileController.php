<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Ticket;

class ProfileController extends Controller
{
    /**
     * Display the user's personal performance dashboard.
     */
    public function myPerformance(): View
    {
        $user = Auth::user();

        // 1. Fetch tickets assigned to the logged-in user
        $tickets = Ticket::where('assigned_to', $user->id)->get();

        // 2. Calculate Metric Cards
        $totalAssigned = $tickets->count();
        $resolvedCount = $tickets->where('status', 'Resolved')->count();
        $pendingCount = $tickets->whereIn('status', ['Open', 'Assigned', 'On Hold'])->count();

        // 3. Prepare Chart Data
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

        // 4. Get the 5 most recently resolved tasks
        $recentTasks = Ticket::where('assigned_to', $user->id)
            ->where('status', 'Resolved')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('profile.my_performance', compact(
            'user', 'totalAssigned', 'resolvedCount', 'pendingCount', 'recentTasks', 'chartData'
        ));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
