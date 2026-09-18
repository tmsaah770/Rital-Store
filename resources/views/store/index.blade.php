@extends('layouts.app')

@section('title', 'retal store')

@section('content')
    <section class="header">
        <div class="header-top">
            <!-- Right side (Login/Cart) -->
            <div class="header-actions">
                @if(Auth::guard('employee')->check() || Auth::guard('web')->check())
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="login-link logout-link">تسجيل خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="login-link">تسجيل دخول</a>
                @endif
                <a href="{{ route('cart.index') }}" class="login-link admin-nav-link" style="margin-top: 5px;">
                    🛒 السلة
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span style="background: var(--primary); color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-right: 5px;">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <!-- Center (Logo) -->
            <div class="header-logo">
                <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
                <h1 class="brand-name">ريتال ستور</h1>
            </div>

            <!-- Left side (Admin) -->
            <div class="header-actions admin-actions">
                @if(Auth::guard('employee')->check())
                    @if(Auth::guard('employee')->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="login-link admin-nav-link" id="nav-admin-dashboard-btn">📦 لوحة التحكم</a>
                    @elseif(Auth::guard('employee')->user()->isEmployee())
                        <a href="{{ route('admin.orders') }}" class="login-link admin-nav-link" id="nav-employee-orders-btn">📦 متابعة الطلبيات</a>
                        <a href="{{ route('admin.categories.index') }}" class="login-link admin-nav-link">🏷️ إدارة الأقسام</a>
                        <a href="{{ route('admin.products.index') }}" class="login-link admin-nav-link">👗 إدارة المنتجات</a>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <section class="search-bar-section" style="text-align: center; margin: 20px 0;">
        <form action="{{ route('home') }}" method="GET" style="display: flex; justify-content: center; align-items: center; gap: 10px; max-width: 500px; margin: 0 auto; padding: 0 15px;">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" style="flex: 1; padding: 12px 15px; border-radius: 25px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-size: 16px; outline: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05);" aria-label="Search">
            <button type="submit" style="padding: 12px 25px; border-radius: 25px; border: none; background-color: #d15c72; color: white; cursor: pointer; font-family: 'Cairo', sans-serif; font-size: 16px; font-weight: bold; transition: background-color 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">بحث</button>
        </form>

        <div class="category-filters" style="display: flex; justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap; padding: 0 15px;">
            <a href="{{ route('home', ['search' => request('search')]) }}" 
               style="padding: 6px 22px; border-radius: 20px; text-decoration: none; font-family: 'Cairo', sans-serif; font-size: 15px; font-weight: bold; border: 1px solid #8e2b3c; transition: all 0.3s; {{ !request('category') ? 'background-color: #8e2b3c; color: white; box-shadow: 0 3px 8px rgba(142,43,60,0.3);' : 'background-color: white; color: #8e2b3c;' }}">
                الرئيسية
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->id, 'search' => request('search')]) }}" 
                   style="padding: 6px 22px; border-radius: 20px; text-decoration: none; font-family: 'Cairo', sans-serif; font-size: 15px; font-weight: bold; border: 1px solid #8e2b3c; transition: all 0.3s; {{ request('category') == $category->id ? 'background-color: #8e2b3c; color: white; box-shadow: 0 3px 8px rgba(142,43,60,0.3);' : 'background-color: white; color: #8e2b3c;' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    @if(isset($products))
        <section class="photos-items">
            @forelse($products as $product)
                @include('store.partials.product_card', ['product' => $product])
            @empty
                <p style="text-align: center; width: 100%; font-family: 'Cairo', sans-serif; color: #666;">لا توجد منتجات متاحة حالياً.</p>
            @endforelse
        </section>
    @else
        @if($latestProducts->count() > 0)
            <div style="text-align: center; margin: 30px 0 15px;">
                <h2 style="font-family: 'Cairo', sans-serif; color: #7c2438;">أضيفت حديثاً</h2>
            </div>
            <section class="photos-items">
                @foreach($latestProducts as $product)
                    @include('store.partials.product_card', ['product' => $product])
                @endforeach
            </section>
        @endif

        @if($discountedProducts->count() > 0)
            <div style="text-align: center; margin: 30px 0 15px;">
                <h2 style="font-family: 'Cairo', sans-serif; color: #7c2438;">العروض</h2>
            </div>
            <section class="photos-items">
                @foreach($discountedProducts as $product)
                    @include('store.partials.product_card', ['product' => $product])
                @endforeach
            </section>
        @endif

        @if($randomProducts->count() > 0)
            <div style="text-align: center; margin: 30px 0 15px;">
                <h2 style="font-family: 'Cairo', sans-serif; color: #7c2438;">منتجات أخرى</h2>
            </div>
            <section class="photos-items">
                @foreach($randomProducts as $product)
                    @include('store.partials.product_card', ['product' => $product])
                @endforeach
            </section>
        @endif
    @endif
    
    <button id="btn-show-more">عرض المزيد</button>

@endsection
