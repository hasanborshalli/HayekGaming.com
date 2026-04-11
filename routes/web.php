<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WatchesController;
use Illuminate\Support\Facades\Route;
use App\Models\Watch;


Route::middleware(['log'])->group(function () {
    Route::get('/testpage',[PagesController::class,'testing']);
    Route::get('/', [PagesController::class,'homePage']);
    
    Route::get('/coming-soon', [PagesController::class,'comingSoonPage']);
    
    Route::get('/product/{product}', [PagesController::class,'productDetailsPage']);
    Route::get('/watch/{watch}', [PagesController::class,'watchDetailsPage']);
    
    
    Route::get('/products/{category}', [PagesController::class,'productsPage']);
    Route::get('/products/category/{subCategory}', [PagesController::class,'productsBySubPage']);
    Route::get('/products/{subCategory}/{gameType}', [PagesController::class,'productsByGameType']);
    Route::get('/allGames/{subCategory}', [PagesController::class,'productsByAllGames'])->name('allGamesRoute');
    
    Route::get('/watches',[PagesController::class,'watchesPage']);
    Route::get('/bracelets',[PagesController::class,'braceletsPage']);
    
    Route::get('/search/products', [ProductController::class,'productsSearch']);
    Route::get('/search-products', [ProductController::class, 'search']);
    Route::get('/filterProducts', [ProductController::class,'filter']);
    
    Route::get('/cart', [PagesController::class,'cartPage']);
    Route::post('/addCart/{check?}', [OrderController::class,'addCart']);
    Route::post('/remove-from-cart', [OrderController::class, 'remove']);
    Route::post('/checkout', [OrderController::class,'checkout']);
    Route::get('/checkout', [PagesController::class,'checkoutPage']);
    Route::post('/order', [OrderController::class,'order']);
    Route::get('/thankyou', [PagesController::class,'ThankyouPage']);
    
    Route::get('/admin/login', [PagesController::class,'loginPage'])->middleware('guest')->name('login');
    Route::post('/admin/login', [AuthController::class,'login'])->middleware('guest');
});
Route::get('/logout', [AuthController::class,'Logout'])->middleware('auth');

Route::get('/admin', [PagesController::class,'adminPage'])->middleware('auth')->name('home');

Route::get('/admin/products', [PagesController::class,'manageProductsPage'])->middleware('auth');
Route::get('/admin/search/products', [ProductController::class,'adminProductsSearch'])->middleware('auth');
Route::get('/admin/filterProducts', [ProductController::class,'adminFilter'])->middleware('auth');
Route::get('/admin/products/add', [PagesController::class,'addProductPage'])->middleware('auth');
Route::post('/admin/addProduct', [ProductController::class,'addProduct'])->middleware('auth');
Route::get('/admin/editProduct/{product}', [PagesController::class,'editProductPage'])->middleware('auth');
Route::post('/admin/editProduct/{product}', [ProductController::class,'editProduct'])->middleware('auth');
Route::get('/admin/deleteProduct/{product}', [ProductController::class,'deleteProduct'])->middleware('auth');

Route::get('/admin/watches', [PagesController::class,'manageWatchesPage'])->middleware('auth');
Route::get('/admin/watches/add', [PagesController::class,'addWatchPage'])->middleware('auth');
Route::post('/admin/addWatch', [WatchesController::class,'addWatch'])->middleware('auth');
Route::get('/admin/deleteWatch/{watch}', [WatchesController::class,'deleteWatch'])->middleware('auth');
Route::get('/admin/editWatch/{watch}', [PagesController::class,'editWatchPage'])->middleware('auth');
Route::post('/admin/editWatch/{watch}', [WatchesController::class,'editWatch'])->middleware('auth');
Route::get('/admin/search/watches', [WatchesController::class,'adminWatchesSearch'])->middleware('auth');

Route::get('/admin/categories', [PagesController::class,'categoriesPage'])->middleware('auth');
Route::get('/admin/categories/add', [PagesController::class,'addCategoryPage'])->middleware('auth');
Route::post('/admin/addCategory', [CategoryController::class,'addCategory'])->middleware('auth');
Route::get('/admin/deleteCategory/{category}', [CategoryController::class,'deleteCategory'])->middleware('auth');
Route::get('/admin/categories/edit/{category}', [PagesController::class,'editCategoryPage'])->middleware('auth');
Route::post('/admin/categories/edit/{category}', [CategoryController::class,'editCategory'])->middleware('auth');
Route::get('/admin/deleteSub/{subCategory}', [CategoryController::class,'deleteSub'])->middleware('auth');

Route::get('/admin/orders', [PagesController::class,'OrdersPage'])->middleware('auth');
Route::get('/order/delete/{order}', [OrderController::class,'DeleteOrder'])->middleware('auth');
Route::get('/order/{order}', [PagesController::class,'OrderPage'])->middleware('auth');
Route::get('/finishOrder/{order}', [OrderController::class,'FinishOrder'])->middleware('auth');
Route::get('/order/edit/{order}', [PagesController::class,'EditOrderPage'])->middleware('auth');
Route::post('/order/edit/{order}', [OrderController::class,'EditOrder'])->middleware('auth');

Route::get('/get-subcategories/{category}', [ProductController::class,'getSubCategories'])->middleware('auth');

Route::get('/admin/banners', [PagesController::class,'bannersPage'])->middleware('auth');
Route::get('/admin/addBanner', [PagesController::class,'addBannerPage'])->middleware('auth');
Route::post('/admin/addBanner', [BannerController::class,'addBanner'])->middleware('auth');
Route::get('/admin/editBanner/{banner}', [PagesController::class,'editBannerPage'])->middleware('auth');
Route::post('/admin/editBanner/{banner}', [BannerController::class,'editBanner'])->middleware('auth');
Route::get('/admin/deleteBanner/{banner}', [BannerController::class,'deleteBanner'])->middleware('auth');

Route::get('/admin/comingSoon', [PagesController::class,'manageComingPage'])->middleware('auth');
Route::post('/admin/comingSoon/edit', [ComingController::class,'editComing'])->middleware('auth');
Route::get('/admin/comingSoon/deleteProduct/{comingProduct}',[ComingController::class,'deleteComingProduct'])->middleware('auth');
Route::post('/admin/comingSoon/addProduct',[ComingController::class,'addComingProduct'])->middleware('auth');
Route::post('/admin/comingSoon/editProduct/{comingProduct}',[ComingController::class,'editComingProduct'])->middleware('auth');

Route::get('/admin/changePassword', [PagesController::class,'changePasswordPage'])->middleware('auth');
Route::post('/admin/changePassword', [AuthController::class,'changePassword'])->middleware('auth');

Route::get('/admin/sentence', [PagesController::class,'sentencePage'])->middleware('auth');
Route::post('/admin/changeSentence', [AuthController::class,'changeSentence'])->middleware('auth');