<?php

namespace App\Providers;

use App\Models\UserLog;
use App\Models\Ticket;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
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
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                // Fetch recent activity including tickets you created, are assigned to, or assigned to others
                $notifications = Ticket::withTrashed() // Allows deleted tickets to appear in the log
                    ->where(function($q) {
                        $q->where('user_id', Auth::id())
                          ->orWhere('assigned_to', Auth::id())
                          ->orWhere('assigned_by', Auth::id());
                    })
                    // Eager load relationships to show avatars and names without extra queries
                    ->with(['assignee', 'assigner'])
                    ->latest('updated_at')
                    ->take(10) // Increased to 10 to match your new high-density design
                    ->get();

                $view->with('globalNotifications', $notifications);
            }
        });
    }
}
