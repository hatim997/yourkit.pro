<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
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
// Route::get('/storage-link', function () {
//     Artisan::call('storage:link');
//     return 'Storage link has been created!';
// });

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return 'All caches cleared successfully!';
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('faq', [HomeController::class, 'faq'])->name('faq');
Route::get('terms', [HomeController::class, 'terms'])->name('terms');
Route::get('privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('return-policy', [HomeController::class, 'return'])->name('return');
Route::get('delivery-info', [HomeController::class, 'deliveryInfo'])->name('delivery.info');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('product', [ProductController::class, 'index'])->name('product');
// Route::get('product/{meta}/{page}', [ProductController::class, 'view'])->name('product.page');
Route::get('product/{meta}', [ProductController::class, 'view'])->name('product.page');
Route::post('product', [ProductController::class, 'store'])->name('product.store');
Route::get('ecommerce', [EcommerceController::class, 'index'])->name('ecommerce');
Route::get('ecommerce/{meta}', [EcommerceController::class, 'details'])->name('ecom.details');
Route::get('ecommerce/category/{id}', [EcommerceController::class, 'categoryIndex'])->name('ecommerce.category');

Route::post('/fetch-attributes', [EcommerceController::class, 'fetchAttributes'])->name('fetch.ecom.attr');

Route::post('ecommerce/cart/submit', [CartController::class, 'submitCartEcom'])->name('ecom.cart.submit');

Route::get('cart', [CartController::class, 'index'])->name('cart');
Route::get('cart/view/{sessionId}', [CartController::class, 'getCart'])->name('cart.all');
Route::get('cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::post('cart/submit', [CartController::class, 'submitCart'])->name('cart.submit');
Route::get('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::get('cart/edit/{id}', [CartController::class, 'edit'])->name('cart.edit');
Route::get('ecom-cart/edit/{id}', [CartController::class, 'ecomEdit'])->name('ecom-cart.edit');
Route::post('ecom-cart/update/{id}', [CartController::class, 'ecomUpdate'])->name('ecom-cart.update');
Route::post('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

Route::post('cart/ecommerce', [EcommerceController::class, 'submit'])->name('ecommerce.submit');


Route::get('signin', [AuthController::class, 'index'])->name('signin');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('forget-password', [AuthController::class, 'submitForgetPassword'])->name('forget.password.post');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset.password');
Route::post('reset-password', [AuthController::class, 'submitResetPassword'])->name('reset.password.post');
Route::post('contact', [DashboardController::class, 'contact'])->name('contact.post');
//NewsLetter
Route::post('newslettermail', [HomeController::class, 'sendMail'])->name('send.newslettermail');
Route::group(['middleware' => ['auth:web', 'role:user']], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('order', [App\Http\Controllers\OrderController::class, 'index'])->name('order.view');
    Route::get('order-detail/{id}', [App\Http\Controllers\OrderController::class, 'orderDetail'])->name('order.detail');
    Route::post('accept-logo', [App\Http\Controllers\OrderController::class, 'acceptLogo'])->name('accept.logo');

    Route::get('profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::post('profile/edit', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('upload-image', [DashboardController::class, 'uploadImage'])->name('upload.image');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'submit'])->name('checkout.store');

    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('checkout/failed', [CheckoutController::class, 'failed'])->name('checkout.failed');
    Route::get('checkout/held', [CheckoutController::class, 'held'])->name('checkout.held');
});

Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware(["auth:web"]);
