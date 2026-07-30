<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>تسجيل الدخول</title>
        <base href="{{ asset('/') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin RTL Theme #2 for " name="description" />
        <link rel="stylesheet" href="{{ asset('assets/admin/login.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;900&family=Shantell+Sans:ital,wght@0,300;0,400;0,500;1,300;1,600&display=swap" rel="stylesheet">
        <!---we had linked our css file----->
        <link rel="shortcut icon" href="{{ url('assets/front/images/favicon.ico')}}" /> </head>

</head>
<body>
    <div class="full-page">
        <div id='login-form'class='login-page' style="display:block">
            <div class="form-box" style="margin-top: 15%;">
                <div class='button-box' style="margin-left: auto;width: auto;">
                    <a href='{{ url('/')}}'>
                        <img src="{{ asset('assets/admin/layouts/layout2/logo.png') }}" alt="alhadath" style="display: block;margin: auto;" />
                    </a>
                </div>
                <form action="" id='login' class=" input-group-login login-form" method="post">
                    {{ csrf_field() }}
                    <input type='text'class='input-field'placeholder='اسم المستخدم' name="username" required>
                    <input type='password'class='input-field'placeholder='كلمة المرور' name="password" required>
                    <input type='checkbox'class='check-box' name="remember" value="1"><span>تذكر كلمة السر</span>
                    <button type='submit'class='submit-btn'>تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
