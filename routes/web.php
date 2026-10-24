<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home & Comics
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/truyen', [ComicController::class, 'index'])->name('comics.index');
Route::get('/truyen/{slug}', [ComicController::class, 'show'])->name('comics.show');
Route::get('/truyen/{comicSlug}/{chapterSlug}', [ReaderController::class, 'read'])->name('comics.read');

// Ajax APIs (Search, Interactions, Chapter Unlock)
Route::get('/api/search', [ComicController::class, 'searchAjax'])->name('api.search');
Route::post('/api/chapters/{id}/unlock', [ReaderController::class, 'unlockAjax'])->name('api.chapters.unlock');
Route::post('/api/comics/{id}/favorite', [InteractionController::class, 'toggleFavorite'])->name('api.comics.favorite');
Route::post('/api/comics/{id}/comments', [InteractionController::class, 'postComment'])->name('api.comics.comment');
Route::post('/api/comics/{id}/rate', [InteractionController::class, 'rateComic'])->name('api.comics.rate');

// Authentication
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [AuthController::class, 'profile'])->name('profile');

    // Wallet & Coin Monetization
    Route::get('/vi-xu', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/vi-xu/nap-tien', [WalletController::class, 'processTopup'])->name('wallet.topup');
    Route::post('/vi-xu/mua-vip', [WalletController::class, 'buyVipSubscription'])->name('wallet.buyVip');
});

// Simulation Payment Gateway Callback (Accessible for testing/defense)
Route::get('/vi-xu/xac-nhan-thanh-toan/{code}', [WalletController::class, 'completeSimulationPayment'])->name('wallet.simulateSuccess');

// E-Commerce Merch Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/san-pham/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/api/cart/add', [ShopController::class, 'addToCart'])->name('cart.add');
Route::post('/api/cart/update', [ShopController::class, 'updateCart'])->name('cart.update');
Route::get('/gio-hang', [ShopController::class, 'viewCart'])->name('cart.view');
Route::get('/thanh-toan', [ShopController::class, 'checkout'])->name('checkout');
Route::post('/thanh-toan/dat-hang', [ShopController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::get('/don-hang/{code}/thanh-cong', [ShopController::class, 'orderSuccess'])->name('shop.orderSuccess');

// Admin Panel (Protected by Auth & Admin Role)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Comics
    Route::get('/comics', [AdminController::class, 'comicsIndex'])->name('comics.index');
    Route::get('/comics/create', [AdminController::class, 'comicsCreate'])->name('comics.create');
    Route::post('/comics', [AdminController::class, 'comicsStore'])->name('comics.store');
    Route::get('/comics/{id}/edit', [AdminController::class, 'comicsEdit'])->name('comics.edit');
    Route::put('/comics/{id}', [AdminController::class, 'comicsUpdate'])->name('comics.update');
    Route::delete('/comics/{id}', [AdminController::class, 'comicsDestroy'])->name('comics.destroy');

    // Chapters
    Route::get('/comics/{comicId}/chapters', [AdminController::class, 'chaptersIndex'])->name('chapters.index');
    Route::get('/comics/{comicId}/chapters/create', [AdminController::class, 'chaptersCreate'])->name('chapters.create');
    Route::post('/comics/{comicId}/chapters', [AdminController::class, 'chaptersStore'])->name('chapters.store');
    Route::delete('/comics/{comicId}/chapters/{chapterId}', [AdminController::class, 'chaptersDestroy'])->name('chapters.destroy');

    // Orders
    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('orders.index');
    Route::post('/orders/{id}/status', [AdminController::class, 'ordersUpdateStatus'])->name('orders.updateStatus');

    // Users
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::put('/users/{id}', [AdminController::class, 'usersUpdate'])->name('users.update');
});
