@extends('layouts.app')

@section('title', 'سلة المشتريات - ريتال ستور')

@section('content')
    <div style="background-color: var(--card-bg); border-radius: 14px; box-shadow: var(--card-shadow); max-width: 1100px; margin: 40px auto 40px auto; position: relative; padding: 40px 20px; min-height: 60vh; text-align: center; display: block;">
        <a href="{{ route('home') }}" style="position: absolute; top: 15px; right: 20px; font-size: 30px; color: var(--text-secondary); text-decoration: none; font-weight: bold; line-height: 1; transition: color 0.3s;" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-secondary)'">×</a>

        <h1 class="dashboard-title" style="font-size: 28px; font-weight: bold; margin-bottom: 30px; text-align: center;">سلة المشتريات</h1>
        
        @if(session('success'))
            <p style="text-align: center; color: var(--success); background: var(--success-bg); padding: 10px; border-radius: 8px; font-weight: bold; max-width: 500px; margin: 0 auto 20px;">{{ session('success') }}</p>
        @endif

        @if(count($cart) > 0)
            <div style="width: 100%; max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 15px;">
                @foreach($cart as $key => $item)
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; border: 1px solid var(--card-border); background: #fff; padding: 15px; border-radius: 12px; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <div style="display: flex; gap: 15px; align-items: center;">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--card-border);">
                            <div>
                                <h3 style="margin: 0; color: var(--text-primary); font-size: 16px; font-weight: bold;">{{ $item['name'] }}</h3>
                                <p style="margin: 6px 0 0; color: var(--text-secondary); font-size: 13px;">
                                    اللون: {{ $item['color'] ?? '-' }} | المقاس: {{ $item['size'] ?? '-' }}
                                </p>
                                <p style="margin: 6px 0 0; color: var(--primary); font-weight: bold; font-size: 15px;">
                                    {{ number_format($item['price'], 0) }} جنيه × {{ $item['quantity'] }}
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('cart.remove') }}" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <button type="submit" style="background: var(--danger-bg); border: none; color: var(--danger); width: 35px; height: 35px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">×</button>
                        </form>
                    </div>
                @endforeach

                <div style="border-top: 2px solid var(--card-border); padding-top: 20px; margin-top: 15px;">
                    <div class="summary-line" style="display: flex; justify-content: space-between; font-size: 22px; font-weight: bold; color: var(--text-primary);">
                        <span>الإجمالي</span>
                        <span style="color: var(--primary);">{{ number_format($total, 0) }} جنيه</span>
                    </div>
                </div>

                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--card-border);">
                    <a href="{{ route('cart.checkout') }}" class="order-btn" style="width: 100%; display: block; font-size: 18px; padding: 15px 0; border-radius: 8px;">إتمام الشراء</a>
                </div>
            </div>
        @else
            <div style="text-align: center; margin: 60px 0;">
                <p style="font-size: 22px; color: var(--text-secondary); font-weight: bold;">السلة فارغة حالياً</p>
                <a href="{{ route('home') }}" class="order-btn" style="display: inline-block; margin-top: 20px; width: auto; padding: 10px 40px;">تصفح المنتجات</a>
            </div>
        @endif
    </div>

@endsection
