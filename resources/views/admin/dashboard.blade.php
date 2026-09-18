@extends('layouts.admin')

@section('title', 'لوحة التحكم - ريتال ستور')

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
        padding: 11px 22px !important;
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
</style>
@endpush

@section('content')
    <header class="header" id="dashboard-header" style="padding: 20px 15px; border-radius: 0 0 24px 24px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
        
        <div class="dashboard-top-row">
            <div style="flex: 1;"></div> <!-- Placeholder -->
            <h1 class="dashboard-nav-title" style="flex: 2;">لوحة التحكم</h1>
            <div class="dashboard-logout-container" style="flex: 1;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" id="dashboard-logout-btn" class="dashboard-logout-btn">تسجيل خروج</button>
                </form>
            </div>
        </div>

        <div class="dashboard-nav-actions">
            <a href="{{ route('home') }}" class="dashboard-nav-btn">زيارة المتجر 🏪</a>
            <a href="{{ route('admin.orders') }}" class="dashboard-nav-btn">متابعة الطلبيات 📦</a>
            <a href="{{ route('admin.products.index') }}" id="admin-products-btn" class="dashboard-nav-btn">إدارة المنتجات 👗</a>
            <a href="{{ route('admin.categories.index') }}" id="admin-categories-btn" class="dashboard-nav-btn">إدارة الأقسام 🏷️</a>
        </div>

        <div class="top-bar" style="padding: 0;">
            <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
        </div>
        <h1 class="brand-name">ريتال ستور</h1>
    </header>

    <section class="dashboard" style="max-width: 1050px; margin: 0 auto; padding: 10px 15px 30px; width: 100%; box-sizing: border-box;">

        <div class="stats-grid">
            <div class="stat-card">
                <p class="stat-number" id="total-visitors">{{ $totalVisitors }}</p>
                <p class="stat-label">عدد الزوار</p>
            </div>

            <div class="stat-card">
                <p class="stat-number" id="whatsapp-clicks">{{ $whatsappClicks }}</p>
                <p class="stat-label">ضغطات زرار واتساب</p>
            </div>

            <div class="stat-card">
                <p class="stat-number" id="product-clicks">{{ $productClicks }}</p>
                <p class="stat-label">ضغطات على المنتجات</p>
            </div>
            
            <div class="stat-card">
                <p class="stat-number" id="product-seales">{{ $totalSales }}</p>
                <p class="stat-label">عدد المبيعات الي تمت</p>
            </div>
            
            <div class="stat-card">
                <p class="stat-number" id="product-returns">{{ $totalReturns }}</p>
                <p class="stat-label">عدد المرتجعات </p>
            </div>
        </div>

        @if(session('success'))
            <div id="flash-success-msg" style="background-color: #e8f8f0; border: 1px solid #a3e9c4; color: #1b7e4b; padding: 12px 16px; border-radius: 8px; margin-top: 20px; font-family: 'Cairo', sans-serif; font-size: 14px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <!-- ============================================ -->
        <!-- قسم وجدول الموظفين -->
        <!-- ============================================ -->
        <section class="employees-section" style="background: #ffffff; border-radius: 12px; box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.06); padding: 22px 20px; margin: 25px auto; font-family: 'Cairo', sans-serif; max-width: 1000px; width: 100%; box-sizing: border-box; border-bottom: 3px solid var(--primary);">
            
            <!-- الهيدر الخاص بالجدول: زر الإضافة في اليمين، العنوان في المنتصف، والعدد في اليسار -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px; width: 100%;">
                <div style="flex: 1; display: flex; justify-content: flex-start; min-width: 140px;">
                    <button type="button" id="open-add-emp-btn" class="login-btn" style="padding: 8px 18px; font-size: 13px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; width: auto; background-color: #8B2635; margin: 0;">
                        + إضافة موظف
                    </button>
                </div>
                <div style="flex: 2; text-align: center;">
                    <h2 style="font-size: 18px; color: #333; margin: 0; font-weight: 700; text-align: center;">فريق عمل المحل</h2>
                </div>
                <div style="flex: 1; display: flex; justify-content: flex-end; min-width: 140px;">
                    <span style="font-size: 13px; color: #777;">
                        إجمالي الموظفين: <strong id="emp-count-badge" style="color: #8B2635;">{{ $employees->count() }}</strong>
                    </span>
                </div>
            </div>

            <!-- جدول بيانات الموظفين المتمركز في المنتصف -->
            <div style="overflow-x: auto; width: 100%;">
                <table class="employees-table" style="width: 100%; border-collapse: collapse; min-width: 600px; margin: 0 auto;">
                    <thead>
                        <tr style="background: #fdf2f4; border-bottom: 2px solid #f2d6dc;">
                            <th style="padding: 14px 16px; text-align: right; font-size: 13px; color: #8B2635; font-weight: bold;">اسم الموظف</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">البريد الإلكتروني</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">رقم الموبايل</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">تاريخ الإضافة</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="employees-list-body">
                        @forelse($employees as $emp)
                            <tr id="emp-row-{{ $emp->id }}" style="border-bottom: 1px solid #eee; transition: background 0.2s;">
                                <td style="padding: 12px 16px; font-weight: 600; color: #333; text-align: right;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #f3e6e8; color: #8B2635; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px; flex-shrink: 0;">
                                            {{ mb_substr($emp->name, 0, 1) }}
                                        </div>
                                        <span>{{ $emp->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; color: #555; font-size: 13px; text-align: center;">{{ $emp->email }}</td>
                                <td style="padding: 12px 16px; color: #555; font-size: 13px; text-align: center; direction: ltr;">{{ $emp->phone ?: '-' }}</td>
                                <td style="padding: 12px 16px; color: #888; font-size: 12px; text-align: center;">{{ $emp->created_at ? $emp->created_at->format('Y/m/d') : '-' }}</td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <button type="button" class="emp-delete-btn" data-emp-id="{{ $emp->id }}" data-emp-name="{{ $emp->name }}" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 5px 14px; border-radius: 6px; cursor: pointer; font-size: 12px; font-family: 'Cairo', sans-serif; font-weight: 600; transition: all 0.2s;">
                                        حذف
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-employees-row">
                                <td colspan="5" style="text-align: center; padding: 25px; color: #888; font-size: 14px;">لا يوجد موظفين مسجلين حالياً. يمكنك إضافة موظف جديد من الزر أعلاه.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

        <!-- ============================================ -->
        <!-- قسم وجدول المستخدمين -->
        <!-- ============================================ -->
        <section class="users-section" style="background: #ffffff; border-radius: 12px; box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.06); padding: 22px 20px; margin: 25px auto; font-family: 'Cairo', sans-serif; max-width: 1000px; width: 100%; box-sizing: border-box; border-bottom: 3px solid var(--primary);">
            
            <!-- الهيدر الخاص بالجدول: العنوان في المنتصف، والعدد في اليسار -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px; width: 100%;">
                <div style="flex: 1; min-width: 140px;"></div>
                <div style="flex: 2; text-align: center;">
                    <h2 style="font-size: 18px; color: #333; margin: 0; font-weight: 700; text-align: center;">المستخدمين المسجلين</h2>
                </div>
                <div style="flex: 1; display: flex; justify-content: flex-end; min-width: 140px;">
                    <span style="font-size: 13px; color: #777;">
                        إجمالي المستخدمين: <strong id="user-count-badge" style="color: #8B2635;">{{ $users->count() }}</strong>
                    </span>
                </div>
            </div>

            <!-- جدول بيانات المستخدمين المتمركز في المنتصف -->
            <div style="overflow-x: auto; width: 100%;">
                <table class="employees-table" style="width: 100%; border-collapse: collapse; min-width: 600px; margin: 0 auto;">
                    <thead>
                        <tr style="background: #fdf2f4; border-bottom: 2px solid #f2d6dc;">
                            <th style="padding: 14px 16px; text-align: right; font-size: 13px; color: #8B2635; font-weight: bold;">اسم المستخدم</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">البريد الإلكتروني</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">رقم الموبايل</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">تاريخ التسجيل</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: #8B2635; font-weight: bold;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="users-list-body">
                        @forelse($users as $usr)
                            <tr id="user-row-{{ $usr->id }}" style="border-bottom: 1px solid #eee; transition: background 0.2s;">
                                <td style="padding: 12px 16px; font-weight: 600; color: #333; text-align: right;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #f3e6e8; color: #8B2635; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px; flex-shrink: 0;">
                                            {{ mb_substr($usr->name, 0, 1) }}
                                        </div>
                                        <span>{{ $usr->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px; color: #555; font-size: 13px; text-align: center;">{{ $usr->email }}</td>
                                <td style="padding: 12px 16px; color: #555; font-size: 13px; text-align: center; direction: ltr;">{{ $usr->phone ?: '-' }}</td>
                                <td style="padding: 12px 16px; color: #888; font-size: 12px; text-align: center;">{{ $usr->created_at ? $usr->created_at->format('Y/m/d') : '-' }}</td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <button type="button" class="user-delete-btn" data-user-id="{{ $usr->id }}" data-user-name="{{ $usr->name }}" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 5px 14px; border-radius: 6px; cursor: pointer; font-size: 12px; font-family: 'Cairo', sans-serif; font-weight: 600; transition: all 0.2s;">
                                        حذف
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-users-row">
                                <td colspan="5" style="text-align: center; padding: 25px; color: #888; font-size: 14px;">لا يوجد مستخدمين مسجلين حالياً.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

    </section>

    <!-- ============================================ -->
    <!-- نافذة إضافة موظف جديد (Modal) -->
    <!-- ============================================ -->
    <div class="checkout-overlay" id="add-emp-overlay">
        <div class="checkout-box" style="max-width: 400px; text-align: right;">
            <button type="button" class="checkout-close" id="add-emp-close">×</button>
            <h3 class="checkout-section-title" style="margin-bottom: 18px; text-align: right;">إضافة موظف جديد</h3>

            <form id="add-employee-form">
                <div style="margin-bottom: 12px; text-align: right;">
                    <label for="new-emp-name" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px;">اسم الموظف</label>
                    <input type="text" id="new-emp-name" class="input" placeholder="اسم الموظف بالكامل" required style="margin-bottom: 0;">
                </div>

                <div style="margin-bottom: 12px; text-align: right;">
                    <label for="new-emp-email" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px;">البريد الإلكتروني</label>
                    <input type="email" id="new-emp-email" class="input" placeholder="example@gmail.com" required style="margin-bottom: 0;">
                </div>

                <div style="margin-bottom: 12px; text-align: right;">
                    <label for="new-emp-phone" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px;">رقم الموبايل</label>
                    <input type="tel" id="new-emp-phone" class="input" placeholder="01012345678" required maxlength="11" style="margin-bottom: 0;">
                </div>

                <div style="margin-bottom: 12px; text-align: right;">
                    <label for="new-emp-password" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px;">كلمة المرور</label>
                    <input type="password" id="new-emp-password" class="input" placeholder="كلمة المرور (6 خانات على الأقل)" required style="margin-bottom: 0;">
                </div>

                <div style="margin-bottom: 12px; text-align: right;">
                    <label for="new-emp-password-confirmation" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px;">تأكيد كلمة المرور</label>
                    <input type="password" id="new-emp-password-confirmation" class="input" placeholder="أعد إدخال كلمة المرور" required style="margin-bottom: 0;">
                </div>

                <p id="new-emp-error" class="login-error" style="margin-top: 6px; text-align: center;"></p>
                <button type="button" id="confirm-add-emp-btn" class="confirm-order-btn" style="margin-top: 10px;">حفظ الموظف</button>
            </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ============================================
        // إدارة الموظفين
        // ============================================
        var empModal = document.getElementById('add-emp-overlay');
        var openEmpBtn = document.getElementById('open-add-emp-btn');
        var closeEmpBtn = document.getElementById('add-emp-close');
        var confirmEmpBtn = document.getElementById('confirm-add-emp-btn');
        var empErrorEl = document.getElementById('new-emp-error');
        var empPhoneInput = document.getElementById('new-emp-phone');

        if (empPhoneInput) {
            empPhoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        if (openEmpBtn && empModal) {
            openEmpBtn.onclick = function () {
                empErrorEl.textContent = '';
                empModal.classList.add('active');
                document.getElementById('new-emp-name').focus();
            };
        }

        if (closeEmpBtn && empModal) {
            closeEmpBtn.onclick = function () {
                empModal.classList.remove('active');
            };
        }

        if (confirmEmpBtn) {
            confirmEmpBtn.onclick = function () {
                var name = document.getElementById('new-emp-name').value.trim();
                var email = document.getElementById('new-emp-email').value.trim();
                var phone = document.getElementById('new-emp-phone').value.trim();
                var password = document.getElementById('new-emp-password').value;
                var passwordConfirmation = document.getElementById('new-emp-password-confirmation').value;

                empErrorEl.textContent = '';

                if (!name || name.length < 3) {
                    empErrorEl.textContent = 'اسم الموظف يجب ألا يقل عن 3 أحرف.';
                    return;
                }

                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailRegex.test(email)) {
                    empErrorEl.textContent = 'يرجى إدخال بريد إلكتروني صحيح.';
                    return;
                }

                var egyptPhoneRegex = /^01[0125][0-9]{8}$/;
                if (!phone || !egyptPhoneRegex.test(phone)) {
                    empErrorEl.textContent = 'رقم الموبايل يجب أن يكون 11 رقماً ويبدأ بـ (010, 011, 012, 015).';
                    return;
                }

                if (!password || password.length < 6) {
                    empErrorEl.textContent = 'كلمة المرور يجب ألا تقل عن 6 خانات.';
                    return;
                }

                if (password !== passwordConfirmation) {
                    empErrorEl.textContent = 'كلمة المرور وتأكيد كلمة المرور غير متطابقين.';
                    return;
                }

                confirmEmpBtn.disabled = true;
                confirmEmpBtn.textContent = 'جاري الحفظ...';

                fetch('{{ route('admin.employees.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        phone: phone,
                        password: password,
                        password_confirmation: passwordConfirmation
                    })
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    });
                })
                .then(function(res) {
                    confirmEmpBtn.disabled = false;
                    confirmEmpBtn.textContent = 'حفظ الموظف';

                    if (res.ok && res.data.success) {
                        empModal.classList.remove('active');
                        window.location.reload();
                    } else if (res.status === 422 && res.data.errors) {
                        var firstKey = Object.keys(res.data.errors)[0];
                        empErrorEl.textContent = res.data.errors[firstKey][0];
                    } else {
                        empErrorEl.textContent = res.data.message || 'حدث خطأ أثناء حفظ الموظف.';
                    }
                })
                .catch(function() {
                    confirmEmpBtn.disabled = false;
                    confirmEmpBtn.textContent = 'حفظ الموظف';
                    empErrorEl.textContent = 'تعذر الاتصال بالسيرفر. يرجى المحاولة لاحقاً.';
                });
            };
        }

        // حذف موظف
        document.querySelectorAll('.emp-delete-btn').forEach(function (btn) {
            btn.onclick = function () {
                var empId = this.getAttribute('data-emp-id');
                var empName = this.getAttribute('data-emp-name');

                if (confirm('هل أنت متأكد من حذف الموظف "' + empName + '"؟')) {
                    fetch('/admin/employees/' + empId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data.success) {
                            var row = document.getElementById('emp-row-' + empId);
                            if (row) {
                                row.style.transition = 'opacity 0.3s';
                                row.style.opacity = '0';
                                setTimeout(function() { row.remove(); }, 300);
                            }
                            var badge = document.getElementById('emp-count-badge');
                            if (badge) {
                                var count = parseInt(badge.textContent) - 1;
                                badge.textContent = count >= 0 ? count : 0;
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء الحذف');
                        }
                    })
                    .catch(function() {
                        alert('تعذر الاتصال بالسيرفر لحذف الموظف');
                    });
                }
            };
        });

        // ============================================
        // إدارة المستخدمين (حذف فقط)
        // ============================================
        // حذف مستخدم
        document.querySelectorAll('.user-delete-btn').forEach(function (btn) {
            btn.onclick = function () {
                var userId = this.getAttribute('data-user-id');
                var userName = this.getAttribute('data-user-name');

                if (confirm('هل أنت متأكد من حذف المستخدم "' + userName + '"؟')) {
                    fetch('/admin/users/' + userId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data.success) {
                            var row = document.getElementById('user-row-' + userId);
                            if (row) {
                                row.style.transition = 'opacity 0.3s';
                                row.style.opacity = '0';
                                setTimeout(function() { row.remove(); }, 300);
                            }
                            var badge = document.getElementById('user-count-badge');
                            if (badge) {
                                var count = parseInt(badge.textContent) - 1;
                                badge.textContent = count >= 0 ? count : 0;
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء الحذف');
                        }
                    })
                    .catch(function() {
                        alert('تعذر الاتصال بالسيرفر لحذف المستخدم');
                    });
                }
            };
        });
    });
</script>
@endpush
