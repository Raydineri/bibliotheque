<?php
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Livres
    Route::resource('books', BookController::class);

    // Emprunts
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])
        ->name('loans.return');

    // Admin seulement
    Route::middleware('role:admin')->group(function () {
        Route::resource('authors', AuthorController::class);
        Route::resource('categories', CategoryController::class);
    });
});

// API Chatbot
Route::middleware(['auth'])->group(function () {
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/api/chatbot', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    Route::delete('/api/chatbot/history', [ChatbotController::class, 'clearHistory'])
        ->name('chatbot.clear');
});

require __DIR__.'/auth.php';
