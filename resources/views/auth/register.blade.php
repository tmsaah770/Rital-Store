<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء حساب جديد - ريتال ستور</title>
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <section class="login-box">
        <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
        <h1 class="login-title">إنشاء حساب جديد</h1>

        <form id="register-form" method="POST" action="{{ route('register.post') }}">
            @csrf
            <input type="text" name="name" id="reg-name" placeholder="الاسم بالكامل" value="{{ old('name') }}" required autocomplete="name">
            <input type="email" name="email" id="reg-email" placeholder="البريد الإلكتروني" value="{{ old('email') }}" required autocomplete="email">
            <input type="tel" name="phone" id="reg-phone" placeholder="رقم الموبايل" value="{{ old('phone') }}" maxlength="11" required autocomplete="tel">
            <input type="password" name="password" id="reg-password" placeholder="كلمة المرور" required autocomplete="new-password">
            <input type="password" name="password_confirmation" id="reg-password-confirm" placeholder="تأكيد كلمة المرور" required autocomplete="new-password">
            <button type="submit" class="login-btn" id="reg-submit-btn">إنشاء الحساب</button>
        </form>

        <p id="register-error" class="login-error"></p>

        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
            <p style="font-size: 13px; color: #666; font-family: 'Cairo', sans-serif; margin: 0;">
                لديك حساب بالفعل؟ 
                <a href="{{ route('login') }}" style="color: #8B2635; font-weight: bold; text-decoration: none;">تسجيل الدخول</a>
            </p>
            <p style="margin-top: 12px; margin-bottom: 0;">
                <a href="{{ route('home') }}" style="font-size: 12px; color: #888; text-decoration: none; font-family: 'Cairo', sans-serif;">← الرجوع للمتجر</a>
            </p>
        </div>
    </section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('register-form');
        var errorEl = document.getElementById('register-error');
        var submitBtn = document.getElementById('reg-submit-btn');

        var nameInput = document.getElementById('reg-name');
        var emailInput = document.getElementById('reg-email');
        var phoneInput = document.getElementById('reg-phone');
        var passInput = document.getElementById('reg-password');
        var passConfInput = document.getElementById('reg-password-confirm');

        // منع إدخال أي حروف غير رقمية في رقم الهاتف
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                errorEl.textContent = '';

                // 1. تحقق الاسم
                var nameVal = nameInput.value.trim();
                if (nameVal.length < 3) {
                    errorEl.textContent = 'الاسم يجب ألا يقل عن 3 أحرف.';
                    nameInput.focus();
                    return;
                }

                // 2. تحقق الإيميل
                var emailVal = emailInput.value.trim();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailVal)) {
                    errorEl.textContent = 'يرجى كتابة بريد إلكتروني صحيح.';
                    emailInput.focus();
                    return;
                }

                // 3. تحقق رقم الموبايل المصري
                var phoneVal = phoneInput.value.trim();
                var egyptPhoneRegex = /^01[0125][0-9]{8}$/;
                if (!egyptPhoneRegex.test(phoneVal)) {
                    errorEl.textContent = 'رقم الموبايل يجب أن يكون 11 رقماً ويبدأ بـ (010, 011, 012, 015).';
                    phoneInput.focus();
                    return;
                }

                // 4. تحقق كلمة المرور
                if (passInput.value.length < 6) {
                    errorEl.textContent = 'كلمة المرور يجب ألا تقل عن 6 خانات.';
                    passInput.focus();
                    return;
                }

                // 5. تحقق تطابق تأكيد كلمة المرور
                if (passInput.value !== passConfInput.value) {
                    errorEl.textContent = 'تأكيد كلمة المرور غير متطابق.';
                    passConfInput.focus();
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'جاري التحقق وإنشاء الحساب...';

                var formData = new FormData(form);

                fetch('{{ route('register.post') }}', {
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
                    submitBtn.textContent = 'إنشاء الحساب';

                    if (res.ok && res.data.success) {
                        window.location.href = res.data.redirect;
                    } else if (res.status === 422 && res.data.errors) {
                        // عرض الخطأ الأول من السيرفر
                        var firstErrKey = Object.keys(res.data.errors)[0];
                        errorEl.textContent = res.data.errors[firstErrKey][0];
                        var targetInput = document.querySelector('[name="' + firstErrKey + '"]');
                        if (targetInput) targetInput.focus();
                    } else {
                        errorEl.textContent = res.data.message || 'حدث خطأ أثناء التسجيل، يرجى المحاولة لاحقاً.';
                    }
                })
                .catch(function() {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'إنشاء الحساب';
                    errorEl.textContent = 'تعذر الاتصال بالسيرفر. يرجى التحقق من اتصال الإنترنت.';
                });
            });
        }
    });
</script>
</body>
</html>
