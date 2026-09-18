<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\StoreAnalytic;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للمتجر
     */
    public function index(Request $request)
    {
        // تسجيل زيارة المتجر لليوم
        $this->recordAnalytics('visitors_count');

        $categories = Category::all();

        if ($request->filled('search') || $request->filled('category')) {
            $query = Product::where('is_active', true)->with(['images', 'variants']);

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            $products = $query->latest()->get();

            return view('store.index', compact('products', 'categories'));
        }

        // Home page sections
        $latestProducts = Product::where('is_active', true)->with(['images', 'variants'])->latest()->take(10)->get();
        $discountedProducts = Product::where('is_active', true)->where('discount', '>', 0)->with(['images', 'variants'])->orderByDesc('discount')->take(10)->get();
        $randomProducts = Product::where('is_active', true)->with(['images', 'variants'])->inRandomOrder()->take(20)->get();

        return view('store.index', compact('latestProducts', 'discountedProducts', 'randomProducts', 'categories'));
    }

    /**
     * عرض تفاصيل منتج معين
     */
    public function show($id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);

        // تسجيل نقرة على المنتج
        $this->recordAnalytics('product_clicks');

        return view('store.product', compact('product'));
    }

    /**
     * استقبال وتأكيد الطلب (الدفع عند الاستلام)
     */
    public function submitOrder(Request $request)
    {
        $cart = session()->get('cart', []);

        if(empty($cart)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'السلة فارغة.']);
            }
            return back()->withErrors(['error' => 'السلة فارغة.']);
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|max:50',
            'address'    => 'required|string|max:500',
            'city'       => 'required|exists:governorates,name',
        ]);

        return DB::transaction(function () use ($validated, $cart) {
            $lastOrder = Order::latest('id')->first();
            $nextNum = $lastOrder ? ($lastOrder->id + 1024) : 1024;
            $orderNumber = '#' . $nextNum;

            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
            
            $governorate = \App\Models\Governorate::where('name', $validated['city'])->first();
            $shippingCost = $governorate ? $governorate->shipping_cost : 0;
            $totalAmount = $cartTotal + $shippingCost;

            $order = Order::create([
                'order_number'    => $orderNumber,
                'customer_name'   => $validated['name'],
                'customer_phone'  => $validated['phone'],
                'customer_email'  => null,
                'customer_address'=> $validated['address'],
                'customer_city'   => $validated['city'],
                'shipping_cost'   => $shippingCost,
                'total_amount'    => $totalAmount,
                'payment_method'  => 'cod',
                'status'          => 'preparing',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_type' => null, // or fetch it if needed
                    'color'        => $item['color'] ?? null,
                    'size'         => $item['size'] ?? null,
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                ]);

                // خصم الكمية من المنتج الرئيسي
                \App\Models\Product::where('id', $item['product_id'])->decrement('quantity', $item['quantity']);

                // خصم الكمية من المتغير الخاص بالمنتج
                $query = \App\Models\ProductVariant::where('product_id', $item['product_id']);
                if (!empty($item['color'])) {
                    $query->where('color_name', $item['color']);
                } else {
                    $query->whereNull('color_name');
                }
                if (!empty($item['size'])) {
                    $query->where('size', $item['size']);
                } else {
                    $query->whereNull('size');
                }
                $query->decrement('stock_quantity', $item['quantity']);
            }

            $this->recordAnalytics('sales_count');

            // إفراغ السلة بعد إتمام الطلب
            session()->forget('cart');

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success'      => true,
                    'order_number' => $order->order_number,
                    'message'      => 'تم تأكيد طلبك بنجاح!',
                ]);
            }

            return redirect()->route('home')->with('success_order', $order->order_number);
        });
    }

    /**
     * تسجيل نقرة واتساب
     */
    public function trackWhatsapp()
    {
        $this->recordAnalytics('whatsapp_clicks');
        return response()->json(['success' => true]);
    }

    /**
     * دالة مساعدة لتسجيل إحصائيات اليوم
     */
    private function recordAnalytics(string $column)
    {
        try {
            $today = Carbon::today()->toDateString();
            $stat = StoreAnalytic::firstOrCreate(['date' => $today]);
            $stat->increment($column);
        } catch (\Exception $e) {
            // صامت حتى لا يؤثر على تجربة المستخدم في حال حدوث أي استثناء في التتبع
        }
    }
}
