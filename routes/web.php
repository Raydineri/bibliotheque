<?php
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', fn() => redirect('/dashboard'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Livres (tout le monde)
    Route::resource('books', BookController::class);

    // Emprunts
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    // Profil membre
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Chatbot
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/api/chatbot', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    Route::delete('/api/chatbot/history', [ChatbotController::class, 'clearHistory'])->name('chatbot.clear');

    // ===== ADMIN UNIQUEMENT =====
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Gestion utilisateurs
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Auteurs & Catégories
        Route::resource('authors', AuthorController::class);
        Route::resource('categories', CategoryController::class);
    });
});

require __DIR__.'/auth.php';
