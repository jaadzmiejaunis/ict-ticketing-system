<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/statistics', [TicketController::class, 'statistics'])->name('statistics');
    Route::get('/tickets/export-pdf', [TicketController::class, 'exportPdf'])->name('tickets.pdf');
    Route::get('/calendar', [TicketController::class, 'calendar'])->name('calendar');

    //Admin
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/staff', [App\Http\Controllers\AdminController::class, 'storeStaff'])->name('admin.store_staff');
    // Account Management Routes
    Route::get('/admin/accounts', [App\Http\Controllers\AdminController::class, 'manageAccounts'])->name('admin.accounts');
    Route::get('/admin/accounts/{user}/edit', [App\Http\Controllers\AdminController::class, 'editAccount'])->name('admin.accounts.edit');
    Route::put('/admin/accounts/{user}', [App\Http\Controllers\AdminController::class, 'updateAccount'])->name('admin.accounts.update');
    Route::patch('/admin/accounts/{user}/toggle', [App\Http\Controllers\AdminController::class, 'toggleStatus'])->name('admin.accounts.toggle');
    Route::get('/admin/accounts/create', [App\Http\Controllers\AdminController::class, 'createAccount'])->name('admin.accounts.create');
    Route::delete('/admin/accounts/{user}', [App\Http\Controllers\AdminController::class, 'deleteAccount'])->name('admin.accounts.delete');

    // Add this line for the tickets:
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [App\Http\Controllers\TicketController::class, 'assignTask'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/unassign', [App\Http\Controllers\TicketController::class, 'unassignTask'])->name('tickets.unassign');
    Route::post('/tickets/{ticket}/resolve', [App\Http\Controllers\TicketController::class, 'resolveTask'])->name('tickets.resolve');
    Route::post('/tickets/{ticket}/transfer', [App\Http\Controllers\TicketController::class, 'transferTask'])->name('tickets.transfer');
});

Route::post('/mark-offline', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        // ONLY grab the absolute most recent login for this user.
        $latestLog = \App\Models\UserLog::where('user_id', Illuminate\Support\Facades\Auth::id())
                                        ->latest('login_at')
                                        ->first();

        // Only stamp it if it doesn't already have a logout time
        if ($latestLog && is_null($latestLog->logout_at)) {
            $latestLog->update(['logout_at' => now()]);
        }
    }
    return response('', 204);
})->name('mark.offline');

require __DIR__.'/auth.php';
