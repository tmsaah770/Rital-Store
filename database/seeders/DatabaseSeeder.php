<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreAnalytic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. الموظفين والإدارة (employees)
        Employee::create([
            'name'     => 'مدير ريتال ستور',
            'email'    => '88902@gmail.com',
            'phone'    => '01000000001',
            'password' => Hash::make('88902'),
            'role'     => 'admin',
        ]);

        Employee::create([
            'name'     => 'موظف الطلبيات',
            'email'    => '12345@gmail.com',
            'phone'    => '01000000002',
            'password' => Hash::make('12345'),
            'role'     => 'employee',
        ]);

        // 2. المستخدمين والعملاء (users)
        User::create([
            'name'     => 'عميل ريتال التجريبي',
            'email'    => 'customer@gmail.com',
            'phone'    => '01011223344',
            'password' => Hash::make('pass12345'),
        ]);

        // 3. الأقسام والتصنيفات (categories)
        $catEvening = Category::firstOrCreate(['name' => 'فساتين سهرة']);
        $catCasual  = Category::firstOrCreate(['name' => 'دريسات كاجوال']);
        $catAbaya   = Category::firstOrCreate(['name' => 'عبايات']);

        // 4. منتج تجريبي متطابق مع التصميم الحالي
        $product = Product::create([
            'category_id' => $catEvening->id,
            'name' => 'فستان سهرة أنيق',
            'slug' => 'evening-dress-elegant',
            'type' => 'فستان سهرة',
            'price' => 450.00,
            'whatsapp_link' => 'https://wa.me/2001550112320',
            'is_active' => true,
        ]);

        // صور المنتج
        $photos = [
            'photos/photo-2.png',
            'photos/photo-3.png',
            'photos/photo-4.png',
            'photos/photo-5.png',
            'photos/photo-6.png',
            'photos/photo-7.png',
        ];

        foreach ($photos as $index => $photo) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $photo,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }

        // ألوان ومقاسات ومخزون
        $colors = [
            ['name' => 'أسود', 'code' => '#2b2b2b'],
            ['name' => 'رمادي', 'code' => '#8a7d74'],
            ['name' => 'بيج', 'code' => '#e8dcc8'],
            ['name' => 'كحلي', 'code' => '#2c3e50'],
            ['name' => 'بني', 'code' => '#a67c52'],
            ['name' => 'ابيض', 'code' => '#ffffff'],
        ];

        $sizes = ['S', 'M', 'L', 'XL', '2XL'];

        foreach ($colors as $c) {
            foreach ($sizes as $s) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_name' => $c['name'],
                    'color_code' => $c['code'],
                    'size' => $s,
                    'stock_quantity' => 10,
                ]);
            }
        }

        // إضافة 7 منتجات أخرى للعرض بالصفحة الرئيسية
        for ($i = 2; $i <= 8; $i++) {
            $extraProduct = Product::create([
                'name' => 'اسم الفستان موديل ' . $i,
                'slug' => 'dress-model-' . $i,
                'type' => 'فستان',
                'price' => 450.00,
                'whatsapp_link' => 'https://wa.me/2001550112320',
                'is_active' => true,
            ]);

            ProductImage::create([
                'product_id' => $extraProduct->id,
                'image_path' => 'photos/photo-2.png',
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            ProductVariant::create([
                'product_id' => $extraProduct->id,
                'color_name' => 'أسود',
                'color_code' => '#2b2b2b',
                'size' => 'M',
                'stock_quantity' => 5,
            ]);
        }

        // 3. طلبات مبدئية متطابقة مع شاشة الموظفين
        $order1 = Order::create([
            'order_number' => '#1024',
            'customer_name' => 'سارة أحمد',
            'customer_phone' => '01012345678',
            'customer_email' => null,
            'customer_address' => 'القاهرة - مدينة نصر - شارع مصطفى النحاس',
            'customer_city' => 'القاهرة',
            'total_amount' => 450.00,
            'payment_method' => 'cod',
            'status' => 'preparing',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
            'product_name' => 'فستان سهرة',
            'product_type' => 'فستان سهرة',
            'color' => 'أسود',
            'size' => 'M',
            'price' => 450.00,
            'quantity' => 1,
        ]);

        $order2 = Order::create([
            'order_number' => '#1023',
            'customer_name' => 'مريم علي',
            'customer_phone' => '01098765432',
            'customer_email' => 'mariam@example.com',
            'customer_address' => 'الجيزة - الدقي',
            'customer_city' => 'الجيزة',
            'total_amount' => 250.00,
            'payment_method' => 'cod',
            'status' => 'delivered',
            'delivered_at' => Carbon::now()->subDay(),
            'created_at' => Carbon::now()->subDays(2),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product->id,
            'product_name' => 'سوتيان',
            'product_type' => 'سوتيان',
            'color' => 'بيج',
            'size' => 'L',
            'price' => 250.00,
            'quantity' => 1,
        ]);

        // 4. إحصائيات المتجر
        StoreAnalytic::create([
            'date' => Carbon::today(),
            'visitors_count' => 100,
            'whatsapp_clicks' => 100,
            'product_clicks' => 100,
            'sales_count' => 100,
            'returns_count' => 100,
        ]);
    }
}
