<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول</title>
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <section class="login-box">
        <img id="logo-image" src="{{ asset('storage/logo.png') }}" alt="ريتال ستور">
        <h1 class="login-title">تسجيل دخول </h1>

        <form id="login-form" method="POST" action="{{ route('login.post') }}">
            @csrf
            <input type="email" name="email" id="admin-email" placeholder="البريد الإلكتروني" value="{{ old('email') }}" required>
            <input type="password" name="password" id="admin-password" placeholder="كلمة المرور" required>
            <button type="submit" class="login-btn" id="login-submit-btn">دخول</button>
        </form>

        <p id="login-error" class="login-error">
            @if($errors->has('login_error'))
                {{ $errors->first('login_error') }}
            @endif
        </p>

        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
            <p style="font-size: 13px; color: #666; font-family: 'Cairo', sans-serif; margin: 0;">
                ليس لديك حساب؟ 
                <a href="{{ route('register') }}" style="color: #8B2635; font-weight: bold; text-decoration: none;">إنشاء حساب</a>
            </p>
            <p style="margin-top: 12px; margin-bottom: 0;">
                <a href="{{ route('home') }}" style="font-size: 12px; color: #888; text-decoration: none; font-family: 'Cairo', sans-serif;">← الرجوع للمتجر</a>
            </p>
        </div>
    </section>

<script>
    // ربط نموذج تسجيل الدخول مع الباك إند
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('login-form');
        var errorEl = document.getElementById('login-error');
        var submitBtn = document.getElementById('login-submit-btn');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                errorEl.textContent = '';
                submitBtn.disabled = true;
                submitBtn.textContent = 'جاري التحقق...';

                var formData = new FormData(form);

                fetch('{{ route('login.post') }}', {
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
                    submitBtn.textContent = 'دخول';

                    if (res.ok && res.data.success) {
                        window.location.href = res.data.redirect;
                    } else {
                        errorEl.textContent = res.data.message || 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
                    }
                })
                .catch(function() {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'دخول';
                    errorEl.textContent = 'تعذر الاتصال بالسيرفر. يرجى المحاولة لاحقاً.';
                });
            });
        }
    });
</script>
</body>
</html>
