<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض قائمة الطلبيات
     */
    public function index()
    {
        $orders = Order::with('items')->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * إنشاء طلبية يدوية جديدة بواسطة الموظف أو الأدمن
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:50',
            'customer_email'   => 'nullable|string|max:255',
            'customer_address' => 'required|string|max:500',
            'customer_city'    => 'nullable|string|max:100',
            'product_type'     => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'color'            => 'nullable|string|max:50',
            'size'             => 'nullable|string|max:50',
        ]);

        return DB::transaction(function () use ($validated) {
            $lastOrder = Order::latest('id')->first();
            $nextNum = $lastOrder ? ($lastOrder->id + 1024) : 1024;
            $orderNumber = '#' . $nextNum;

            $order = Order::create([
                'order_number'    => $orderNumber,
                'customer_name'   => $validated['customer_name'],
                'customer_phone'  => $validated['customer_phone'],
                'customer_email'  => $validated['customer_email'] ?? null,
                'customer_address'=> $validated['customer_address'],
                'customer_city'   => $validated['customer_city'] ?? 'المحل',
                'total_amount'    => $validated['price'],
                'payment_method'  => 'cod',
                'status'          => 'preparing',
            ]);

            OrderItem::create([
                'order_id'     => $order->id,
                'product_name' => $validated['product_type'],
                'product_type' => $validated['product_type'],
                'color'        => $validated['color'] ?? null,
                'size'         => $validated['size'] ?? null,
                'price'        => $validated['price'],
                'quantity'     => 1,
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الطلبية بنجاح!',
                    'order'   => $order->load('items'),
                ]);
            }

            return back()->with('success', 'تم إنشاء الطلبية بنجاح!');
        });
    }

    /**
     * تحديث حالة الطلبية (قيد التجهيز / قيد التوصيل / تم التوصيل / فشل التوصيل)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $oldStatus = $order->status;

        if ($oldStatus === 'delivered') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'لا يمكن تغيير حالة طلب تم توصيله.']);
            }
            return back()->with('error', 'لا يمكن تغيير حالة طلب تم توصيله.');
        }

        $validated = $request->validate([
            'status'          => 'required|in:preparing,out_for_delivery,delivered,failed',
            'failure_reason'  => 'nullable|string|max:255',
            'failure_details' => 'nullable|string|max:500',
        ]);

        $newStatus = $validated['status'];
        $order->status = $newStatus;

        if ($newStatus === 'delivered') {
            $order->delivered_at = Carbon::now();
        } elseif ($newStatus === 'failed') {
            $order->failure_reason = $validated['failure_reason'] ?? null;
            $order->failure_details = $validated['failure_details'] ?? null;
        }

        if ($newStatus === 'failed' && $oldStatus !== 'failed') {
            foreach ($order->items as $item) {
                \App\Models\Product::where('id', $item->product_id)->increment('quantity', $item->quantity);
                $query = ProductVariant::where('product_id', $item->product_id);
                if (!empty($item->color)) $query->where('color_name', $item->color); else $query->whereNull('color_name');
                if (!empty($item->size)) $query->where('size', $item->size); else $query->whereNull('size');
                $query->increment('stock_quantity', $item->quantity);
            }
        } elseif ($oldStatus === 'failed' && $newStatus !== 'failed') {
            foreach ($order->items as $item) {
                \App\Models\Product::where('id', $item->product_id)->decrement('quantity', $item->quantity);
                $query = ProductVariant::where('product_id', $item->product_id);
                if (!empty($item->color)) $query->where('color_name', $item->color); else $query->whereNull('color_name');
                if (!empty($item->size)) $query->where('size', $item->size); else $query->whereNull('size');
                $query->decrement('stock_quantity', $item->quantity);
            }
        }

        $order->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الطلبية بنجاح!',
                'status'  => $order->status,
                'status_arabic' => $order->status_arabic,
                'delivered_time' => $order->status === 'failed' ? 'مرتجع' : ($order->delivered_at ? $order->delivered_at->format('Y-m-d h:i A') : 'لسه ماتسلمش'),
            ]);
        }

        return back()->with('success', 'تم تحديث الحالة بنجاح!');
    }

    /**
     * تعديل بيانات الطلبية
     */
    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $validated = $request->validate([
            'customer_name'    => 'nullable|string|max:255',
            'customer_phone'   => 'nullable|string|max:50',
            'customer_email'   => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:500',
            'total_amount'     => 'nullable|numeric|min:0',
            'color'            => 'nullable|string|max:50',
            'size'             => 'nullable|string|max:50',
            'product_type'     => 'nullable|string|max:255',
        ]);

        if (isset($validated['customer_name'])) $order->customer_name = $validated['customer_name'];
        if (isset($validated['customer_phone'])) $order->customer_phone = $validated['customer_phone'];
        if (isset($validated['customer_email'])) $order->customer_email = $validated['customer_email'];
        if (isset($validated['customer_address'])) $order->customer_address = $validated['customer_address'];
        if (isset($validated['total_amount'])) $order->total_amount = $validated['total_amount'];

        $order->save();

        // تعديل بيانات البند الأول
        if ($item = $order->items->first()) {
            if (isset($validated['color'])) $item->color = $validated['color'];
            if (isset($validated['size'])) $item->size = $validated['size'];
            if (isset($validated['product_type'])) $item->product_type = $validated['product_type'];
            if (isset($validated['total_amount'])) $item->price = $validated['total_amount'];
            $item->save();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تعديل بيانات الطلبية بنجاح!',
                'order'   => $order->fresh('items'),
            ]);
        }

        return back()->with('success', 'تم التعديل بنجاح!');
    }

    /**
     * حذف طلبية
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطلبية بنجاح!',
            ]);
        }

        return back()->with('success', 'تم حذف الطلبية بنجاح!');
    }
}
