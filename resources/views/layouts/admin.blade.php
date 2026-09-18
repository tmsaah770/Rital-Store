<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم')</title>
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;700&family=Cairo:wght@400;500;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @yield('content')
    <script src="{{ asset('main.js') }}"></script>
    @stack('scripts')
</body>
</html>
