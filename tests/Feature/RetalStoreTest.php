<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

class RetalStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_loads_successfully_with_products(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ريتال ستور');
    }

    public function test_product_page_loads_successfully(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->get('/product/' . $product->id);
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_order_submission_creates_order(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->postJson('/order/submit', [
            'product_id' => $product->id,
            'name'       => 'منى محمود',
            'phone'      => '01234567890',
            'address'    => 'ش الهرم - الجيزة',
            'city'       => 'الجيزة',
            'color'      => 'أسود',
            'size'       => 'L',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'منى محمود',
            'customer_phone' => '01234567890',
        ]);
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('تسجيل دخول الإدارة');
    }

    public function test_employee_can_login_via_credentials(): void
    {
        $response = $this->postJson('/login', [
            'email'    => '88902@gmail.com',
            'password' => '88902',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'  => true,
            'redirect' => route('admin.dashboard'),
        ]);

        $this->assertAuthenticated('employee');
    }

    public function test_user_can_login_via_credentials(): void
    {
        $response = $this->postJson('/login', [
            'email'    => 'customer@gmail.com',
            'password' => 'pass12345',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'  => true,
            'redirect' => route('home'),
        ]);

        $this->assertAuthenticated('web');
    }

    public function test_admin_can_access_dashboard_and_orders(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin, 'employee')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('لوحة التحكم');

        $ordersResponse = $this->actingAs($admin, 'employee')->get('/admin/orders');
        $ordersResponse->assertStatus(200);
        $ordersResponse->assertSee('أوردرات ريتال ستور');

        $createProductResponse = $this->actingAs($admin, 'employee')->get('/admin/products/create');
        $createProductResponse->assertStatus(200);
        $createProductResponse->assertSee('إضافة منتج جديد');

        $productsResponse = $this->actingAs($admin, 'employee')->get('/admin/products');
        $productsResponse->assertStatus(200);
        $productsResponse->assertSee('إدارة المنتجات');
    }

    public function test_register_page_loads_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('إنشاء حساب جديد');
    }

    public function test_registration_validation_blocks_invalid_inputs(): void
    {
        // فحص هاتف غير مصري وباسوورد غير متطابقة
        $response = $this->postJson('/register', [
            'name'                  => 'أحمد',
            'email'                 => 'invalid-email',
            'phone'                 => '12345678', // ليس رقما مصريا
            'password'              => '123',      // اقل من 6 خانات
            'password_confirmation' => '456',      // غير متطابق
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'phone', 'password']);
    }

    public function test_registration_blocks_fake_email_domain(): void
    {
        $response = $this->postJson('/register', [
            'name'                  => 'عميل تجريبي',
            'email'                 => 'fake@thisdomaindoesnotexistatall12345.xyz',
            'phone'                 => '01011223344',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_registration_blocks_duplicate_email(): void
    {
        // مستخدم موجود مسبقاً في جدول users
        User::create([
            'name'     => 'مستخدم موجود',
            'email'    => 'existing@gmail.com',
            'phone'    => '01011112222',
            'password' => bcrypt('password123'),
        ]);

        // محاولة التسجيل بنفس الإيميل (حتى بحروف كبيرة/صغيرة)
        $response = $this->postJson('/register', [
            'name'                  => 'شخص آخر',
            'email'                 => 'EXISTING@GMAIL.COM',
            'phone'                 => '01033334444',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_registration_succeeds_with_valid_data(): void
    {
        $response = $this->postJson('/register', [
            'name'                  => 'ياسمين محمد',
            'email'                 => 'yasmeen.retal@gmail.com',
            'phone'                 => '01099887766',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'name'  => 'ياسمين محمد',
            'email' => 'yasmeen.retal@gmail.com',
            'phone' => '01099887766',
        ]);

        $this->assertAuthenticated('web');
    }

    public function test_admin_can_add_employee_successfully(): void
    {
        $admin = Employee::where('role', 'admin')->first();

        $response = $this->actingAs($admin, 'employee')->postJson('/admin/employees', [
            'name'                  => 'أحمد محمود كاشير',
            'email'                 => 'ahmed.cashier@gmail.com',
            'phone'                 => '01011223344',
            'password'              => 'pass12345',
            'password_confirmation' => 'pass12345',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('employees', [
            'name'  => 'أحمد محمود كاشير',
            'email' => 'ahmed.cashier@gmail.com',
            'phone' => '01011223344',
            'role'  => 'employee',
        ]);
    }

    public function test_admin_cannot_add_employee_with_mismatched_password(): void
    {
        $admin = Employee::where('role', 'admin')->first();

        $response = $this->actingAs($admin, 'employee')->postJson('/admin/employees', [
            'name'                  => 'أحمد محمود كاشير',
            'email'                 => 'ahmed.cashier@gmail.com',
            'phone'                 => '01011223344',
            'password'              => 'pass12345',
            'password_confirmation' => 'mismatch6789',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_admin_cannot_add_employee_with_invalid_phone(): void
    {
        $admin = Employee::where('role', 'admin')->first();

        $response = $this->actingAs($admin, 'employee')->postJson('/admin/employees', [
            'name'                  => 'موظف تجريبي',
            'email'                 => 'emp.invalid@gmail.com',
            'phone'                 => '12345', // invalid Egyptian phone
            'password'              => 'pass12345',
            'password_confirmation' => 'pass12345',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    public function test_admin_can_delete_employee(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $employee = Employee::where('role', 'employee')->first();
        $this->assertNotNull($employee);

        $response = $this->actingAs($admin, 'employee')->deleteJson('/admin/employees/' . $employee->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_admin_can_add_user_successfully(): void
    {
        $admin = Employee::where('role', 'admin')->first();

        $response = $this->actingAs($admin, 'employee')->postJson('/admin/users', [
            'name'                  => 'عميل جديد من الداش',
            'email'                 => 'dashuser@gmail.com',
            'phone'                 => '01099887766',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'dashuser@gmail.com',
            'phone' => '01099887766',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $user = User::create([
            'name'     => 'مستخدم للحذف',
            'email'    => 'deluser@gmail.com',
            'phone'    => '01099112233',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'employee')->deleteJson('/admin/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_market_displays_dashboard_button_for_admin(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $response = $this->actingAs($admin, 'employee')->get('/');
        $response->assertStatus(200);
        $response->assertSee('لوحة التحكم');
        $response->assertDontSee('إدارة الطلبات');
    }

    public function test_market_displays_orders_button_for_employee(): void
    {
        $employee = Employee::where('role', 'employee')->first();
        $response = $this->actingAs($employee, 'employee')->get('/');
        $response->assertStatus(200);
        $response->assertSee('إدارة الطلبات');
        $response->assertDontSee('لوحة التحكم');
    }

    public function test_market_does_not_display_admin_buttons_for_user_or_guest(): void
    {
        // 1. كزائر غير مسجل
        $guestResponse = $this->get('/');
        $guestResponse->assertStatus(200);
        $guestResponse->assertDontSee('لوحة التحكم');
        $guestResponse->assertDontSee('إدارة الطلبات');

        // 2. كمستخدم عادي مسجل
        $user = User::first();
        $userResponse = $this->actingAs($user, 'web')->get('/');
        $userResponse->assertStatus(200);
        $userResponse->assertDontSee('لوحة التحكم');
        $userResponse->assertDontSee('إدارة الطلبات');
    }

    public function test_admin_can_access_categories_page(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $response = $this->actingAs($admin, 'employee')->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('إدارة الأقسام');
    }

    public function test_admin_can_create_category(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $response = $this->actingAs($admin, 'employee')->postJson('/admin/categories', [
            'name' => 'فساتين زفاف',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('categories', [
            'name' => 'فساتين زفاف',
        ]);
    }

    public function test_admin_cannot_create_duplicate_category_name(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        Category::create(['name' => 'عبايات خليجية']);

        $response = $this->actingAs($admin, 'employee')->postJson('/admin/categories', [
            'name' => 'عبايات خليجية',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_admin_can_update_category(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $cat = Category::create(['name' => 'دريسات قديمة']);

        $response = $this->actingAs($admin, 'employee')->putJson('/admin/categories/' . $cat->id, [
            'name' => 'دريسات صيفية جديدة',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('categories', [
            'id'   => $cat->id,
            'name' => 'دريسات صيفية جديدة',
        ]);
    }

    public function test_admin_can_delete_category_and_cascades_to_products(): void
    {
        $admin = Employee::where('role', 'admin')->first();
        $cat = Category::create(['name' => 'قسم للتجربة']);

        $product = Product::create([
            'category_id'   => $cat->id,
            'name'          => 'منتج تابع للقسم التجريبي',
            'slug'          => 'test-cat-product',
            'price'         => 350,
            'whatsapp_link' => 'https://wa.me/2001550112320',
            'is_active'     => true,
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'category_id' => $cat->id]);

        $response = $this->actingAs($admin, 'employee')->deleteJson('/admin/categories/' . $cat->id);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // تأكيد حذف القسم
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);

        // تأكيد حذف المنتج التابع له تلقائياً (Cascade Delete)
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}


