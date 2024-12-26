<?php

use Illuminate\Support\Facades\Route;

use App\Providers\RouteServiceProvider;
use App\Http\Controllers\RecommendationController;

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
    return redirect(RouteServiceProvider::HOME);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect(route('recommend.index'));
    })->name('dashboard');

    Route::get('/wishlist', [RecommendationController::class, 'getWishlist'])->name('wishlist.index');

   

    Route::get('/recommend', [RecommendationController::class, 'index'])->name('recommend.index');
    
    Route::post('/recommend/search', [RecommendationController::class, 'search'])->name('recommend.search');
    
    Route::post('/recommend/results', [RecommendationController::class, 'getRecommendations'])->name('recommend.results');

    
    
    Route::post('/recommend/wishlist', [RecommendationController::class, 'addWishlist'])->name('recommend.wishlist');

    Route::post('/recommend/remove-wishlist', [RecommendationController::class, 'removeWishlist'])->name('recommend.remove-wishlist');
    

});
