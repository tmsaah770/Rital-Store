@extends('layouts.app')

@section('title', 'إتمام الطلب - ريتال ستور')

@section('content')
    <div style="background-color: var(--card-bg); border-radius: 14px; box-shadow: var(--card-shadow); max-width: 600px; margin: 40px auto 40px auto; position: relative; padding: 40px 20px; min-height: 60vh; text-align: center; display: block;">
        <a href="{{ route('cart.index') }}" style="position: absolute; top: 15px; right: 20px; font-size: 30px; color: var(--text-secondary); text-decoration: none; font-weight: bold; line-height: 1; transition: color 0.3s;" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-secondary)'">×</a>

        <div style="background: var(--card-bg); padding: 10px; border-radius: 16px;">
            <h2 class="dashboard-title" style="font-size: 24px; font-weight: bold; margin-bottom: 30px; color: var(--text-primary);">إتمام الطلب</h2>
            <form id="checkout-form" method="POST" action="{{ route('order.submit') }}" style="max-width: 100%; margin: 0 auto; text-align: right;">
                @csrf
                
                <h3 class="checkout-section-title" style="font-size: 16px; margin-bottom: 10px; text-align: center;">بيانات التواصل</h3>
                <input type="tel" name="phone" id="checkout-phone" class="input" placeholder="رقم الموبايل" required style="margin-bottom: 25px; text-align: right;">
                
                <h3 class="checkout-section-title" style="font-size: 16px; margin-bottom: 10px; text-align: center;">عنوان التوصيل</h3>
                <input type="text" name="name" id="checkout-name" class="input" placeholder="الاسم بالكامل" required style="margin-bottom: 15px; text-align: right;">
                <input type="text" name="address" id="checkout-address" class="input" placeholder="العنوان بالتفصيل اسم القرية او المركز" required style="margin-bottom: 15px; text-align: right;">
                
                <select name="city" id="checkout-city" class="input" required style="margin-bottom: 25px; text-align: right; direction: rtl; appearance: auto;">
                    <option value="" disabled selected>اختر المحافظة</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov->name }}" data-cost="{{ $gov->shipping_cost }}" data-days="{{ $gov->delivery_days }}">
                            {{ $gov->name }}
                        </option>
                    @endforeach
                </select>
                
                <h3 class="checkout-section-title" style="font-size: 16px; margin-bottom: 10px; text-align: center;">طريقة الدفع</h3>
                <div class="payment-option selected" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 2px solid var(--primary); border-radius: 10px; background: var(--primary-soft); color: var(--primary); font-weight: bold;">
                    <span>✓</span>
                    <span>الدفع عند الاستلام (كاش)</span>
                </div>
                
                <!-- تفاصيل الفاتورة -->
                <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; margin-bottom: 25px; border: 1px solid var(--card-border);">
                    <h3 style="font-size: 16px; margin-bottom: 15px; text-align: center; color: var(--text-primary);">تفاصيل الفاتورة</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--text-secondary); font-size: 15px;">
                        <span id="summary-items-total" data-base-total="{{ $total }}">{{ number_format($total, 0) }} جنيه</span>
                        <span>مجموع المنتجات ({{ collect($cart)->sum('quantity') }} قطع)</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--text-secondary); font-size: 15px;">
                        <span id="summary-shipping-cost">سيتم تحديده</span>
                        <span>مصاريف الشحن <small id="summary-delivery-time" style="color: var(--primary);"></small></span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--card-border); font-size: 18px; font-weight: bold; color: var(--primary);">
                        <span id="summary-final-total">{{ number_format($total, 0) }} جنيه</span>
                        <span>الإجمالي الكلي</span>
                    </div>
                </div>
                
                <p id="checkout-error" class="login-error" style="text-align: center;"></p>
                <button type="submit" class="order-btn" id="checkout-submit-btn" style="width: 100%; font-size: 18px; padding: 15px 0; border-radius: 8px;">تأكيد الطلب</button>
            </form>
        </div>
    </div>

    <!-- نافذة نجاح الطلب (Success Modal) -->
    <div class="checkout-overlay" id="success-overlay">
        <div class="checkout-box success-box" style="text-align: center;">
            <div class="success-icon" style="font-size: 50px; color: var(--success); margin-bottom: 20px;">✓</div>
            <h2 class="success-title" style="font-size: 24px; color: var(--text-primary); margin-bottom: 10px;">تم تأكيد الطلب بنجاح</h2>
            <p class="success-text" style="color: var(--text-secondary); margin-bottom: 15px;">هيتم التواصل معاكِ قريبًا لتأكيد التفاصيل والتوصيل.</p>
            <p class="success-order-id" id="success-order-summary" style="font-weight: bold; color: var(--primary); margin-bottom: 25px;"></p>
            <a href="{{ route('home') }}" class="order-btn" style="display: block; text-decoration: none; text-align: center; padding: 12px 0;">العودة للمتجر</a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var citySelect = document.getElementById('checkout-city');
        var shippingCostEl = document.getElementById('summary-shipping-cost');
        var deliveryTimeEl = document.getElementById('summary-delivery-time');
        var finalTotalEl = document.getElementById('summary-final-total');
        var itemsTotalEl = document.getElementById('summary-items-total');
        
        var baseTotal = parseFloat(itemsTotalEl.getAttribute('data-base-total'));

        // تحديث الحسابات عند تغيير المحافظة
        citySelect.addEventListener('change', function() {
            var selectedOption = citySelect.options[citySelect.selectedIndex];
            var cost = parseFloat(selectedOption.getAttribute('data-cost') || 0);
            var days = selectedOption.getAttribute('data-days') || '';

            if (cost > 0) {
                shippingCostEl.textContent = cost + ' جنيه';
                deliveryTimeEl.textContent = '(تصل خلال ' + days + ' أيام)';
                
                var finalTotal = baseTotal + cost;
                finalTotalEl.textContent = finalTotal.toLocaleString() + ' جنيه';
            } else {
                shippingCostEl.textContent = 'مجاناً';
                deliveryTimeEl.textContent = '';
                finalTotalEl.textContent = baseTotal.toLocaleString() + ' جنيه';
            }
        });

        var form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var errorEl = document.getElementById('checkout-error');
                errorEl.textContent = '';

                var submitBtn = document.getElementById('checkout-submit-btn');
                submitBtn.disabled = true;
                submitBtn.textContent = 'جاري التأكيد...';

                var formData = new FormData(form);

                fetch('{{ route('order.submit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    });
                })
                .then(function(res) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'تأكيد الطلب';

                    if (res.ok && res.data.success) {
                        var successSummary = document.getElementById('success-order-summary');
                        if (successSummary) {
                            successSummary.textContent = 'رقم الطلب: ' + res.data.order_number;
                        }
                        document.getElementById('success-overlay').classList.add('active');
                        form.reset();
                    } else {
                        errorEl.textContent = res.data.message || 'حدث خطأ أثناء حفظ الطلب، يرجى المحاولة ثانية.';
                    }
                })
                .catch(function(err) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'تأكيد الطلب';
                    errorEl.textContent = 'تعذر الاتصال بالسيرفر. يرجى التحقق من الاتصال.';
                });
            });
        }
    });
</script>
@endpush
