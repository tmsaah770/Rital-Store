@extends('layouts.admin')

@section('title', 'إدارة المنتجات - ريتال ستور')

@push('styles')
<style>
    .dashboard-logout-btn {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: none !important;
        padding: 8px 22px !important;
        border-radius: 20px !important;
        font-family: 'Cairo', sans-serif !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15) !important;
        transition: background-color 0.25s ease, color 0.25s ease !important;
    }
    .dashboard-logout-btn:hover {
        background-color: #ff3333 !important;
        color: #000000 !important;
    }
    .dashboard-nav-title {
        margin: 0;
        color: #ffffff;
        font-family: 'Cairo', sans-serif;
        font-size: 24px;
        font-weight: 700;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        text-align: center;
        z-index: 5;
    }
    .dashboard-top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1200px;
    }
    .dashboard-logout-container {
        display: flex;
        justify-content: flex-start;
    }
    @media (max-width: 600px) {
        .dashboard-nav-title {
            font-size: 18px;
        }
        .dashboard-top-row {
            flex-direction: column;
            gap: 15px;
        }
        .dashboard-top-row > div:first-child {
            display: none; /* Hide the empty placeholder div on mobile */
        }
        .dashboard-logout-container {
            justify-content: center;
        }
    }
    .dashboard-nav-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin: 15px 0;
        z-index: 5;
    }
    .dashboard-nav-btn {
        background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 100%) !important;
        color: #6e0012ff !important;
        text-decoration: none !important;
        padding: 11px 24px !important;
        font-size: 14.5px !important;
        font-weight: 700 !important;
        font-family: 'Cairo', sans-serif !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 5px 16px rgba(0, 0, 0, 0.28) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
    }
    .dashboard-nav-btn:hover {
        background: linear-gradient(135deg, #721e2b 0%, #8B2635 100%) !important;
        color: #ffffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35) !important;
    }
    .dashboard {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 10px 20px 40px !important;
        box-sizing: border-box !important;
    }
    .products-manage-card {
        background: #ffffff;
        border: 2px solid #8B2635;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        margin-top: 15px;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .badge-tag {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        margin: 2px;
    }
    .badge-color {
        background: #fdf2f4;
        color: #8B2635;
        border: 1px solid #f2d6dc;
    }
    .badge-size {
        background: #f0f4f8;
        color: #2c3e50;
        border: 1px solid #d5e0ea;
    }
    .action-btn-sm {
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        border: none;
        font-family: 'Cairo', sans-serif;
        transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .action-btn-sm:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    .product-search-box {
        position: relative;
        min-width: 280px;
        max-width: 500px;
        width: 100%;
    }
    .product-search-input {
        width: 100%;
        height: 44px;
        padding: 6px 42px 6px 36px;
        border-radius: 25px;
        border: 1.5px solid #dcc2c7;
        background-color: #ffffff;
        font-family: 'Cairo', sans-serif;
        font-size: 14px;
        color: #111111;
        outline: none;
        box-sizing: border-box;
        transition: all 0.25s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    .product-search-input:focus {
        border-color: #8B2635;
        background-color: #ffffff;
        box-shadow: 0 0 0 3.5px rgba(139, 38, 53, 0.12);
    }
    .product-search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        color: #8B2635;
        pointer-events: none;
        opacity: 0.8;
    }
    .product-clear-btn {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: #eed5d9;
        border: none;
        color: #8B2635;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.2s, transform 0.15s;
    }
    .product-clear-btn:hover {
        background: #8B2635;
        color: #ffffff;
        transform: translateY(-50%) scale(1.1);
    }
    .category-pills-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding: 0 4px;
    }
    .category-pill {
        padding: 7px 20px;
        border-radius: 25px;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: 1.5px solid #dcc2c7;
        background-color: #ffffff;
        color: #555;
        transition: all 0.25s ease;
        white-space: nowrap;
        user-select: none;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }
    .category-pill:hover {
        background-color: #fdf2f4;
        border-color: #c4949d;
        color: #8B2635;
    }
    .category-pill.active {
        background-color: #8B2635;
        color: #ffffff;
        border-color: #8B2635;
        box-shadow: 0 3px 10px rgba(139, 38, 53, 0.3);
    }
    @media (max-width: 768px) {
        .products-top-bar {
            flex-direction: column !important;
            gap: 14px !important;
            align-items: stretch !important;
        }
        .products-top-bar .add-btn-wrapper {
            position: static !important;
            transform: none !important;
            width: 100% !important;
            display: flex !important;
            justify-content: flex-start !important;
        }
        .product-search-box {
            max-width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
    <header class="header" id="dashboard-header" style="padding: 20px 15px; border-radius: 0 0 24px 24px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
        <div class="dashboard-top-row">
            <div style="flex: 1;"></div>
            <h1 class="dashboard-nav-title" style="flex: 2;">إدارة المنتجات</h1>
            <div class="dashboard-logout-container" style="flex: 1;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" id="dashboard-logout-btn" class="dashboard-logout-btn">تسجيل خروج</button>
                </form>
            </div>
        </div>

        <div class="dashboard-nav-actions">
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="dashboard-nav-btn">← لوحة التحكم</a>
            @endif
            <a href="{{ route('home') }}" class="dashboard-nav-btn">🏪 زيارة المتجر</a>
        </div>

        <div class="top-bar" style="padding: 0;">
            <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
        </div>
        <h1 class="brand-name">ريتال ستور</h1>
    </header>

    <section class="dashboard" style="max-width: 100% !important; margin: 0 !important; padding: 10px 20px 40px !important; width: 100% !important; box-sizing: border-box !important;">
        <!-- شريط علوي فوق الجدول يضم زر إضافة منتج والسيرش بار في منتصف العرض -->
        <div class="products-top-bar" style="display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 22px; width: 100%; min-height: 48px;">
            <!-- زر إضافة منتج جديد على اليمين -->
            <div class="add-btn-wrapper" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%);">
                <a href="{{ route('admin.products.create') }}" class="login-btn" style="text-decoration: none; padding: 10px 22px; font-size: 13.5px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background-color: #8B2635; width: auto; font-weight: bold; box-shadow: 0 4px 12px rgba(139, 38, 53, 0.25); white-space: nowrap;">+ إضافة منتج جديد</a>
            </div>

            <!-- سيرش بار في نص العرض تماماً فوق الجدول -->
            <div class="product-search-box" style="width: 100%; max-width: 480px; margin: 0 auto;">
                <form method="GET" action="{{ route('admin.products.index') }}" id="search-form" style="margin: 0; position: relative;">
                    <span class="product-search-icon">🔍</span>
                    <input type="text" 
                           name="search" 
                           id="product-search-input" 
                           value="{{ request('search') }}" 
                           placeholder="ابحث باسم المنتج أو كود المنتج (ID)..." 
                           autocomplete="off"
                           class="product-search-input">
                    <button type="button" 
                            id="clear-search-btn" 
                            class="product-clear-btn" 
                            title="مسح البحث"
                            style="{{ request('search') ? 'display: flex;' : 'display: none;' }}">✕</button>
                </form>
            </div>
        </div>

        <!-- تقسيمة الكاتيجوري فوق الجدول -->
        <div class="category-pills-bar">
            <button type="button" class="category-pill active" data-category-filter="all">الكل</button>
            @foreach($categories as $cat)
                <button type="button" class="category-pill" data-category-filter="{{ $cat->name }}">{{ $cat->name }}</button>
            @endforeach
        </div>

        @if(session('success'))
            <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        <!-- جدول بطاقة إدارة المنتجات -->
        <div class="products-manage-card" style="width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid #f0e2e4; padding-bottom: 16px;">
                <h2 style="font-size: 18px; color: #8B2635; margin: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <span>👗</span> قائمة المنتجات المتاحة في المتجر
                </h2>
                <span style="font-size: 13px; color: #555; background: #fdf2f4; padding: 4px 14px; border-radius: 20px; border: 1px solid #f2d6dc; font-weight: 600;">
                    إجمالي المعروض: <strong id="total-products-count" style="color: #8B2635; font-size: 14px;">{{ $products->count() }}</strong>
                </span>
            </div>

            <div style="overflow-x: auto; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; min-width: 850px;">
                    <thead>
                        <tr style="background: #fdf2f4; border-bottom: 2px solid #f2d6dc;">
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 75px;">كود المنتج</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 65px;">الصورة</th>
                            <th style="padding: 14px 16px; text-align: right; font-size: 13px; color: #8B2635; font-weight: bold;">اسم المنتج</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 100px;">القسم</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 90px;">السعر</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 90px;">الخصم</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 80px;">الألوان</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 80px;">المقاسات</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 70px;">الكمية</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold; width: 160px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $prod)
                            @php
                                $thumb = $prod->images->first() ? $prod->images->first()->image_url : asset('photos/photo-2.png');
                                $sizes = $prod->variants->pluck('size')->unique()->filter()->values();

                                $standardColorCodes = [
                                    'أسود'  => '#2b2b2b',
                                    'رمادي' => '#8a7d74',
                                    'بيج'   => '#e8dcc8',
                                    'كحلي'  => '#2c3e50',
                                    'بني'   => '#a67c52',
                                    'ابيض'  => '#ffffff',
                                    'أبيض'  => '#ffffff',
                                    'أحمر'  => '#e74c3c',
                                    'احمر'  => '#e74c3c',
                                    'أزرق'  => '#3498db',
                                    'ازرق'  => '#3498db',
                                    'أخضر'  => '#2ecc71',
                                    'اخضر'  => '#2ecc71',
                                    'وردي'  => '#e91e63',
                                    'بنفسجي'=> '#9b59b6',
                                    'أصفر'  => '#f1c40f',
                                    'اصفر'  => '#f1c40f',
                                ];

                                $renderedColors = [];
                                foreach ($prod->variants as $variant) {
                                    $raw = trim($variant->color_code ?: $variant->color_name ?: '');
                                    if (!$raw) continue;

                                    $cssColor = null;
                                    if (isset($standardColorCodes[$raw])) {
                                        $cssColor = $standardColorCodes[$raw];
                                    } elseif (isset($standardColorCodes[$variant->color_name])) {
                                        $cssColor = $standardColorCodes[$variant->color_name];
                                    } elseif (preg_match('/^#([0-9a-fA-F]{3,8})$/', $raw)) {
                                        $cssColor = $raw;
                                    } elseif (preg_match('/^([0-9a-fA-F]{3,8})#$/', $raw, $m)) {
                                        $cssColor = '#' . $m[1];
                                    } elseif (preg_match('/^[0-9a-fA-F]{6}$/', $raw)) {
                                        $cssColor = '#' . $raw;
                                    } else {
                                        $cssColor = $raw;
                                    }

                                    if ($cssColor && !in_array($cssColor, array_column($renderedColors, 'color'))) {
                                        $renderedColors[] = [
                                            'color' => $cssColor,
                                            'name'  => $variant->color_name ?: $cssColor
                                        ];
                                    }
                                }
                            @endphp
                            <tr class="product-row" data-id="{{ $prod->id }}" data-name="{{ $prod->name }}" data-category="{{ $prod->category ? $prod->category->name : '' }}" style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#fafafa';" onmouseout="this.style.backgroundColor='transparent';">
                                <td style="padding: 12px 14px; text-align: center; font-weight: 700; color: #000000; font-size: 13.5px;">
                                    {{ $prod->id }}
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    <img src="{{ $thumb }}" alt="{{ $prod->name }}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e5e5; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                                </td>
                                <td style="padding: 12px 16px; text-align: right;">
                                    <span style="font-weight: 700; color: #000000; font-size: 14px;">{{ $prod->name }}</span>
                                </td>
                                <td style="padding: 12px 16px; text-align: center; color: #000000; font-size: 13.5px; font-weight: 600;">
                                    {{ $prod->category ? $prod->category->name : '-' }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center; font-weight: 700; color: #000000; font-size: 14px; white-space: nowrap;">
                                    {{ number_format($prod->price, 0) }} ج
                                </td>
                                <td style="padding: 12px 16px; text-align: center; font-weight: 700; color: #000000; font-size: 14px; white-space: nowrap;">
                                    {{ ($prod->discount && $prod->discount > 0) ? number_format($prod->discount, 0) . ' ج' : '-' }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    @if(count($renderedColors) > 0)
                                        <div style="display: grid; grid-template-columns: repeat(2, 20px); gap: 4px; justify-content: center; align-items: center; margin: 0 auto; width: fit-content;">
                                            @foreach($renderedColors as $c)
                                                <div style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $c['color'] }}; border: 1px solid {{ in_array(strtolower($c['color']), ['#fff', '#ffffff', '#fff9f9', '#ffffff']) ? '#d1d5db' : 'rgba(0,0,0,0.15)' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.15);" title="{{ $c['name'] }}"></div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color: #000000; font-size: 13px;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    @if($sizes->isNotEmpty())
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 3px 6px; justify-content: center; align-items: center; max-width: 65px; margin: 0 auto; direction: ltr;">
                                            @foreach($sizes as $size)
                                                <span style="color: #000000; font-size: 13px; font-weight: 700; text-align: center;">{{ $size }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color: #000000; font-size: 13px;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px; text-align: center; color: #000000; font-size: 14px; font-weight: 700;">
                                    {{ $prod->quantity ?? 0 }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center; flex-wrap: nowrap; white-space: nowrap;">
                                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="action-btn-sm" style="background-color: #2c3e50; color: #ffffff;" title="تعديل بيانات المنتج">
                                             ✏️ تعديل
                                        </a>
                                        <form action="{{ route('admin.products.delete', $prod->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المنتج نهائياً؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-sm" style="background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">
                                                🗑️ حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 40px 20px; color: #888;">
                                    <div style="font-size: 36px; margin-bottom: 10px;">👗</div>
                                    <p style="font-size: 15px; font-weight: 600; margin-bottom: 10px;">لا توجد منتجات مسجلة حالياً في المتجر.</p>
                                    <a href="{{ route('admin.products.create') }}" class="login-btn" style="text-decoration: none; padding: 8px 18px; font-size: 13px; border-radius: 6px; display: inline-block; background-color: #8B2635; width: auto;">
                                        + أضف أول منتج الآن
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                        <!-- صف في حالة عدم العثور على نتائج بحث -->
                        <tr id="no-search-results-row" style="display: none;">
                            <td colspan="10" style="text-align: center; padding: 45px 20px; background-color: #fcfcfc;">
                                <div style="font-size: 36px; margin-bottom: 10px;">🔍</div>
                                <p style="font-size: 16px; font-weight: 700; color: #8B2635; margin: 0 0 6px;">لا توجد منتجات مطابقة للبحث</p>
                                <p id="no-results-query-text" style="font-size: 13.5px; color: #666; margin: 0 0 16px;"></p>
                                <button type="button" id="reset-search-btn" style="background: #8B2635; color: #ffffff; border: none; padding: 8px 20px; border-radius: 20px; font-family: 'Cairo', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px rgba(139, 38, 53, 0.2);">
                                    ✕ مسح البحث وعرض كل المنتجات
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('product-search-input');
    const clearBtn = document.getElementById('clear-search-btn');
    const resetBtn = document.getElementById('reset-search-btn');
    const totalCountEl = document.getElementById('total-products-count');
    const noResultsRow = document.getElementById('no-search-results-row');
    const noResultsQueryText = document.getElementById('no-results-query-text');
    const productRows = document.querySelectorAll('.product-row');
    const categoryPills = document.querySelectorAll('.category-pill');

    let activeCategory = 'all';

    function normalizeArabic(text) {
        if (!text) return '';
        return text.toString().toLowerCase().trim()
            .replace(/[أإآ]/g, 'ا')
            .replace(/ة/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/[\u064B-\u065F]/g, '');
    }

    function filterProducts() {
        const rawVal = searchInput ? searchInput.value.trim() : '';
        const normVal = normalizeArabic(rawVal);
        const cleanIdVal = rawVal.replace(/^#/, '').trim();

        if (clearBtn) {
            clearBtn.style.display = rawVal.length > 0 ? 'flex' : 'none';
        }

        let visibleCount = 0;

        productRows.forEach(function (row) {
            const rowId = (row.getAttribute('data-id') || '').trim();
            const rowName = row.getAttribute('data-name') || '';
            const rowCategory = row.getAttribute('data-category') || '';
            const normName = normalizeArabic(rowName);

            // فلتر الكاتيجوري
            let categoryMatch = (activeCategory === 'all') || (rowCategory === activeCategory);

            // فلتر البحث
            let searchMatch = true;
            if (normVal !== '') {
                const isIdMatch = (cleanIdVal !== '' && (rowId === cleanIdVal || rowId.startsWith(cleanIdVal)));
                const isNameMatch = normName.includes(normVal);
                searchMatch = isIdMatch || isNameMatch;
            }

            if (categoryMatch && searchMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (totalCountEl) {
            totalCountEl.textContent = visibleCount;
        }

        if (productRows.length > 0) {
            if (visibleCount === 0) {
                if (noResultsRow) noResultsRow.style.display = '';
                if (noResultsQueryText) {
                    let msg = 'لا توجد منتجات مطابقة';
                    if (rawVal) msg += ` للبحث: "${rawVal}"`;
                    if (activeCategory !== 'all') msg += ` في قسم: "${activeCategory}"`;
                    noResultsQueryText.textContent = msg;
                }
            } else {
                if (noResultsRow) noResultsRow.style.display = 'none';
            }
        }
    }

    // أحداث أزرار الكاتيجوري
    categoryPills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            categoryPills.forEach(function (p) { p.classList.remove('active'); });
            pill.classList.add('active');
            activeCategory = pill.getAttribute('data-category-filter');
            filterProducts();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', filterProducts);
        searchInput.addEventListener('keyup', filterProducts);

        if (searchInput.value.trim() !== '') {
            filterProducts();
        }
    }

    function clearSearch() {
        if (searchInput) searchInput.value = '';
        filterProducts();
        if (searchInput) searchInput.focus();

        if (window.location.search.includes('search=')) {
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            window.history.replaceState({}, '', url.pathname + (url.search ? url.search : ''));
        }
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', clearSearch);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', clearSearch);
    }
});
</script>
@endpush
