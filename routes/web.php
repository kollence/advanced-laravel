<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
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




Route::post('/threads/{channel}/{thread}/replies', [App\Http\Controllers\ReplyController::class, 'store'])->name('threads-reply.store');
Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store'])->name('favorites.store');

    
});

require __DIR__.'/auth.php';
