<?php

use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info', function () {
    phpinfo();
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('thumbnail')->name('thumbnail.')->group(function(){
    Route::post('/', [ThumbnailController::class, 'store'])->name('store');
    Route::patch('/change', [ThumbnailController::class, 'change'])->middleware(['thumbnail.have'])->name('change');
    Route::delete('/', [ThumbnailController::class, 'delete'])->middleware(['thumbnail.have'])->name('delete');
});

Route::prefix('profile')->name('profile.')->group(function(){
    Route::get('/edit', [UserController::class, 'edit'])->name('edit');
    Route::get('/show', [UserController::class, 'show'])->name('show');
    Route::post('/store', [UserController::class, 'store'])->name('store');
});

Route::prefix('account')->name('account.')->group(function(){
    Route::get('/edit', [UserController::class, 'edit'])->name('edit'); //一時的なもの　いずれ消す
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::patch('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'delete'])->name('delete');
});

require __DIR__.'/auth.php';
