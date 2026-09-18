@extends('layouts.admin')

@section('title', 'إدارة الطلبيات - ريتال ستور')

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
            <div style="flex: 1;"></div>
            <h1 class="dashboard-nav-title" style="flex: 2;">إدارة عمليات الطلب</h1>
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
                <a href="{{ route('admin.dashboard') }}" class="dashboard-nav-btn">لوحة التحكم ⚙️</a>
            @endif
        </div>

        <div class="top-bar" style="padding: 0;">
            <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
        </div>
        <h1 class="brand-name">ريتال ستور</h1>
    </header>

<section class="dashboard" id="employee-orders-section">

    <div class="orders-list" id="orders-list">
        @forelse($orders as $order)
            @php
                $firstItem = $order->items->first();
            @endphp
            <div class="order-card" id="order-card-{{ $order->id }}" data-order-db-id="{{ $order->id }}">
                <div class="order-header">
                    <span class="order-id">{{ $order->order_number }}</span>
                    <span class="order-date">تم الطلب: <span data-field="createdDate">{{ $order->created_at->format('Y/m/d - h:i A') }}</span></span>
                </div>

                <div class="order-body">
                    <p class="order-type"><strong>نوع الأوردر:</strong> <span data-field="type">{{ $firstItem ? $firstItem->product_type ?? $firstItem->product_name : 'فستان' }}</span></p>
                    <p><strong>اللون:</strong> <span data-field="color">{{ $firstItem ? $firstItem->color ?? '-' : '-' }}</span> &nbsp; | &nbsp; <strong>المقاس:</strong> <span data-field="size">{{ $firstItem ? $firstItem->size ?? '-' : '-' }}</span></p>
                    <p class="order-price"><strong>السعر:</strong> <span data-field="price">{{ number_format($order->total_amount, 0) }}</span> جنيه (دفع عند الاستلام)</p>

                    <hr class="order-divider">

                    <p><strong>العميل:</strong> <span data-field="customer">{{ $order->customer_name }}</span></p>
                    <p><strong>الهاتف:</strong> <span data-field="phone">{{ $order->customer_phone }}</span></p>
                    <p><strong>الإيميل:</strong> <span data-field="email">{{ $order->customer_email ?: 'لا يوجد' }}</span></p>
                    <p><strong>العنوان:</strong> <span data-field="address">{{ $order->customer_address }}</span></p>

                    <hr class="order-divider">

                    <p class="order-timing"><strong>وقت الإنشاء:</strong> <span data-field="createdTime">{{ $order->created_at->format('Y/m/d h:i A') }}</span></p>
                    <p class="order-timing"><strong>وقت التسليم:</strong> <span data-field="deliveredTime">{{ $order->delivered_at ? $order->delivered_at->format('Y/m/d h:i A') : 'لسه ماتسلمش' }}</span></p>

                    <p class="failure-note" style="{{ $order->status === 'failed' ? '' : 'display:none;' }}">
                        <strong>سبب فشل التوصيل:</strong> <span class="failure-reason-text">{{ $order->failure_reason }} {{ $order->failure_details ? '- ' . $order->failure_details : '' }}</span>
                    </p>
                </div>

                <div class="order-footer">
                    <select class="order-status" data-order-id="{{ $order->id }}" {{ $order->status === 'delivered' ? 'disabled' : '' }}>
                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>قيد التجهيز</option>
                        <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>قيد التوصيل</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                        <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>فشل التوصيل</option>
                    </select>
                </div>

                <div class="order-actions">
                    <span class="save-success-msg" id="save-msg-{{ $order->id }}">تم الحفظ بنجاح</span>
                </div>
            </div>
        @empty
            <p style="text-align:center; color:#666; font-family:'Cairo'; padding: 30px;">لا توجد طلبات حالياً.</p>
        @endforelse
    </div>
</section>

<!-- نافذة سبب فشل التوصيل -->
<div class="checkout-overlay" id="failure-overlay">
    <div class="checkout-box">
        <button type="button" class="checkout-close" id="failure-close">×</button>

        <h3 class="checkout-section-title">سبب فشل التوصيل - أوردر <span id="failure-order-id"></span></h3>

        <select id="failure-reason-select" class="input">
            <option value="">اختاري السبب</option>
            <option value="العميل لم يرد على المندوب">العميل لم يرد على المندوب</option>
            <option value="العميل رفض استلام الأوردر">العميل رفض استلام الأوردر</option>
            <option value="العنوان غير صحيح / غير موجود">العنوان غير صحيح / غير موجود</option>
            <option value="العميل طلب تأجيل التوصيل">العميل طلب تأجيل التوصيل</option>
            <option value="سبب آخر">سبب آخر</option>
        </select>

        <textarea id="failure-reason-details" class="input" rows="3" placeholder="تفاصيل إضافية (اختياري)"></textarea>

        <p id="failure-error" class="login-error"></p>

        <button type="button" id="failure-confirm-btn" class="confirm-order-btn">حفظ</button>
    </div>
</div>

