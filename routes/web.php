<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\AttributeController;
use App\Http\Controllers\Dashboard\AttributeValueController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\EcommerceProductController;
use App\Http\Controllers\Dashboard\EcomProductAttributeController;
use App\Http\Controllers\Dashboard\KitProductBundleController;
use App\Http\Controllers\Dashboard\SubCategoryController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\KitProductController;
use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\ContactMessageController;
use App\Http\Controllers\Dashboard\EmailTemplateController;
use App\Http\Controllers\Dashboard\FaqController;
use App\Http\Controllers\Dashboard\NewsletterManagementController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RolePermission\PermissionController;
use App\Http\Controllers\Dashboard\RolePermission\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\TaxController;
use App\Http\Controllers\Dashboard\PromoCodeController;
use App\Http\Controllers\Dashboard\User\ArchivedUserController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\EcommerceController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Middleware\CheckAccountActivation;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

Route::get('/lang/{lang}', function ($lang) {
    // dd($lang);
    if (! in_array($lang, ['en', 'fr', 'ar', 'de'])) {
        abort(404);
    } else {
        session(['locale' => $lang]);
        App::setLocale($lang);
        Log::info("Locale set to: " . $lang);
        return redirect()->back();
    }
})->name('lang');

Route::get('/current-time', function () {
    return response()->json([
        'time' => Carbon::now()->format('h:iA') // Returns time in 12-hour format with AM/PM
    ]);
});

Auth::routes();
Route::get('/admin', function () {
    return redirect()->route('dashboard');
});
// Guest Routes
Route::group(['middleware' => ['guest']], function () {

    //User Login Authentication Routes
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-attempt', [LoginController::class, 'login_attempt'])->name('login.attempt');

    Route::middleware(['redirect.dashboard'])->group(function () {
        //User Register Authentication Routes
        Route::get('register', [RegisterController::class, 'register'])->name('register');
        Route::post('registration-attempt', [RegisterController::class, 'register_attempt'])->name('register.attempt');

        // Google Authentication Routes
        Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.login');
        Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.login.callback');
        // Github Authentication Routes
        Route::get('auth/github', [GithubController::class, 'redirectToGithub'])->name('auth.github.login');
        Route::get('auth/github/callback', [GithubController::class, 'handleGithubCallback'])->name('auth.github.login.callback');
        // Facebook Authentication Routes
        // Route::controller(FacebookController::class)->group(function () {
        //     Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
        //     Route::get('auth/facebook/callback', 'handleFacebookCallback');
        // });
    });
});

// Authentication Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('login-verification', [AuthController::class, 'login_verification'])->name('login.verification');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('verify-account', [AuthController::class, 'verify_account'])->name('verify.account');
    Route::post('resend-code', [AuthController::class, 'resend_code'])->name('resend.code');

    // Verified notification
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verification_verify'])->middleware(['signed'])->name('verification.verify');
    Route::get('email/verify', [AuthController::class, 'verification_notice'])->name('verification.notice');
    Route::post('email/verification-notification', [AuthController::class, 'verification_send'])->middleware(['throttle:2,1'])->name('verification.send');
    // Verified notification
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/deactivated', function () {
        return view('errors.deactivated');
    })->name('deactivated');
    Route::middleware(['check.activation'])->group(function () {

        Route::resource('profile', ProfileController::class);
        Route::post('profile/setting/account/{id}', [ProfileController::class, 'accountDeactivation'])->name('account.deactivate');
        Route::post('profile/security/password/{id}', [ProfileController::class, 'passwordUpdate'])->name('update.password');

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::post('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);
        Route::get('/notifications/send-test-noti/{id}', [NotificationController::class, 'testNotification']);

        Route::get('admin/dashboard', [HomeController::class, 'index'])->name('dashboard');

        // Admin Dashboard Authentication Routes
        Route::prefix('admin/dashboard')->name('dashboard.')->group(function () {

            Route::resource('user', UserController::class);
            Route::resource('archived-user', ArchivedUserController::class);
            Route::get('user/restore/{id}', [ArchivedUserController::class, 'restoreUser'])->name('archived-user.restore');
            Route::get('user/status/{id}', [UserController::class, 'updateStatus'])->name('user.status.update');

            // Role & Permission Start
            Route::resource('permissions', PermissionController::class);

            Route::resource('roles', RoleController::class);
            //Role & Permission End

            // Setting Routes
            Route::resource('setting', SettingController::class);
            Route::put('company/setting/{id}', [SettingController::class, 'updateCompanySettings'])->name('setting.company.update');
            Route::put('recaptcha/setting/{id}', [SettingController::class, 'updateRecaptchaSettings'])->name('setting.recaptcha.update');
            Route::put('system/setting/{id}', [SettingController::class, 'updateSystemSettings'])->name('setting.system.update');
            Route::put('email/setting/{id}', [SettingController::class, 'updateEmailSettings'])->name('setting.email.update');
            Route::post('send-mail/setting', [SettingController::class, 'sendTestMail'])->name('setting.send_test_mail');

            // User Dashboard Authentication Routes

            //Category
            Route::resource('categories', CategoryController::class);

            //Sub Category
            Route::resource('sub-categories', SubCategoryController::class);

            //Attribute
            Route::resource('attributes', AttributeController::class);

            //Attribute Value
            Route::resource('attribute-values', AttributeValueController::class);

            //Kit Products
            Route::resource('kit-products', KitProductController::class);
            Route::delete('kit-products/color-attribute/delete/{id}', [KitProductController::class, 'deleteProductColorAttr'])->name('kit-products.color-attribute.destroy');

            //Ecommerce Products
            Route::resource('ecommerce-products', EcommerceProductController::class);
            Route::get('ecommerce-product-attributes/{id}', [EcomProductAttributeController::class, 'index'])->name('ecommerce-product-attributes.index');
            Route::get('ecommerce-product-attributes/create/{id}', [EcomProductAttributeController::class, 'create'])->name('ecommerce-product-attributes.create');
            Route::post('ecommerce-product-attributes/store/{id}', [EcomProductAttributeController::class, 'store'])->name('ecommerce-product-attributes.store');
            Route::get('ecommerce-product-attributes/edit/{id}', [EcomProductAttributeController::class, 'edit'])->name('ecommerce-product-attributes.edit');
            Route::put('ecommerce-product-attributes/update/{id}', [EcomProductAttributeController::class, 'update'])->name('ecommerce-product-attributes.update');
            Route::delete('ecommerce-product-attributes/delete/{id}', [EcomProductAttributeController::class, 'destroy'])->name('ecommerce-product-attributes.destroy');

            //Kit Product Bundles
            Route::resource('kit-product-bundles', KitProductBundleController::class);

            //Banner
            Route::resource('banners', BannerController::class);

            //FAQ
            Route::resource('faqs', FaqController::class);

            //Pages
            Route::resource('pages', PageController::class);

            //Taxes
            Route::resource('taxes', TaxController::class);

            //Taxes
            Route::resource('contact-messages', ContactMessageController::class);

            //Newsletters
            Route::resource('newsletters', NewsletterManagementController::class);

            //Order
            Route::resource('orders', DashboardOrderController::class);
            Route::get('orders/invoice/{id}', [DashboardOrderController::class, 'getInvoice'])->name('orders.invoice');
            Route::post('orders/status-update', [DashboardOrderController::class, 'updateOrderStatus'])->name('orders.status-update');

            //Email Template
            Route::resource('/email-templates', EmailTemplateController::class);

            //Promo Code Template
            Route::resource('/promo-codes', PromoCodeController::class);

        });
    });
});

