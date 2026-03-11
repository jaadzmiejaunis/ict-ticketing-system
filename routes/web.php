<?php

use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // 1. Profile & Performance Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-performance', [ProfileController::class, 'myPerformance'])->name('my.performance');

    // 2. Notifications (FIXED: These now prevent the ribbon crash)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');

    // 3. Features & Statistics
    Route::get('/statistics', [TicketController::class, 'statistics'])->name('statistics');
    Route::get('/tickets/export-pdf', [TicketController::class, 'exportPdf'])->name('tickets.pdf');
    Route::get('/calendar', [TicketController::class, 'calendar'])->name('calendar');

    // 4. Admin Panel & Account Management
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/staff', [AdminController::class, 'storeStaff'])->name('admin.store_staff');
    Route::get('/admin/accounts', [AdminController::class, 'manageAccounts'])->name('admin.accounts');
    Route::get('/admin/accounts/create', [AdminController::class, 'createAccount'])->name('admin.accounts.create');
    Route::get('/admin/accounts/{user}/edit', [AdminController::class, 'editAccount'])->name('admin.accounts.edit');
    Route::put('/admin/accounts/{user}', [AdminController::class, 'updateAccount'])->name('admin.accounts.update');
    Route::patch('/admin/accounts/{user}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.accounts.toggle');
    Route::delete('/admin/accounts/{user}', [AdminController::class, 'deleteAccount'])->name('admin.accounts.delete');
    Route::get('/admin/accounts/{user}/history', [AdminController::class, 'history'])->name('admin.accounts.history');
    Route::get('/admin/accounts/deletion-history', [AdminController::class, 'deletionHistory'])->name('admin.accounts.deletions');
    Route::get('/admin/accounts/{user}/performance', [AdminController::class, 'performance'])->name('admin.accounts.performance');

    // 5. Ticket Management (Removed Duplicates)
    Route::get('/tickets/trash', [TicketController::class, 'trash'])->name('tickets.trash');
    Route::post('/tickets/{id}/restore', [TicketController::class, 'restore'])->name('tickets.restore');
    Route::delete('/tickets/{id}/force-delete', [TicketController::class, 'forceDelete'])->name('tickets.force-delete');

    Route::resource('tickets', TicketController::class);

    // Task Assignment & Resolution
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assignTask'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/unassign', [TicketController::class, 'unassignTask'])->name('tickets.unassign');
    Route::post('/tickets/{ticket}/transfer', [TicketController::class, 'transferTask'])->name('tickets.transfer');
    Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolveTask'])->name('tickets.resolve');
    Route::post('/tickets/{ticket}/undo-resolve', [TicketController::class, 'undoResolve'])->name('tickets.undo-resolve');
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');

    Route::get('tickets/{ticket}/comments', function($ticket) {
    return redirect()->route('tickets.show', $ticket);
    });
});

// 6. User Session Management
Route::post('/mark-offline', function () {
    if (Auth::check()) {
        $latestLog = \App\Models\UserLog::where('user_id', Auth::id())
                        ->latest('login_at')
                        ->first();

        if ($latestLog && is_null($latestLog->logout_at)) {
            $latestLog->update(['logout_at' => now()]);
        }
    }
    return response('', 204);
})->name('mark.offline');

require __DIR__.'/auth.php';