<!-- نافذة إنشاء طلب جديد -->
<div class="checkout-overlay" id="new-order-overlay">
    <div class="checkout-box">
        <button type="button" class="checkout-close" id="new-order-close">×</button>
        <h3 class="checkout-section-title">إنشاء طلبية جديدة</h3>

        <form id="new-order-form">
            <input type="text" id="new-order-type" class="input" placeholder="نوع الأوردر (مثال: فستان سهرة)" required>
            <input type="text" id="new-order-color" class="input" placeholder="اللون (مثال: أسود)">
            <input type="text" id="new-order-size" class="input" placeholder="المقاس (مثال: M)">
            <input type="number" id="new-order-price" class="input" placeholder="السعر (جنيه)" required>
            <hr style="margin: 10px 0; border: none; border-top: 1px solid #eee;">
            <input type="text" id="new-order-customer" class="input" placeholder="اسم العميل" required>
            <input type="tel" id="new-order-phone" class="input" placeholder="رقم الهاتف" required>
            <input type="email" id="new-order-email" class="input" placeholder="البريد الإلكتروني (اختياري)">
            <input type="text" id="new-order-address" class="input" placeholder="العنوان بالتفصيل" required>
            
            <p id="new-order-error" class="login-error"></p>
            <button type="button" id="new-order-confirm-btn" class="confirm-order-btn">إنشاء الطلبية</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ربط إجراءات الأوردرات (الحالة، التعديل، الحذف، والإنشاء) بالباك إند مباشرة
    document.addEventListener('DOMContentLoaded', function () {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var failureOverlay = document.getElementById('failure-overlay');
        var failureCloseBtn = document.getElementById('failure-close');
        var failureConfirmBtn = document.getElementById('failure-confirm-btn');
        var currentFailureOrderId = null;
        var currentFailureSelect = null;

        // تحديث حالة الطلبية
        document.querySelectorAll('.order-status').forEach(function (select) {
            select.addEventListener('change', function () {
                var orderId = this.getAttribute('data-order-id');
                var val = this.value;

                if (val === 'failed') {
                    currentFailureOrderId = orderId;
                    currentFailureSelect = this;
                    document.getElementById('failure-order-id').textContent = '#' + orderId;
                    failureOverlay.classList.add('active');
                } else {
                    updateOrderStatus(orderId, val, null, null);
                }
            });
        });

        if (failureCloseBtn) {
            failureCloseBtn.onclick = function () {
                failureOverlay.classList.remove('active');
            };
        }

        if (failureConfirmBtn) {
            failureConfirmBtn.onclick = function () {
                var reason = document.getElementById('failure-reason-select').value;
                var details = document.getElementById('failure-reason-details').value;

                if (!reason) {
                    document.getElementById('failure-error').textContent = 'يرجى اختيار سبب الفشل';
                    return;
                }

                updateOrderStatus(currentFailureOrderId, 'failed', reason, details);
                failureOverlay.classList.remove('active');
            };
        }

        function updateOrderStatus(id, status, reason, details) {
            fetch('/admin/orders/' + id + '/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: status,
                    failure_reason: reason,
                    failure_details: details
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    var card = document.getElementById('order-card-' + id);
                    if (card) {
                        var timingDelivered = card.querySelector('[data-field="deliveredTime"]');
                        if (timingDelivered) timingDelivered.textContent = data.delivered_time;
                        var failureNote = card.querySelector('.failure-note');
                        var failureText = card.querySelector('.failure-reason-text');
                        var selectBox = card.querySelector('.order-status');
                        if (status === 'failed') {
                            failureText.textContent = reason + (details ? ' - ' + details : '');
                            failureNote.style.display = 'block';
                        } else {
                            failureNote.style.display = 'none';
                        }
                        if (status === 'delivered') {
                            if (selectBox) selectBox.disabled = true;
                        }
                    }
                } else {
                    alert(data.message || 'حدث خطأ أثناء التحديث.');
                    // Revert select visually if failed
                    var card = document.getElementById('order-card-' + id);
                    if (card) {
                        var selectBox = card.querySelector('.order-status');
                        if (selectBox) {
                            // Can reload or keep old value, simplest is reload
                            window.location.reload();
                        }
                    }
                }
            });
        }

        // حذف أوردر
        document.querySelectorAll('.admin-delete-btn').forEach(function (btn) {
            btn.onclick = function () {
                var id = this.getAttribute('data-order-id');
                if (confirm('هل أنت متأكد من حذف هذه الطلبية؟')) {
                    fetch('/admin/orders/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            var card = document.getElementById('order-card-' + id);
                            if (card) card.remove();
                        }
                    });
                }
            };
        });

        // إنشاء طلبية جديدة
        var createBtn = document.getElementById('create-order-btn');
        var newOrderOverlay = document.getElementById('new-order-overlay');
        var newOrderClose = document.getElementById('new-order-close');
        var newOrderConfirmBtn = document.getElementById('new-order-confirm-btn');

        if (createBtn && newOrderOverlay) {
            createBtn.onclick = function() { newOrderOverlay.classList.add('active'); };
            newOrderClose.onclick = function() { newOrderOverlay.classList.remove('active'); };

            newOrderConfirmBtn.onclick = function() {
                var payload = {
                    product_type: document.getElementById('new-order-type').value.trim(),
                    color: document.getElementById('new-order-color').value.trim(),
                    size: document.getElementById('new-order-size').value.trim(),
                    price: document.getElementById('new-order-price').value.trim(),
                    customer_name: document.getElementById('new-order-customer').value.trim(),
                    customer_phone: document.getElementById('new-order-phone').value.trim(),
                    customer_email: document.getElementById('new-order-email').value.trim(),
                    customer_address: document.getElementById('new-order-address').value.trim(),
                };

                if (!payload.product_type || !payload.price || !payload.customer_name || !payload.customer_phone || !payload.customer_address) {
                    document.getElementById('new-order-error').textContent = 'يرجى ملء جميع الحقول الإلزامية';
                    return;
                }

                fetch('{{ route('admin.orders.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        document.getElementById('new-order-error').textContent = data.message || 'حدث خطأ';
                    }
                });
            };
        }
    });
</script>
@endpush
