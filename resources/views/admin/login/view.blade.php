<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8" />
        <title>تسجيل الدخول - لوحة التحكم</title>
        <base href="{{ asset('/') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="robots" content="noindex, nofollow" />
        <link rel="shortcut icon" type="image/png" href="{{ asset('assets/front/images/mediagrope.png') }}" />
        <link rel="apple-touch-icon" href="{{ asset('assets/front/images/mediagrope.png') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/admin/global/plugins/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/login.css?v=2') }}">
    </head>

    <body>
        <div class="full-page">
            <div id="login-form" class="login-page">
                <div class="form-box">
                    <div class="brand-box">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/front/images/mediagrope.png') }}" alt="Media Group" />
                        </a>
                    </div>

                    <h1 class="form-title">لوحة التحكم</h1>
                    <p class="form-subtitle">الرجاء إدخال بيانات الدخول للمتابعة</p>

                    @if(session('danger'))
                        <div class="login-alert" role="alert">
                            <i class="fa fa-exclamation-circle"></i>
                            <span>اسم المستخدم أو كلمة المرور غير صحيحة</span>
                        </div>
                    @endif

                    <form action="{{ url('admin/login') }}" id="login" class="login-form" method="post">
                        {{ csrf_field() }}

                        <div class="field">
                            <i class="fa fa-user field-icon"></i>
                            <input type="text" class="input-field" placeholder="اسم المستخدم" name="username"
                                   value="{{ old('username') }}" autocomplete="username" autofocus required>
                        </div>

                        <div class="field">
                            <i class="fa fa-lock field-icon"></i>
                            <input type="password" class="input-field" placeholder="كلمة المرور" name="password"
                                   autocomplete="current-password" required>
                        </div>

                        <label class="remember">
                            <input type="checkbox" class="check-box" name="remember" value="1">
                            <span>تذكرني</span>
                        </label>

                        <button type="submit" class="submit-btn">تسجيل الدخول</button>
                    </form>

                    <a href="{{ url('/') }}" class="back-link">&larr; العودة إلى الموقع</a>
                </div>
            </div>
        </div>
    </body>
</html>
