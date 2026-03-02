<?php

namespace App\Providers;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use App\Models\UserLog;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. EXPLICIT LOGIN: Always generate a brand new timestamp row!
        Event::listen(Login::class, function (Login $event) {
            UserLog::create([
                'user_id' => $event->user->id,
                'login_at' => now(),
            ]);
        });

        // 2. EXPLICIT LOGOUT: Always stamp the exact exit time!
        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                $latestLog = UserLog::where('user_id', $event->user->id)
                                    ->latest('login_at')
                                    ->first();

                if ($latestLog && is_null($latestLog->logout_at)) {
                    $latestLog->update(['logout_at' => now()]);
                }
            }
        });
    }
}
