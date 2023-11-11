<?php

use App\Http\Controllers\Api\UserAvatarController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BestReplyController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ThreadSubscriptionController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\ConfirmedEmailCanCreate;
use Illuminate\Support\Facades\Redis;
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

Route::get('/register/confirm', [RegisteredUserController::class, 'confirm'])
->name('register.confirm');
//write route for threads with optional channel param
Route::controller(ThreadController::class)->group(function()
{
    Route::get('threads/create', 'create')->name('threads.create');
    Route::get('threads/{thread}/edit', 'edit')->name('threads.edit');
    Route::patch('threads/{thread}/update', 'update')->name('threads.update');
    Route::get('threads/{channel}/{thread}', 'show')->name('threads.show');
    Route::post('threads', 'store')->name('threads.store')->middleware(ConfirmedEmailCanCreate::class);
    Route::get('threads/{channel?}', 'index')->name('threads.index');
    Route::delete('threads/{channel}/{thread}', 'destroy')->name('threads.delete');

    Route::post('threads/{channel}/{thread}/lock', 'lock')->name('threads.lock')->middleware(Admin::class);
    Route::post('threads/{channel}/{thread}/unlock', 'unlock')->name('threads.unlock')->middleware(Admin::class);
});

Route::controller(ReplyController::class)->group(function()
{
    Route::get('/threads/{channel}/{thread}/replies', 'index')->name('replies.index');
    Route::post('/threads/{channel}/{thread}/replies', 'store')->name('threads-reply.store');
    Route::patch('replies/{reply}', 'update')->name('replies.update');
    Route::delete('replies/{reply}', 'destroy')->name('replies.delete');
});
Route::post('/replies/{reply}/mark-best',[BestReplyController::class, 'store'])->name('mark-best-reply.store');
Route::post('/replies/{reply}/un-mark-best',[BestReplyController::class, 'destroy'])->name('un-mark-best-reply.destroy');

Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store'])->name('favorites.store');
Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy'])->name('favorites.delete');

Route::controller(ThreadSubscriptionController::class)->group(function()
{
    Route::post('/threads/{channel}/{thread}/subscriptions', 'store')->name('threads-subsriptions.store');
    Route::delete('/threads/{channel}/{thread}/subscriptions', 'destroy')->name('threads-subsriptions.destroy');
})->middleware('auth');


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

    Route::post('/api/users/{user}/avatar', [UserAvatarController::class, 'store'])->name('users.avatar.store');
});

Route::get('clear_cache', function() {
    Redis::flushall();
});
require __DIR__.'/auth.php';
