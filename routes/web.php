<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ThreadSubscriptionController;
use App\Http\Controllers\UserNotificationsController;
use App\Models\Reply;
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

Route::get('/', function () {
    return view('welcome');
});
//write route for threads with optional channel param
Route::get('threads/create', [ThreadController::class, 'create'])->name('threads.create');
Route::get('threads/{channel}/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::post('threads', [ThreadController::class, 'store'])->name('threads.store');
Route::get('threads/{channel?}', [ThreadController::class, 'index'])->name('threads.index');
Route::delete('threads/{channel}/{thread}', [ThreadController::class, 'destroy'])->name('threads.delete');

Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store'])->name('favorites.store');
Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy'])->name('favorites.delete');

Route::get('/threads/{channel}/{thread}/replies', [ReplyController::class, 'index'])->name('replies.index');
Route::post('/threads/{channel}/{thread}/replies', [ReplyController::class, 'store'])->name('threads-reply.store')->middleware('throttle:1');
Route::patch('replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
Route::delete('replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.delete');

Route::post('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionController::class, 'store'])->name('threads-subsriptions.store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionController::class, 'destroy'])->name('threads-subsriptions.delete')->middleware('auth');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/{user}/notifications', [UserNotificationsController::class, 'index'])->name('profile.notifications.index');
    Route::delete('/profile/{user}/notifications/{notification}', [UserNotificationsController::class, 'destroy'])->name('profile.notifications.delete');
});

require __DIR__.'/auth.php';
