<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;
use Carbon\Carbon;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Only run this check if the user is actually logged in
        if (Auth::check()) {
            $latestLog = UserLog::where('user_id', Auth::id())->latest('login_at')->first();

            if (!$latestLog) {
                // Failsafe: If they somehow have zero logs, create their first one
                UserLog::create(['user_id' => Auth::id(), 'login_at' => now()]);
            }
            elseif ($latestLog->logout_at !== null) {
                // They have a logout stamp. We need to find out exactly how long ago it happened.
                $logoutTime = Carbon::parse($latestLog->logout_at);

                // If the logout stamp is less than 5 minutes old, they just clicked a link
                // to navigate to a new page. Erase the stamp so they stay 'Active'.
                if ($logoutTime->diffInMinutes(now()) < 5) {
                    $latestLog->update(['logout_at' => null]);
                }
                else {
                    // If it has been more than 5 minutes, they actually left the system and
                    // came back later (Remember Me). Generate a BRAND NEW login log!
                    UserLog::create(['user_id' => Auth::id(), 'login_at' => now()]);
                }
            }
        }

        return $next($request);
    }
}
