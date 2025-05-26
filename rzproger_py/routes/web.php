<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// О нас
Route::view('/about', 'about')->name('about');

// Маршруты для мероприятий
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Temporary route to update event dates
Route::get('/update-event-dates', function () {
    $events = \App\Models\Event::all();
    foreach ($events as $event) {
        // Set date to after May 28, 2025
        $randomDays = rand(0, 365); // Random days offset from May 28, 2025
        $newDate = \Carbon\Carbon::create(2025, 5, 28)->addDays($randomDays);
        $event->start_datetime = $newDate;
        $event->save();
    }
    return 'Event dates updated successfully!';
});

// Маршруты, требующие аутентификации
Route::middleware('auth')->group(function () {
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
    
    // Управление мероприятиями
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Управление избранными мероприятиями
    Route::post('/events/{event}/favorite', [EventController::class, 'favorite'])->name('events.favorite');
    Route::delete('/events/{event}/favorite', [EventController::class, 'unfavorite'])->name('events.unfavorite');
    
    // Управление комментариями
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Административная панель (доступна только администраторам)
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        
        // Управление мероприятиями
        Route::get('/events', [AdminController::class, 'events'])->name('events');
        Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
        Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
        Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
        Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [AdminController::class, 'destroyEvent'])->name('events.destroy');
        
        // Управление пользователями
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Управление комментариями
        Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
        Route::delete('/comments/{comment}', [AdminController::class, 'destroyComment'])->name('comments.destroy');
        
        // Очистка базы данных (только для разработки)
        Route::get('/clear-database', [AdminController::class, 'clearDatabase'])->name('clear-database');
        Route::post('/clear-database', [AdminController::class, 'clearTable'])->name('clear-table');
    });
});

// Маршруты аутентификации
require __DIR__.'/auth.php';
