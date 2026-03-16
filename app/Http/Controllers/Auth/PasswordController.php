<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Http;

class PasswordController extends Controller
{
    /**
     * Update the user's password with reCAPTCHA verification.
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Verify Google reCAPTCHA v2 Checkbox
        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        $result = $response->json();

        // If checkbox is NOT clicked, stop and return error to the password bag
        if (!$result['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'Please confirm you are not a robot.'], 'updatePassword')->withInput();
        }

        // 2. Validate Password Fields
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // 3. Update the Database
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 4. Success Redirect with Status (Triggers emerald notification)
        return back()->with('status', 'password-updated');
    }
}
