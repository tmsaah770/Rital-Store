<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StoreAnalytic;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية وإحصائيات المتجر وجدول الموظفين
     */
    public function dashboard()
    {
        // جمع الإحصائيات (الإجمالي واليومي)
        $today = Carbon::today()->toDateString();
        $todayStats = StoreAnalytic::where('date', $today)->first();

        $totalVisitors = StoreAnalytic::sum('visitors_count');
        $whatsappClicks = StoreAnalytic::sum('whatsapp_clicks');
        $productClicks = StoreAnalytic::sum('product_clicks');
        $totalSales = Order::where('status', 'delivered')->count();
        $totalReturns = Order::where('status', 'failed')->count();

        // قائمة الموظفين
        $employees = Employee::where('role', 'employee')->latest()->get();

        // قائمة المستخدمين والعملاء
        $users = User::latest()->get();

        return view('admin.dashboard', compact(
            'totalVisitors',
            'whatsappClicks',
            'productClicks',
            'totalSales',
            'totalReturns',
            'employees',
            'users'
        ));
    }

    /**
     * عرض صفحة إدارة المنتجات مع دعم البحث بالاسم أو كود المنتج
     */
    public function manageProducts(Request $request)
    {
        $query = Product::with(['images', 'variants', 'category'])->latest();

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $termClean = ltrim($term, '#');
            $query->where(function ($q) use ($term, $termClean) {
                $q->where('name', 'like', "%{$term}%");
                if (is_numeric($termClean)) {
                    $q->orWhere('id', $termClean);
                }
            });
        }

        $products = $query->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * عرض صفحة إنشاء منتج جديد
     */
    public function createProduct(Request $request)
    {
        if ($request->filled('edit')) {
            return redirect()->route('admin.products.edit', $request->input('edit'));
        }

        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * عرض صفحة مستقلة لتعديل منتج موجود
     */
    public function editProduct($id)
    {
        $product = Product::with(['images', 'variants', 'category'])->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * حفظ أو تعديل منتج من لوحة التحكم
     */
    public function saveProduct(Request $request)
    {
        $validated = $request->validate([
            'id'            => 'nullable|exists:products,id',
            'category_id'   => 'nullable|exists:categories,id',
            'name'          => 'required|string|max:255',
            'type'          => 'nullable|string|max:100',
            'price'         => 'required|numeric|min:0',
            'quantity'      => 'nullable|integer|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'whatsapp_link' => 'nullable|url|max:255',
            'colors'        => 'nullable|string', // مفصولة بفاصلة
            'sizes'         => 'nullable|string',  // مفصولة بفاصلة
            'stock_matrix'  => 'nullable|array',   // مصفوفة الكميات حسب اللون والمقاس
            'photos.*'      => 'nullable|image|max:10240', // صور حتى 10 ميجا
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $categoryName = null;
            if (!empty($validated['category_id'])) {
                $cat = Category::find($validated['category_id']);
                $categoryName = $cat ? $cat->name : null;
            }

            $productType = !empty($validated['type']) ? $validated['type'] : ($categoryName ?: 'فستان');

            $product = Product::updateOrCreate(
                ['id' => $validated['id'] ?? null],
                [
                    'category_id'   => $validated['category_id'] ?? null,
                    'name'          => $validated['name'],
                    'slug'          => Str::slug($validated['name']) . '-' . time(),
                    'type'          => $productType,
                    'price'         => $validated['price'],
                    'quantity'      => $validated['quantity'] ?? 0,
                    'discount'      => $validated['discount'] ?? 0,
                    'whatsapp_link' => $validated['whatsapp_link'] ?? null,
                    'is_active'     => true,
                ]
            );

            // رفع الصور الجديدة إن وُجدت
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photoFile) {
                    $path = $photoFile->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'storage/' . $path,
                        'is_primary' => ($index === 0 && $product->images()->count() === 0),
                        'sort_order' => $product->images()->count() + $index,
                    ]);
                }
            }

            // تحديث الألوان والمقاسات والمخزون
            $colors = !empty($validated['colors']) ? array_filter(array_map('trim', explode(',', $validated['colors']))) : [];
            $sizes = !empty($validated['sizes']) ? array_filter(array_map('trim', explode(',', $validated['sizes']))) : [];

            $knownColorCodes = [
                'أسود'  => '#2b2b2b',
                'رمادي' => '#8a7d74',
                'بيج'   => '#e8dcc8',
                'كحلي'  => '#2c3e50',
                'بني'   => '#a67c52',
                'ابيض'  => '#ffffff',
                'أبيض'  => '#ffffff',
            ];

            // حذف المتغيرات السابقة لإعادة مزامنة الألوان والمقاسات المختارة
            $product->variants()->delete();

            if (!empty($colors) && !empty($sizes)) {
                foreach ($colors as $color) {
                    $colorCode = str_starts_with($color, '#') ? $color : ($knownColorCodes[$color] ?? null);
                    foreach ($sizes as $size) {
                        ProductVariant::create([
                            'product_id'     => $product->id,
                            'color_name'     => $color,
                            'color_code'     => $colorCode,
                            'size'           => $size,
                            'stock_quantity' => 10,
                        ]);
                    }
                }
            } elseif (!empty($colors)) {
                foreach ($colors as $color) {
                    $colorCode = str_starts_with($color, '#') ? $color : ($knownColorCodes[$color] ?? null);
                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'color_name'     => $color,
                        'color_code'     => $colorCode,
                        'size'           => null,
                        'stock_quantity' => 10,
                    ]);
                }
            } elseif (!empty($sizes)) {
                foreach ($sizes as $size) {
                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'color_name'     => null,
                        'color_code'     => null,
                        'size'           => $size,
                        'stock_quantity' => 10,
                    ]);
                }
            } else {
                ProductVariant::create([
                    'product_id'     => $product->id,
                    'color_name'     => null,
                    'color_code'     => null,
                    'size'           => null,
                    'stock_quantity' => 10,
                ]);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حفظ المنتج بنجاح!',
                    'product' => $product->load(['images', 'variants']),
                ]);
            }

            return back()->with('success', 'تم حفظ المنتج بنجاح!');
        });
    }

    /**
     * حذف منتج
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج بنجاح!',
            ]);
        }

        return back()->with('success', 'تم حذف المنتج بنجاح!');
    }

    /**
     * حذف صورة فردية من صور المنتج
     */
    public function deleteProductImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $image->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح!',
            ]);
        }

        return back()->with('success', 'تم حذف الصورة بنجاح!');
    }

    /**
     * إضافة موظف جديد
     */
    public function storeEmployee(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim((string)$request->input('email'))),
            'phone' => preg_replace('/[^0-9]/', '', (string)$request->input('phone')),
            'name'  => strip_tags(trim((string)$request->input('name'))),
        ]);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'email'    => ['required', 'string', 'email:filter', 'max:255', 'unique:employees,email'],
            'phone'    => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/', 'unique:employees,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'        => 'من فضلك أدخل اسم الموظف.',
            'name.min'             => 'اسم الموظف يجب ألا يقل عن 3 أحرف.',
            'email.required'       => 'من فضلك أدخل البريد الإلكتروني للموظف.',
            'email.email'          => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'         => 'هذا البريد الإلكتروني مسجل مسبقاً لموظف آخر.',
            'phone.required'       => 'من فضلك أدخل رقم الموبايل.',
            'phone.regex'          => 'رقم الموبايل يجب أن يكون 11 رقماً مصرياً صحيحاً (010, 011, 012, 015).',
            'phone.unique'         => 'رقم الموبايل هذا مسجل مسبقاً لموظف آخر.',
            'password.required'    => 'من فضلك أدخل كلمة المرور للموظف.',
            'password.min'         => 'كلمة المرور يجب ألا تقل عن 6 خانات.',
            'password.confirmed'   => 'كلمة المرور وتأكيد كلمة المرور غير متطابقين.',
        ]);

        $employee = Employee::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => 'employee',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'تمت إضافة الموظف بنجاح!',
                'employee' => $employee,
            ]);
        }

        return back()->with('success', 'تمت إضافة الموظف بنجاح!');
    }

    /**
     * حذف موظف
     */
    public function deleteEmployee($id)
    {
        $employee = Employee::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الموظف بنجاح!',
            ]);
        }

        return back()->with('success', 'تم حذف الموظف بنجاح!');
    }

    /**
     * إضافة مستخدم جديد من لوحة التحكم
     */
    public function storeUser(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim((string)$request->input('email'))),
            'phone' => preg_replace('/[^0-9]/', '', (string)$request->input('phone')),
            'name'  => strip_tags(trim((string)$request->input('name'))),
        ]);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'email'    => ['required', 'string', 'email:filter', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'        => 'من فضلك أدخل اسم المستخدم.',
            'name.min'             => 'اسم المستخدم يجب ألا يقل عن 3 أحرف.',
            'email.required'       => 'من فضلك أدخل البريد الإلكتروني للمستخدم.',
            'email.email'          => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'         => 'هذا البريد الإلكتروني مسجل مسبقاً لمستخدم آخر.',
            'phone.required'       => 'من فضلك أدخل رقم الموبايل.',
            'phone.regex'          => 'رقم الموبايل يجب أن يكون 11 رقماً مصرياً صحيحاً (010, 011, 012, 015).',
            'phone.unique'         => 'رقم الموبايل هذا مسجل مسبقاً لمستخدم آخر.',
            'password.required'    => 'من فضلك أدخل كلمة المرور.',
            'password.min'         => 'كلمة المرور يجب ألا تقل عن 6 خانات.',
            'password.confirmed'   => 'كلمة المرور وتأكيد كلمة المرور غير متطابقين.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة المستخدم بنجاح!',
                'user'    => $user,
            ]);
        }

        return back()->with('success', 'تمت إضافة المستخدم بنجاح!');
    }

    /**
     * حذف مستخدم
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخدم بنجاح!',
            ]);
        }

        return back()->with('success', 'تم حذف المستخدم بنجاح!');
    }
}