// Frontend Pages Routes
Route::name('frontend.')->group(function () {
    Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
    Route::get('faq', [FrontendHomeController::class, 'faq'])->name('faq');
    Route::get('terms', [FrontendHomeController::class, 'terms'])->name('terms');
    Route::get('privacy', [FrontendHomeController::class, 'privacy'])->name('privacy');
    Route::get('return-policy', [FrontendHomeController::class, 'return'])->name('return');
    Route::get('delivery-info', [FrontendHomeController::class, 'deliveryInfo'])->name('delivery.info');
    Route::get('contact', [FrontendHomeController::class, 'contact'])->name('contact');
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


    Route::group(['middleware' => ['guest']], function () {
        Route::get('signin', [FrontendAuthController::class, 'index'])->name('signin');
        Route::post('user/login', [FrontendAuthController::class, 'login'])->name('login');
        Route::post('user/register', [FrontendAuthController::class, 'register'])->name('register');
        Route::post('user/forget-password', [FrontendAuthController::class, 'submitForgetPassword'])->name('forget.password.post');
        Route::get('reset-password/{token}', [FrontendAuthController::class, 'showResetPassword'])->name('reset.password');
        Route::post('reset-password', [FrontendAuthController::class, 'submitResetPassword'])->name('reset.password.post');
    });
    Route::post('contact', [DashboardController::class, 'contact'])->name('contact.post');
    //NewsLetter
    Route::post('newslettermail', [FrontendHomeController::class, 'sendMail'])->name('send.newslettermail');
    Route::group(['middleware' => ['auth']], function () {

        Route::get('user/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('order', [OrderController::class, 'index'])->name('order.view');
        Route::get('order-detail/{id}', [OrderController::class, 'orderDetail'])->name('order.detail');
        Route::post('accept-logo', [OrderController::class, 'acceptLogo'])->name('accept.logo');

        Route::get('user/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::post('user/profile/edit', [DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('upload-image', [DashboardController::class, 'uploadImage'])->name('upload.image');

        Route::post('/apply-promo', [CartController::class, 'applyPromo'])->name('apply.promo');
        Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('checkout', [CheckoutController::class, 'submit'])->name('checkout.store');

        Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('checkout/failed', [CheckoutController::class, 'failed'])->name('checkout.failed');
        Route::get('checkout/held', [CheckoutController::class, 'held'])->name('checkout.held');
    });

    Route::get('user/logout', [FrontendAuthController::class, 'logout'])->name('logout')->middleware(["auth:web"]);
});


//Artisan Routes
Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return "Stored linked successfully!";
})->name('clear.cache');
Route::middleware(['auth'])->group(function () {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        return "Application cache cleared!";
    })->name('clear.cache');

    Route::get('/clear-config', function () {
        Artisan::call('config:clear');
        return "Configuration cache cleared!";
    })->name('clear.config');

    Route::get('/clear-view', function () {
        Artisan::call('view:clear');
        return "View cache cleared!";
    })->name('clear.view');

    Route::get('/clear-route', function () {
        Artisan::call('route:clear');
        return "Route cache cleared!";
    })->name('clear.route');

    Route::get('/clear-optimize', function () {
        Artisan::call('optimize:clear');
        return "Optimization cache cleared!";
    })->name('clear.optimize');
});
