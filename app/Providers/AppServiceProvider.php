<?php

namespace App\Providers;

use App\Models\UserLog;
use App\Models\Ticket;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. SESSION TRACKING: Login Event
        Event::listen(Login::class, function (Login $event) {
            UserLog::create([
                'user_id' => $event->user->id,
                'login_at' => now(),
            ]);
        });

        // 2. SESSION TRACKING: Logout Event
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

        // 3. GLOBAL NOTIFICATIONS: Shares ticket alerts with the navigation bar
        view()->composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $notifications = Ticket::where(function($q) {
                    $q->where('user_id', Auth::id())        // Tickets I created
                      ->orWhere('assigned_to', Auth::id())   // Tasks for me
                      ->orWhere('assigned_by', Auth::id());  // Tasks I gave (Admin)
                })
                ->with(['assignee', 'assigner'])
                ->latest('updated_at')
                ->take(5)
                ->get();

                $view->with('globalNotifications', $notifications);
            }
        });
    }
}
