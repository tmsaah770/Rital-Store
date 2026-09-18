<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * عرض شاشة تسجيل الدخول
     */
    public function showLogin()
    {
        if (Auth::guard('employee')->check()) {
            $emp = Auth::guard('employee')->user();
            return $emp->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('admin.orders');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * عرض شاشة إنشاء حساب جديد (Register)
     */
    public function showRegister()
    {
        if (Auth::guard('employee')->check()) {
            $emp = Auth::guard('employee')->user();
            return $emp->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('admin.orders');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * معالجة تسجيل الحساب الجديد مع فاليديشن صارم
     */
    public function register(Request $request)
    {
        // تنظيف وتوحيد المدخلات قبل الفاليديشن
        $request->merge([
            'email' => strtolower(trim((string)$request->input('email'))),
            'phone' => preg_replace('/[^0-9]/', '', (string)$request->input('phone')),
            'name'  => strip_tags(trim((string)$request->input('name'))),
        ]);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'email'    => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    $parts = explode('@', $value);
                    if (count($parts) !== 2) {
                        $fail('صيغة البريد الإلكتروني غير صحيحة.');
                        return;
                    }
                    $domain = strtolower($parts[1]);

                    // حظر الإيميلات الوهمية والمؤقتة
                    $disposableDomains = [
                        'mailinator.com', 'tempmail.com', '10minutemail.com', 'guerrillamail.com',
                        'yopmail.com', 'sharklasers.com', 'dispostable.com', 'throwawaymail.com',
                        'temp-mail.org', 'fakeinbox.com', 'fakemailgenerator.com', 'trashmail.com'
                    ];

                    if (in_array($domain, $disposableDomains)) {
                        $fail('غير مسموح باستخدام بريد إلكتروني مؤقت أو وهمي. يرجى استخدام بريد حقيقي.');
                        return;
                    }

                    // التحقق من أن النطاق حقيقي وله سجلات MX / A لاستقبال البريد
                    if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                        $fail('نطاق البريد الإلكتروني غير موجود أو لا يستقبل رسائل. يرجى كتابة بريد إلكتروني حقيقي.');
                    }
                }
            ],
            'phone'    => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'      => 'من فضلك أدخل الاسم بالكامل.',
            'name.min'           => 'الاسم يجب ألا يقل عن 3 أحرف.',
            'name.max'           => 'الاسم طويل جداً.',
            'email.required'     => 'من فضلك أدخل البريد الإلكتروني.',
            'email.email'        => 'صيغة البريد الإلكتروني غير صحيحة أو النطاق غير موجود.',
            'email.unique'       => 'هذا البريد الإلكتروني مسجل مسبقاً، يمكنك تسجيل الدخول به.',
            'phone.required'     => 'من فضلك أدخل رقم الموبايل.',
            'phone.regex'        => 'رقم الموبايل يجب أن يكون 11 رقماً مصرياً صحيحاً يبدأ بـ (010, 011, 012, 015).',
            'phone.unique'       => 'رقم الموبايل هذا مسجل مسبقاً بالفعل.',
            'password.required'  => 'من فضلك أدخل كلمة المرور.',
            'password.min'       => 'كلمة المرور يجب ألا تقل عن 6 خانات.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $user = \App\Models\User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'تم إنشاء الحساب بنجاح!',
                'redirect' => route('home'),
            ]);
        }

        return redirect()->route('home')->with('success', 'مرحباً بك! تم إنشاء حسابك بنجاح.');
    }

    /**
     * معالجة تسجيل الدخول (يدعم الموظفين والمديرين من جدول employees والمستخدمين من جدول users)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));

        // أولاً: فحص إذا كان المستخدم موظفاً أو مديراً في جدول employees
        if (Auth::guard('employee')->attempt($credentials, true)) {
            $request->session()->regenerate();

            $emp = Auth::guard('employee')->user();
            $redirectUrl = $emp->isAdmin() ? route('admin.dashboard') : route('admin.orders');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => $redirectUrl,
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        // ثانياً: فحص إذا كان مستخدماً/عميلاً في جدول users
        if (Auth::guard('web')->attempt($credentials, true)) {
            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => route('home'),
                ]);
            }

            return redirect()->intended(route('home'));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ], 422);
        }

        return back()->withErrors([
            'login_error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ])->withInput($request->only('email'));
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
