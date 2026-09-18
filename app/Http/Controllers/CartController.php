<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * عرض محتويات السلة
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // حساب الإجمالي
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('store.cart', compact('cart', 'total'));
    }

    /**
     * إضافة منتج للسلة
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'color'      => 'nullable|string',
            'size'       => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        $cart = session()->get('cart', []);

        // لتجنب تكرار نفس المنتج بنفس اللون والمقاس في السلة
        $cartKey = $product->id . '_' . $request->color . '_' . $request->size;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id'   => $product->id,
                'name'         => $product->name,
                'price'        => $product->price,
                'quantity'     => $request->quantity,
                'color'        => $request->color,
                'size'         => $request->size,
                'image'        => $product->primaryImage ? $product->primaryImage->image_path : ($product->images->first() ? $product->images->first()->image_path : 'photos/photo-2.png'),
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('home')->with('success', 'تمت إضافة المنتج إلى السلة بنجاح!');
    }

    /**
     * حذف منتج من السلة
     */
    public function remove(Request $request)
    {
        if($request->cart_key) {
            $cart = session()->get('cart');
            if(isset($cart[$request->cart_key])) {
                unset($cart[$request->cart_key]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'تم حذف المنتج من السلة');
        }
    }

    /**
     * صفحة إتمام الطلب
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors(['error' => 'السلة فارغة.']);
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $governorates = \App\Models\Governorate::orderBy('name')->get();

        return view('store.checkout', compact('cart', 'total', 'governorates'));
    }
}
