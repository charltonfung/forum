<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\UserProfileController;

// Route::get('/', function(){
//     return view('welcome');
// });

Route::resource('articles', ArticlesController::class);

Route::get('/', [ArticlesController::class, 'index'])->name('root');

// ============================================================
// 留言（comments）
// ============================================================
Route::post('articles/{article}/comments', [CommentsController::class, 'store'])
    ->name('comments.store');
Route::delete('comments/{comment}', [CommentsController::class, 'destroy'])
    ->name('comments.destroy');

// ============================================================
// 點讚（likes）— 對 article / comment 各一組 like / unlike
// ============================================================
Route::post('articles/{article}/like',   [LikesController::class, 'likeArticle'])   ->name('articles.like');
Route::delete('articles/{article}/like', [LikesController::class, 'unlikeArticle']) ->name('articles.unlike');

Route::post('comments/{comment}/like',   [LikesController::class, 'likeComment'])   ->name('comments.like');
Route::delete('comments/{comment}/like', [LikesController::class, 'unlikeComment']) ->name('comments.unlike');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/user_profile', UserProfileController::class)
    ->name('user_profile');
