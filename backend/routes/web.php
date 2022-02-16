<?php

use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SnsAccountController;
use App\Http\Controllers\FriendRequestController;
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

// サムネイル関連
Route::prefix('thumbnail')->name('thumbnail.')->group(function(){
    Route::post('/', [ThumbnailController::class, 'store'])->name('store');
    Route::patch('/change', [ThumbnailController::class, 'change'])->middleware(['thumbnail.have'])->name('change');
    Route::delete('/', [ThumbnailController::class, 'delete'])->middleware(['thumbnail.have'])->name('delete');
});

// プロフィール関連
Route::prefix('profile')->name('profile.')->group(function(){
    Route::get('/edit', [UserController::class, 'edit'])->name('edit');
    Route::get('/show', [UserController::class, 'show'])->name('show');
    Route::post('/store', [UserController::class, 'store'])->name('store');
});

// SNSアカウント関連
Route::prefix('account')->name('account.')->group(function(){
    Route::get('/edit', [SnsAccountController::class, 'edit'])->name('edit'); //一時的なもの　いずれ消す
    Route::post('/store', [SnsAccountController::class, 'store'])->name('store');
    Route::patch('/{id}', [SnsAccountController::class, 'update'])->name('update');
    Route::delete('/{id}', [SnsAccountController::class, 'delete'])->name('delete');
});

// フレンド申請
Route::prefix('friend/request')->name('friend.request.')->group(function(){
    Route::get('/create', [FriendRequestController::class, 'create'])->name('create');
    Route::post('/store', [FriendRequestController::class, 'store'])->name('store');
});

require __DIR__.'/auth.php';
