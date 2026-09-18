<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes - Retal Store (ريتال ستور)
|--------------------------------------------------------------------------
*/

// واجهات المتجر العامة (Storefront)
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/product/{id}', [StoreController::class, 'show'])->name('product.show');
Route::post('/order/submit', [StoreController::class, 'submitOrder'])->name('order.submit');
Route::post('/track/whatsapp', [StoreController::class, 'trackWhatsapp'])->name('track.whatsapp');

// السلة (Cart)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// تسجيل الدخول والتسجيل والخروج (Authentication)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// لوحة الإدارة ومتابعة الطلبات (محمية بتسجيل دخول الموظفين والمدير)
Route::middleware(['auth:employee'])->prefix('admin')->group(function () {
    // لوحة التحكم وإدارة المنتجات (خاصة بمدير المحل)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'manageProducts'])->name('admin.products.index');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/save', [AdminController::class, 'saveProduct'])->name('admin.products.save');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::delete('/products/images/{id}', [AdminController::class, 'deleteProductImage'])->name('admin.products.images.delete');

    // إدارة الأقسام والتصنيفات (خاصة بمدير المحل)
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.delete');

    // إدارة الموظفين (إضافة وحذف)
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    Route::delete('/employees/{id}', [AdminController::class, 'deleteEmployee'])->name('admin.employees.delete');

    // إدارة المستخدمين والعملاء (إضافة وحذف)
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // متابعة الطلبيات (للمدير والموظفين)
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
});

// حل بديل لعرض الصور على الاستضافات التي تمنع الـ Symlink
Route::get('/storage/{path}', function ($path) {
    // منع الوصول لملفات خارج مجلد public
    $path = str_replace(['..', "\0"], '', $path);
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath) || !is_file($filePath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($filePath);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->where('path', '.*');

// مسار بديل للصور يتجاوز مشكلة IIS مع فولدر storage
Route::get('/img/{path}', function ($path) {
    $path = str_replace(['..', "\0"], '', $path);
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath) || !is_file($filePath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($filePath);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->where('path', '.*');

// صفحة تشخيص مؤقتة لحل مشاكل الاستضافة (احذفها بعد حل المشكلة)
Route::get('/debug-hosting', function () {
    // مسح جميع الكاش
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');

    $info = [];
    $info['APP_URL'] = config('app.url');
    $info['APP_ENV'] = config('app.env');
    $info['APP_DEBUG'] = config('app.debug') ? 'true' : 'false';
    $info['base_path'] = base_path();
    $info['public_path'] = public_path();
    $info['storage_path'] = storage_path();
    $info['asset_test_css'] = asset('style.css');
    $info['asset_test_logo'] = asset('storage/logo.png');
    $info['logo_exists_in_storage'] = file_exists(storage_path('app/public/logo.png')) ? 'YES' : 'NO';
    $info['style_css_exists'] = file_exists(public_path('style.css')) ? 'YES' : 'NO';
    $info['public_storage_exists'] = file_exists(public_path('storage')) ? 'YES' : 'NO';
    $info['public_storage_is_link'] = is_link(public_path('storage')) ? 'YES (symlink)' : 'NO (regular dir)';
    $info['public_storage_is_dir'] = is_dir(public_path('storage')) ? 'YES' : 'NO';
    $info['web_config_exists'] = file_exists(public_path('web.config')) ? 'YES' : 'NO';
    $info['env_file_exists'] = file_exists(base_path('.env')) ? 'YES' : 'NO';
    $info['cache_cleared'] = 'YES - All caches cleared!';
    
    // فحص مجلد storage/products داخل public
    $pubStorageProducts = public_path('storage/products');
    $info['public_storage_products_exists'] = is_dir($pubStorageProducts) ? 'YES' : 'NO';
    
    if (is_dir($pubStorageProducts)) {
        $pubProductFiles = array_diff(scandir($pubStorageProducts), ['.', '..']);
        $info['public_storage_products_count'] = count($pubProductFiles);
        $info['public_storage_products_files'] = implode(', ', array_slice($pubProductFiles, 0, 5));
    }
    
    // فحص مجلد storage/app/public/products (المسار الحقيقي)
    $realProductsPath = storage_path('app/public/products');
    $info['real_products_path_exists'] = is_dir($realProductsPath) ? 'YES' : 'NO';
    
    if (is_dir($realProductsPath)) {
        $realProductFiles = array_diff(scandir($realProductsPath), ['.', '..']);
        $info['real_products_count'] = count($realProductFiles);
        $info['real_products_files'] = implode(', ', array_slice($realProductFiles, 0, 5));
    }
    
    // فحص صورة محددة من الداتابيز
    $testImages = \App\Models\ProductImage::latest()->take(3)->get();
    foreach ($testImages as $i => $img) {
        $info["db_image_{$i}_path"] = $img->image_path;
        $info["db_image_{$i}_product_id"] = $img->product_id;
        
        // فحص بـ asset
        $info["db_image_{$i}_asset_url"] = asset($img->image_path);
        
        // فحص وجود الملف عبر المسار الحقيقي
        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $img->image_path));
        $info["db_image_{$i}_real_path"] = $storagePath;
        $info["db_image_{$i}_file_exists"] = file_exists($storagePath) ? 'YES ✅' : 'NO ❌';
    }
    
    // قراءة محتوى مجلد public
    $publicFiles = scandir(public_path());
    $info['public_dir_contents'] = implode(', ', $publicFiles);
    
    // قراءة محتوى storage/app/public إن وجد
    $storagePath = storage_path('app/public');
    if (is_dir($storagePath)) {
        $storageFiles = scandir($storagePath);
        $info['storage_app_public_contents'] = implode(', ', $storageFiles);
    } else {
        $info['storage_app_public_contents'] = 'DIRECTORY NOT FOUND';
    }

    $html = '<html dir="rtl"><head><meta charset="utf-8"><title>Debug</title></head><body style="font-family:monospace;padding:20px;background:#1a1a2e;color:#eee;">';
    $html .= '<h1 style="color:#e94560;">🔍 تشخيص الاستضافة - Retal Store</h1>';
    $html .= '<p style="color:#0f3460;background:#e94560;padding:10px;border-radius:8px;color:white;">✅ تم مسح جميع ملفات الكاش بنجاح!</p>';
    $html .= '<table style="border-collapse:collapse;width:100%;">';
    foreach ($info as $key => $value) {
        $html .= '<tr style="border-bottom:1px solid #333;">';
        $html .= '<td style="padding:8px;color:#e94560;font-weight:bold;">' . $key . '</td>';
        $html .= '<td style="padding:8px;color:#eee;">' . $value . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table></body></html>';
    
    return $html;
});
