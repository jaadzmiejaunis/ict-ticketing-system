<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's personal performance profile.
     * PRESERVED: Every line of your original Ticket and Chart logic.
     */
    public function myPerformance(): View
    {
        $user = Auth::user();

        // 1. Fetch tickets assigned to you
        $tickets = Ticket::where('assigned_to', $user->id)->get();

        // 2. Calculate Metric Cards
        $totalAssigned = $tickets->count();
        $resolvedCount = $tickets->where('status', 'Resolved')->count();
        $pendingCount = $tickets->whereIn('status', ['Open', 'Assigned', 'On Hold'])->count();

        // 3. Prepare Chart Data using exact DB Enum values
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

        // 4. Get the 5 most recently resolved tasks for the table
        $recentTasks = Ticket::where('assigned_to', $user->id)
            ->where('status', 'Resolved')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('profile.my_performance', compact(
            'user', 'totalAssigned', 'resolvedCount', 'pendingCount', 'recentTasks', 'chartData'
        ));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * MASTER UPDATE: Updates Identity, Avatar, and Password in one request.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Verify Google reCAPTCHA v2 (Single verification for the combined form)
        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        $result = $response->json();

        if (!$result['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'Security check failed. Please confirm you are not a robot.'])->withInput();
        }

        $user = $request->user();

        // 2. Conditional Password Update (Triggers only if current_password is provided)
        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user->password = Hash::make($request->password);
        }

        // 3. Strict Image Validation
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        }

        // 4. Update Profile Details
        $user->fill($request->validated());

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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
