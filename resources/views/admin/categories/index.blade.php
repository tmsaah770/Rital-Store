@extends('layouts.admin')

@section('title', 'إدارة الأقسام - ريتال ستور')

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

    .badge-count {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        background-color: #fdf2f4;
        color: #8B2635;
        border: 1px solid #f2d6dc;
    }
    .action-btn-sm {
        text-decoration: none;
        padding: 6px 14px;
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
    .btn-edit {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
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
    .cat-search-box {
        position: relative;
        min-width: 280px;
        max-width: 500px;
        width: 100%;
    }
    .cat-search-input {
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
    .cat-search-input:focus {
        border-color: #8B2635;
        box-shadow: 0 0 0 3.5px rgba(139, 38, 53, 0.12);
    }
    .cat-search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        color: #8B2635;
        pointer-events: none;
        opacity: 0.8;
    }
    .cat-clear-btn {
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
    .cat-clear-btn:hover {
        background: #8B2635;
        color: #ffffff;
        transform: translateY(-50%) scale(1.1);
    }
</style>
@endpush

@section('content')
    <header class="header" id="dashboard-header" style="padding: 20px 15px; border-radius: 0 0 24px 24px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
        <div class="dashboard-top-row">
            <div style="flex: 1;"></div>
            <h1 class="dashboard-nav-title" style="flex: 2;">إدارة الأقسام</h1>
            <div class="dashboard-logout-container" style="flex: 1;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" id="dashboard-logout-btn" class="dashboard-logout-btn">تسجيل خروج</button>
                </form>
            </div>
        </div>

        <div class="dashboard-nav-actions">
            <a href="{{ route('home') }}" class="dashboard-nav-btn">زيارة المتجر 🏪</a>
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="dashboard-nav-btn">← لوحة التحكم</a>
            @endif
        </div>

        <div class="top-bar" style="padding: 0;">
            <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
        </div>
        <h1 class="brand-name">ريتال ستور</h1>
    </header>

    <section class="dashboard" style="max-width: 1050px; margin: 0 auto; padding: 10px 15px 40px; width: 100%; box-sizing: border-box;">

        <!-- سيرش بار البحث في الأقسام في نص العرض -->
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 22px; width: 100%;">
            <div class="cat-search-box" style="width: 100%; max-width: 480px; margin: 0 auto;">
                <div style="position: relative;">
                    <span class="cat-search-icon">🔍</span>
                    <input type="text" 
                           id="cat-search-input" 
                           placeholder="ابحث باسم القسم..." 
                           autocomplete="off"
                           class="cat-search-input">
                    <button type="button" 
                            id="cat-clear-search-btn" 
                            class="cat-clear-btn" 
                            title="مسح البحث">✕</button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div id="flash-success-msg" style="background-color: #e8f8f0; border: 1px solid #a3e9c4; color: #1b7e4b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-family: 'Cairo', sans-serif; font-size: 14px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <!-- ============================================ -->
        <!-- قسم وجدول التصنيفات -->
        <!-- ============================================ -->
        <section class="categories-section" style="background: #ffffff; border-radius: 12px; box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.06); padding: 22px 20px; margin: 15px auto; font-family: 'Cairo', sans-serif; width: 100%; box-sizing: border-box; border-bottom: 3px solid var(--primary);">
            
            <!-- الهيدر الخاص بالجدول -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px; width: 100%;">
                <div style="flex: 1; display: flex; justify-content: flex-start; min-width: 140px;">
                    <button type="button" id="open-add-cat-btn" class="login-btn" style="padding: 8px 18px; font-size: 13px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; width: auto; background-color: #8B2635; margin: 0;">
                        + إضافة قسم جديد
                    </button>
                </div>
                <div style="flex: 2; text-align: center;">
                    <h2 style="font-size: 18px; color: #333; margin: 0; font-weight: 700; text-align: center;">أقسام وتصنيفات المتجر</h2>
                </div>
                <div style="flex: 1; display: flex; justify-content: flex-end; min-width: 140px;">
                    <span style="font-size: 13px; color: #777;">
                        إجمالي الأقسام: <strong id="cat-count-badge" style="color: #8B2635;">{{ $categories->count() }}</strong>
                    </span>
                </div>
            </div>

            <!-- تنبيه توضيحي لطيف -->
            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; color: #92400e; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 12.5px; display: flex; align-items: center; gap: 8px;">
                <span>⚠️</span>
                <span><strong>ملاحظة هامة:</strong> عند حذف أي قسم، يتم تلقائياً وبشكل مباشر حذف كافة المنتجات التابعة لهذا القسم لحماية انتظام وتكامل المتجر.</span>
            </div>

            <!-- جدول التصنيفات -->
            <div style="overflow-x: auto; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; min-width: 600px; margin: 0 auto;">
                    <thead>
                        <tr style="background: #fdf2f4; border-bottom: 2px solid #f2d6dc;">
                            <th style="padding: 14px 16px; text-align: right; font-size: 13px; color: #8B2635; font-weight: bold;">اسم القسم</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">عدد المنتجات التابعة</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">تاريخ الإضافة</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="categories-list-body">
                        @forelse($categories as $category)
                            <tr id="category-row-{{ $category->id }}" class="category-row" data-name="{{ $category->name }}" style="border-bottom: 1px solid #eee; transition: background 0.2s;">
                                <td style="padding: 12px 16px; font-weight: 600; color: #333; text-align: right;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 34px; height: 34px; border-radius: 8px; background: #f3e6e8; color: #8B2635; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; flex-shrink: 0;">
                                            🏷️
                                        </div>
                                        <span id="cat-name-display-{{ $category->id }}" style="font-size: 14px; font-weight: 700; color: #2c3e50;">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <span class="badge-count" id="cat-products-count-{{ $category->id }}">
                                        {{ $category->products_count }} {{ $category->products_count == 1 ? 'منتج' : ($category->products_count == 2 ? 'منتجان' : 'منتجات') }}
                                    </span>
                                </td>
                                <td style="padding: 12px 16px; color: #888; font-size: 12px; text-align: center;">
                                    {{ $category->created_at ? $category->created_at->format('Y/m/d') : '-' }}
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <button type="button" class="action-btn-sm btn-edit cat-edit-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                            ✏️ تعديل
                                        </button>
                                        <button type="button" class="action-btn-sm btn-delete cat-delete-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-count="{{ $category->products_count }}">
                                            🗑️ حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-categories-row">
                                <td colspan="4" style="text-align: center; padding: 25px; color: #888; font-size: 14px;">لا يوجد أقسام مسجلة حالياً. يمكنك إضافة قسم جديد من الزر أعلاه.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </section>

    <!-- نافذة إضافة قسم جديد (Modal) -->
    <div class="checkout-overlay" id="add-cat-overlay">
        <div class="checkout-box" style="max-width: 400px; text-align: right;">
            <button type="button" class="checkout-close" id="add-cat-close">×</button>
            <h3 class="checkout-section-title" style="margin-bottom: 18px; text-align: right;">إضافة قسم جديد</h3>

            <form id="add-category-form">
                <div style="margin-bottom: 14px; text-align: right;">
                    <label for="new-cat-name" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px;">اسم القسم / التصنيف</label>
                    <input type="text" id="new-cat-name" class="input" placeholder="مثال: فساتين سهرة، عبايات..." required style="margin-bottom: 0;">
                </div>

                <p id="new-cat-error" class="login-error" style="margin-top: 6px; text-align: center;"></p>
                <button type="button" id="confirm-add-cat-btn" class="confirm-order-btn" style="margin-top: 10px;">حفظ القسم</button>
            </form>
        </div>
    </div>

    <!-- نافذة تعديل قسم (Modal) -->
    <div class="checkout-overlay" id="edit-cat-overlay">
        <div class="checkout-box" style="max-width: 400px; text-align: right;">
            <button type="button" class="checkout-close" id="edit-cat-close">×</button>
            <h3 class="checkout-section-title" style="margin-bottom: 18px; text-align: right;">تعديل اسم القسم</h3>

            <form id="edit-category-form">
                <input type="hidden" id="edit-cat-id">
                <div style="margin-bottom: 14px; text-align: right;">
                    <label for="edit-cat-name" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px;">اسم القسم الجديد</label>
                    <input type="text" id="edit-cat-name" class="input" required style="margin-bottom: 0;">
                </div>

                <p id="edit-cat-error" class="login-error" style="margin-top: 6px; text-align: center;"></p>
                <button type="button" id="confirm-edit-cat-btn" class="confirm-order-btn" style="margin-top: 10px;">تحديث القسم</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // عناصر نافذة الإضافة
        var addModal = document.getElementById('add-cat-overlay');
        var openAddBtn = document.getElementById('open-add-cat-btn');
        var closeAddBtn = document.getElementById('add-cat-close');
        var confirmAddBtn = document.getElementById('confirm-add-cat-btn');
        var newCatNameInput = document.getElementById('new-cat-name');
        var newCatErrorEl = document.getElementById('new-cat-error');

        // عناصر نافذة التعديل
        var editModal = document.getElementById('edit-cat-overlay');
        var closeEditBtn = document.getElementById('edit-cat-close');
        var confirmEditBtn = document.getElementById('confirm-edit-cat-btn');
        var editCatIdInput = document.getElementById('edit-cat-id');
        var editCatNameInput = document.getElementById('edit-cat-name');
        var editCatErrorEl = document.getElementById('edit-cat-error');

        var tableBody = document.getElementById('categories-list-body');
        var countBadge = document.getElementById('cat-count-badge');

        // فتح نافذة الإضافة
        if (openAddBtn && addModal) {
            openAddBtn.onclick = function () {
                newCatErrorEl.textContent = '';
                newCatNameInput.value = '';
                addModal.classList.add('active');
                newCatNameInput.focus();
            };
        }

        // إغلاق نافذة الإضافة
        if (closeAddBtn && addModal) {
            closeAddBtn.onclick = function () {
                addModal.classList.remove('active');
            };
        }

        // إغلاق نافذة التعديل
        if (closeEditBtn && editModal) {
            closeEditBtn.onclick = function () {
                editModal.classList.remove('active');
            };
        }

        // حفظ قسم جديد عبر AJAX
        if (confirmAddBtn) {
            confirmAddBtn.onclick = function () {
                var name = newCatNameInput.value.trim();
                newCatErrorEl.textContent = '';

                if (!name || name.length < 2) {
                    newCatErrorEl.textContent = 'اسم القسم يجب ألا يقل عن حرفين.';
                    return;
                }

                confirmAddBtn.disabled = true;
                confirmAddBtn.textContent = 'جاري الحفظ...';

                fetch('{{ route('admin.categories.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: name })
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    });
                })
                .then(function(res) {
                    confirmAddBtn.disabled = false;
                    confirmAddBtn.textContent = 'حفظ القسم';

                    if (res.ok && res.data.success) {
                        addModal.classList.remove('active');
                        window.location.reload();
                    } else if (res.status === 422 && res.data.errors) {
                        var firstKey = Object.keys(res.data.errors)[0];
                        newCatErrorEl.textContent = res.data.errors[firstKey][0];
                    } else {
                        newCatErrorEl.textContent = res.data.message || 'حدث خطأ أثناء حفظ القسم.';
                    }
                })
                .catch(function() {
                    confirmAddBtn.disabled = false;
                    confirmAddBtn.textContent = 'حفظ القسم';
                    newCatErrorEl.textContent = 'تعذر الاتصال بالسيرفر. يرجى المحاولة لاحقاً.';
                });
            };
        }

        // ربط أزرار التعديل
        function attachEditHandlers() {
            document.querySelectorAll('.cat-edit-btn').forEach(function (btn) {
                btn.onclick = function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');

                    editCatIdInput.value = id;
                    editCatNameInput.value = name;
                    editCatErrorEl.textContent = '';
                    editModal.classList.add('active');
                    editCatNameInput.focus();
                };
            });
        }
        attachEditHandlers();

        // حفظ التعديل عبر AJAX
        if (confirmEditBtn) {
            confirmEditBtn.onclick = function () {
                var id = editCatIdInput.value;
                var name = editCatNameInput.value.trim();
                editCatErrorEl.textContent = '';

                if (!name || name.length < 2) {
                    editCatErrorEl.textContent = 'اسم القسم يجب ألا يقل عن حرفين.';
                    return;
                }

                confirmEditBtn.disabled = true;
                confirmEditBtn.textContent = 'جاري التحديث...';

                fetch('/admin/categories/' + id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: name })
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    });
                })
                .then(function(res) {
                    confirmEditBtn.disabled = false;
                    confirmEditBtn.textContent = 'تحديث القسم';

                    if (res.ok && res.data.success) {
                        editModal.classList.remove('active');
                        var displayEl = document.getElementById('cat-name-display-' + id);
                        if (displayEl) {
                            displayEl.textContent = res.data.category.name;
                        }
                        var editBtn = document.querySelector('.cat-edit-btn[data-id="' + id + '"]');
                        if (editBtn) {
                            editBtn.setAttribute('data-name', res.data.category.name);
                        }
                    } else if (res.status === 422 && res.data.errors) {
                        var firstKey = Object.keys(res.data.errors)[0];
                        editCatErrorEl.textContent = res.data.errors[firstKey][0];
                    } else {
                        editCatErrorEl.textContent = res.data.message || 'حدث خطأ أثناء تعديل القسم.';
                    }
                })
                .catch(function() {
                    confirmEditBtn.disabled = false;
                    confirmEditBtn.textContent = 'تحديث القسم';
                    editCatErrorEl.textContent = 'تعذر الاتصال بالسيرفر. يرجى المحاولة لاحقاً.';
                });
            };
        }

        // ربط أزرار الحذف مع التحذير الصارم (Cascade warning)
        function attachDeleteHandlers() {
            document.querySelectorAll('.cat-delete-btn').forEach(function (btn) {
                btn.onclick = function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    var count = this.getAttribute('data-count') || 0;

                    var msg = '⚠️ تحذير هام:\nهل أنت متأكد من حذف القسم "' + name + '"؟\n';
                    if (parseInt(count) > 0) {
                        msg += 'سيؤدي حذف هذا القسم إلى حذف ' + count + ' منتج مرتبط به بشكل نهائي وتلقائي!';
                    } else {
                        msg += 'لن تتمكن من استرجاع هذا القسم بعد الحذف.';
                    }

                    if (confirm(msg)) {
                        fetch('/admin/categories/' + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(function(res) { return res.json(); })
                        .then(function(data) {
                            if (data.success) {
                                var row = document.getElementById('category-row-' + id);
                                if (row) {
                                    row.style.transition = 'opacity 0.3s';
                                    row.style.opacity = '0';
                                    setTimeout(function() { row.remove(); }, 300);
                                }
                                if (countBadge) {
                                    var currentCount = parseInt(countBadge.textContent) - 1;
                                    countBadge.textContent = currentCount >= 0 ? currentCount : 0;
                                }
                            } else {
                                alert(data.message || 'حدث خطأ أثناء الحذف.');
                            }
                        })
                        .catch(function() {
                            alert('تعذر الاتصال بالسيرفر لحذف القسم.');
                        });
                    }
                };
            });
        }
        attachDeleteHandlers();

    // === سيرش بار البحث في الأقسام ===
    const catSearchInput = document.getElementById('cat-search-input');
    const catClearBtn = document.getElementById('cat-clear-search-btn');
    const catCountBadge = document.getElementById('cat-count-badge');

    function normalizeArabic(text) {
        if (!text) return '';
        return text.toString().toLowerCase().trim()
            .replace(/[أإآ]/g, 'ا')
            .replace(/ة/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/[\u064B-\u065F]/g, '');
    }

    function filterCategories() {
        const rawVal = catSearchInput ? catSearchInput.value.trim() : '';
        const normVal = normalizeArabic(rawVal);

        if (catClearBtn) {
            catClearBtn.style.display = rawVal.length > 0 ? 'flex' : 'none';
        }

        const catRows = document.querySelectorAll('.category-row');
        let visibleCount = 0;

        catRows.forEach(function (row) {
            const rowName = row.getAttribute('data-name') || '';
            const normName = normalizeArabic(rowName);

            if (normVal === '' || normName.includes(normVal)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (catCountBadge) {
            catCountBadge.textContent = visibleCount;
        }
    }

    if (catSearchInput) {
        catSearchInput.addEventListener('input', filterCategories);
    }

    if (catClearBtn) {
        catClearBtn.addEventListener('click', function () {
            if (catSearchInput) catSearchInput.value = '';
            filterCategories();
            if (catSearchInput) catSearchInput.focus();
        });
    }

    });
</script>
@endpush
