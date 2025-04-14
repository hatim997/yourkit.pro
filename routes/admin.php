<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcommerceController;
use App\Http\Controllers\Admin\ExtraController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductBundleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductEcomController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TaxController;
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

Route::group(['as'=>'admin.'], function(){
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');


    Route::group(['middleware' => ['auth:admin', 'role:admin']], function () {
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('change-password', [LoginController::class, 'changePassword'])->name('change.password');
        Route::post('change-pwd', [LoginController::class, 'changePasswordPost'])->name('change.password.post');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::resource('sub-categories', SubCategoryController::class);

        Route::resource('attributes', AttributeController::class);

        Route::resource('products', ProductController::class);

        Route::resource('ecommerce', EcommerceController::class);

        Route::resource('contact', ContactController::class);

        Route::resource('orders', OrderController::class);

        Route::get('order-detail/{id}', [OrderController::class, 'detail'])->name('orders.detail');

        Route::post('update-order-status', [OrderController::class, 'updateOrderStatus'])->name('order.status');


        // Route::post('logos/{id}', [OrderController::class, 'logoDisplay'])->name('logo.upload');

        Route::resource('products-bundle', ProductBundleController::class);

        Route::resource('banners', BannerController::class);

        Route::resource('settings', SettingController::class);

        // Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        // Route::get('settings/{id}', [SettingController::class, 'edit'])->name('settings.edit');
        // Route::put('settings/{id}', [SettingController::class, 'update'])->name('settings.update');

        Route::get('extra', [ExtraController::class, 'getAttributesValue'])->name('attribute.value');

        Route::resource('faq', FaqController::class);

        Route::resource('page', PageController::class);

        Route::resource('tax', TaxController::class);

        Route::get('invoice/{id}', [InvoiceController::class, 'view'])->name('invoice.show');

        Route::resource('/newslettersubscribers', App\Http\Controllers\Admin\NewsletterSubscriberController::class);

        //Email Template
        Route::resource('/email-templates', App\Http\Controllers\Admin\EmailTemplateController::class);

    });


});
