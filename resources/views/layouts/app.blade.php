<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'retal store')</title>
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;700&family=Cairo:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- زرار واتساب ثابت، ظاهر طول الوقت فوق الصفحة، لأي استفسار عام -->
    <a href="https://api.whatsapp.com/send?phone=201097921977" class="whatsapp-btn" target="_blank" onclick="fetch('{{ route('track.whatsapp') }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})">واتساب</a>
    
    <footer style="background-color: #2b2b2b; color: white; padding: 0 20px; padding-bottom: 10px; margin-top: 50px; font-family: 'Cairo', sans-serif;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 30px;">
            <div style="flex: 1; min-width: 250px;">
                <h3 style="color: #d15c72; margin-bottom: 20px; font-size: 24px;">ريتال ستور</h3>
                <p style="line-height: 1.6; font-size: 15px; color: #ccc;"> للملابس البيتي وتجهيز العروسه ( جملة الجملة ) </p>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <h3 style="color: #d15c72; margin-bottom: 20px; font-size: 20px;">تواصل معنا</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; gap: 15px;">
                    <li style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-phone-alt" style="color: #d15c72; font-size: 18px;"></i>
                        <a href="tel:01097921977" style="color: white; text-decoration: none; font-size: 16px; direction: ltr;">010 97921977</a>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px;">
                        <i class="fab fa-whatsapp" style="color: #25D366; font-size: 20px;"></i>
                        <a href="https://api.whatsapp.com/send?phone=201097921977" target="_blank" style="color: white; text-decoration: none; font-size: 16px; direction: ltr;">+20 10 97921977</a>
                    </li>
                </ul>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <h3 style="color: #d15c72; margin-bottom: 10px; font-size: 20px;">تابعنا على</h3>
                <div style="display: flex; gap: 15px;">
                    <a href="https://share.google/mnCYCndgzuIj9smFN" target="_blank" style="color: white; font-size: 24px; transition: color 0.3s;" onmouseover="this.style.color='#1877f2'" onmouseout="this.style.color='white'"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com/@retal.store4" target="_blank" style="color: white; font-size: 24px; transition: color 0.3s;" onmouseover="this.style.color='#ff0050'" onmouseout="this.style.color='white'"><i class="fab fa-tiktok"></i></a>
                    <a href="https://t.me/+m9UTbCmXr9o2Njlk" target="_blank" style="color: white; font-size: 24px; transition: color 0.3s;" onmouseover="this.style.color='#0088cc'" onmouseout="this.style.color='white'"><i class="fab fa-telegram-plane"></i></a>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #444; color: #aaa; font-size: 14px;">
            © {{ date('Y') }} ريتال ستور. جميع الحقوق محفوظة.
        </div>
    </footer>

    <script src="{{ asset('main.js') }}"></script>
    @stack('scripts')
</body>
</html>
