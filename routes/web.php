<?php

use App\Http\Controllers\ProfileController;
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
// Route::get('/threads', [App\Http\Controllers\ThreadController::class, 'index'])->name('threads.index');
Route::get('/threads/{channel}/{thread}', [App\Http\Controllers\ThreadController::class, 'show'])->name('threads.show');
// Route::post('/threads', [App\Http\Controllers\ThreadController::class, 'store'])->name('threads.store');
// Route::resource('threads', [App\Http\Controllers\ThreadController::class]);

Route::resource('threads', App\Http\Controllers\ThreadController::class);
Route::post('/threads/{channel}/{thread}/replies', [App\Http\Controllers\ReplyController::class, 'store'])->name('threads-reply.store');

Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
