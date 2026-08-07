<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الالتحاق ببرنامج التدريب التخصصي - {{ $mysettings->name_ar ?? 'الوطن' }}</title>
    <link rel="icon" href="{{ asset('assets/front/images/mediagrope.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap">
    <style>
        :root{
            --primary:#213478;
            --primary-dark:#152350;
            --accent:#c0212f;
            --accent-dark:#96131e;
            --bg:#f4f6fb;
            --surface:#ffffff;
            --border:#e2e6f0;
            --text:#1c2340;
            --muted:#667085;
            --success:#1a9e5c;
            --danger:#c0212f;
            --radius:14px;
        }
        *{box-sizing:border-box;}
        body{
            margin:0;
            font-family:'Cairo', 'Tahoma', sans-serif;
            background:var(--bg);
            color:var(--text);
            line-height:1.7;
        }
        a{color:inherit;}
        .container{max-width:980px;margin:0 auto;padding:0 20px;}

        /* HERO */
        .hero{
            color:#fff;
            padding:64px 0 110px;
            position:relative;
            overflow:hidden;
            background-image:
                linear-gradient(135deg, rgba(33,52,120,.80) 0%, rgba(21,35,80,.85) 100%),
                url('{{ asset("assets/admin/damianlopjus-cameras-6839248.jpg") }}');
            background-repeat:no-repeat, no-repeat;
            background-position:center, center 62%;
            background-size:cover, cover;
            background-blend-mode:multiply, normal;
        }
        .hero::after{
            content:"";
            position:absolute;
            inset:0;
            background:radial-gradient(circle at 85% 15%, rgba(255,255,255,.14), transparent 55%);
        }
        /* Cloud-like dissolve where the hero meets the form card */
        .hero-fade{
            position:absolute;
            left:0;right:0;bottom:0;height:140px;
            background:linear-gradient(to bottom, rgba(244,246,251,0) 0%, var(--bg) 92%);
            z-index:1;
            pointer-events:none;
        }
        .hero-inner{position:relative;z-index:2;text-align:center;}
        .hero-badge{
            display:inline-flex;align-items:center;gap:8px;
            background:rgba(255,255,255,.12);
            border:1px solid rgba(255,255,255,.25);
            padding:6px 16px;border-radius:999px;
            font-size:13px;font-weight:600;margin-bottom:18px;
            backdrop-filter:blur(4px);
        }
        .hero-badge svg{flex-shrink:0;}
        .hero-logo{display:block;margin:0 auto 18px;height:56px;width:auto;}
        .hero h1{font-size:clamp(22px,5vw,34px);font-weight:800;margin:0 0 14px;}
        .hero p{font-size:clamp(14px,2.4vw,16px);color:rgba(255,255,255,.88);max-width:680px;margin:0 auto;}

        /* FORM CARD */
        .wrap{max-width:900px;margin:-38px auto 60px;padding:0 20px;position:relative;z-index:2;}
        .card{
            background:var(--surface);
            border-radius:var(--radius);
            box-shadow:0 10px 40px rgba(20,30,70,.12);
            overflow:hidden;
        }
        .card-header{padding:26px 32px 6px;}
        .card-header-branded{text-align:center;padding-top:32px;}
        .card-logo{height:54px;width:auto;margin:0 auto 14px;display:block;}
        .card-header h2{margin:0 0 4px;font-size:20px;font-weight:800;}
        .card-header p{margin:0;color:var(--muted);font-size:14px;}
        form{padding:10px 32px 32px;}

        @media (max-width:640px){
            .hero{padding:48px 0 130px;}
            .wrap{margin-top:-60px;padding:0 14px;}
            .card-header{padding:22px 18px 4px;}
            form{padding:8px 18px 26px;}
            .container{padding:0 14px;}
        }
        @media (max-width:400px){
            .hero-badge{font-size:12px;padding:6px 12px;}
        }

        .section-title{
            font-size:15px;font-weight:700;color:var(--primary);
            margin:26px 0 14px;padding-bottom:8px;border-bottom:2px solid var(--border);
            display:flex;align-items:center;gap:8px;
        }
        .section-title:first-of-type{margin-top:8px;}

        .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px 20px;}
        @media (max-width:640px){.grid{grid-template-columns:1fr;}}

        .field{display:flex;flex-direction:column;gap:6px;}
        .field label{font-size:13.5px;font-weight:600;color:var(--text);}
        .field label .req{color:var(--accent);margin-inline-start:2px;}
        .field input,.field select{
            appearance:none;
            border:1.5px solid var(--border);
            border-radius:10px;
            padding:12px 14px;
            font-size:15px;
            font-family:inherit;
            background:#fbfcfe;
            color:var(--text);
            transition:border-color .18s ease, box-shadow .18s ease, background .18s ease;
            min-height:46px;
        }
        .field input:hover,.field select:hover{border-color:#c7cfe4;}
        .field input:focus,.field select:focus{
            outline:none;border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(33,52,120,.14);
            background:#fff;
        }
        .field small.hint{color:var(--muted);font-size:12px;}
        .field.has-error input,.field.has-error select{
            border-color:var(--danger);
            background:#fff6f6;
        }
        .field .error-msg{color:var(--danger);font-size:12.5px;display:none;align-items:center;gap:4px;}
        .field.has-error .error-msg{display:flex;}

        .submit-row{margin-top:30px;display:flex;flex-direction:column;align-items:center;gap:10px;}
        .btn-submit{
            width:100%;max-width:340px;
            background:var(--accent);
            color:#fff;border:none;
            padding:15px 20px;border-radius:12px;
            font-size:16px;font-weight:700;
            cursor:pointer;
            transition:background .2s ease, transform .1s ease, box-shadow .2s ease;
            box-shadow:0 6px 18px rgba(192,33,47,.28);
        }
        .btn-submit:hover{background:var(--accent-dark);}
        .btn-submit:active{transform:translateY(1px);}
        .btn-submit:disabled{opacity:.65;cursor:not-allowed;}
        .submit-note{font-size:12.5px;color:var(--muted);text-align:center;}

        .alert{
            margin:20px 32px 0;padding:14px 16px;border-radius:10px;
            font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;
        }
        .alert-success{background:#e9f9f1;color:var(--success);border:1px solid #b9ecd2;}
        .alert-danger{background:#fdecec;color:var(--danger);border:1px solid #f6c2c2;}

        /* SUCCESS STATE */
        .success-screen{
            text-align:center;padding:60px 32px;
        }
        .success-icon{
            width:72px;height:72px;border-radius:50%;
            background:#e9f9f1;color:var(--success);
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 20px;
        }
        .success-screen h2{font-size:22px;margin:0 0 10px;}
        .success-screen p{color:var(--muted);max-width:480px;margin:0 auto;}

        .notes-box{
            text-align:start;max-width:560px;margin:28px auto 0;
            background:#f7f8fc;border:1px solid var(--border);
            border-inline-start:4px solid var(--primary);
            border-radius:12px;padding:18px 20px;
        }
        .notes-title{
            display:flex;align-items:center;gap:8px;
            font-weight:700;font-size:14.5px;color:var(--primary);
            margin-bottom:10px;
        }
        .notes-list{margin:0;padding-inline-start:20px;display:flex;flex-direction:column;gap:8px;}
        .notes-list li{color:var(--text);font-size:13.5px;line-height:1.75;}

        footer{text-align:center;color:var(--muted);font-size:13px;padding:20px 0 40px;}
    </style>
</head>
<body>

    <div class="hero">
        <div class="container hero-inner">
            <span class="hero-badge" title="{{ isset($partners) && count($partners) ? 'بالشراكة مع ' . $partners->pluck('name')->implode('، ') : 'تسجيل الالتحاق' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" fill="currentColor"/>
                </svg>
                تسجيل الالتحاق ببرنامج التدريب التخصصي
            </span>
            <h1>استمارة التسجيل في برنامج التدريب</h1>
            <p>ندعو الراغبين بالمشاركة إلى تعبئة الاستمارة المرفقة للتقدم والمنافسة على فرصة الالتحاق ببرنامج التدريب التخصصي، وفق المعايير والشروط المعتمدة.</p>
        </div>
        <div class="hero-fade"></div>
    </div>

    <div class="wrap">
        <div class="card">

            @if(session('registered'))
            <div class="success-screen">
                <div class="success-icon">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h2>تم إرسال طلبك بنجاح</h2>
                <p>{{ session('success', 'شكراً لتسجيلك، سيتم التواصل معك في حال قبول طلبك ضمن معايير برنامج التدريب التخصصي.') }}</p>

                <div class="notes-box">
                    <div class="notes-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 16.5h.01M10.29 3.86l-8.02 13.9A1.5 1.5 0 0 0 3.53 20h16.94a1.5 1.5 0 0 0 1.26-2.24l-8.02-13.9a1.5 1.5 0 0 0-2.42 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        ملاحظات هامة
                    </div>
                    <ul class="notes-list">
                        <li>تعبئة نموذج التسجيل لا تعني القبول النهائي، ويتم اختيار المشاركين وفق معايير البرنامج.</li>
                        <li>سيتم التواصل مع المقبولين لاستكمال إجراءات المشاركة وإبلاغهم بموعد ومكان انعقاد التدريب.</li>
                        <li>عدد المقاعد محدود وستُمنح الأولوية للطلبات المستوفية للشروط والمقدمة خلال فترة التسجيل.</li>
                        <li>سيتم تخصيص 50% من المقاعد للإناث، تعزيزاً لمبدأ تكافؤ الفرص والمساواة.</li>
                        <li>سيحصل المشاركون الذين يجتازون متطلبات البرنامج بنجاح على شهادة معتمدة من المؤسسة.</li>
                    </ul>
                </div>
            </div>
            @else

            <div class="card-header card-header-branded">
                <img src="{{ asset('assets/front/images/mediagrope.png') }}" alt="{{ $mysettings->name_ar ?? 'الوطن' }}" class="card-logo">
                <h2>بيانات المتقدم</h2>
                <p>جميع الحقول أدناه مطلوبة، يرجى تعبئتها بدقة.</p>
            </div>

            @if(session('danger'))
            <div class="alert alert-danger">{{ session('danger') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">يرجى تصحيح الحقول المشار إليها أدناه.</div>
            @endif

            <form id="registration_form" method="POST" action="{{ route('course_registration.register') }}" novalidate>
                @csrf

                <div class="section-title">البيانات الشخصية</div>
                <div class="grid">
                    <div class="field {{ $errors->has('full_name') ? 'has-error' : '' }}">
                        <label>الاسم رباعي <span class="req">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="مثال: محمد أحمد خالد الوطن" required minlength="6">
                        <span class="error-msg">{{ $errors->first('full_name') ?: 'يرجى إدخال الاسم الرباعي كاملاً' }}</span>
                    </div>
                    <div class="field {{ $errors->has('national_id') ? 'has-error' : '' }}">
                        <label>رقم الهوية <span class="req">*</span></label>
                        <input type="text" inputmode="numeric" name="national_id" value="{{ old('national_id') }}" placeholder="9 - 10 أرقام" required pattern="[0-9]{9,10}">
                        <span class="error-msg">{{ $errors->first('national_id') ?: 'رقم الهوية يجب أن يتكون من 9 إلى 10 أرقام' }}</span>
                    </div>
                    <div class="field {{ $errors->has('gender') ? 'has-error' : '' }}">
                        <label>الجنس <span class="req">*</span></label>
                        <select name="gender" required>
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>اختر الجنس</option>
                            <option value="male" {{ old('gender')=='male' ? 'selected':'' }}>ذكر</option>
                            <option value="female" {{ old('gender')=='female' ? 'selected':'' }}>أنثى</option>
                        </select>
                        <span class="error-msg">{{ $errors->first('gender') ?: 'يرجى اختيار الجنس' }}</span>
                    </div>
                    <div class="field {{ $errors->has('birth_date') ? 'has-error' : '' }}">
                        <label>تاريخ الميلاد <span class="req">*</span></label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required max="{{ date('Y-m-d', strtotime('-16 years')) }}">
                        <span class="error-msg">{{ $errors->first('birth_date') ?: 'يرجى إدخال تاريخ ميلاد صحيح' }}</span>
                    </div>
                    <div class="field {{ $errors->has('birth_place') ? 'has-error' : '' }}">
                        <label>مكان الميلاد <span class="req">*</span></label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" required>
                        <span class="error-msg">{{ $errors->first('birth_place') ?: 'يرجى إدخال مكان الميلاد' }}</span>
                    </div>
                    <div class="field {{ $errors->has('nationality') ? 'has-error' : '' }}">
                        <label>الجنسية <span class="req">*</span></label>
                        <input type="text" name="nationality" value="{{ old('nationality', 'فلسطينية') }}" required>
                        <span class="error-msg">{{ $errors->first('nationality') ?: 'يرجى إدخال الجنسية' }}</span>
                    </div>
                    <div class="field {{ $errors->has('marital_status') ? 'has-error' : '' }}">
                        <label>الحالة الاجتماعية <span class="req">*</span></label>
                        <select name="marital_status" required>
                            <option value="" disabled {{ old('marital_status') ? '' : 'selected' }}>اختر الحالة الاجتماعية</option>
                            <option value="single" {{ old('marital_status')=='single'?'selected':'' }}>أعزب</option>
                            <option value="married" {{ old('marital_status')=='married'?'selected':'' }}>متزوج</option>
                            <option value="divorced" {{ old('marital_status')=='divorced'?'selected':'' }}>مطلق</option>
                            <option value="widowed" {{ old('marital_status')=='widowed'?'selected':'' }}>أرمل</option>
                        </select>
                        <span class="error-msg">{{ $errors->first('marital_status') ?: 'يرجى اختيار الحالة الاجتماعية' }}</span>
                    </div>
                    <div class="field {{ $errors->has('current_address') ? 'has-error' : '' }}">
                        <label>العنوان الحالي <span class="req">*</span></label>
                        <input type="text" name="current_address" value="{{ old('current_address') }}" required>
                        <span class="error-msg">{{ $errors->first('current_address') ?: 'يرجى إدخال العنوان الحالي' }}</span>
                    </div>
                    <div class="field {{ $errors->has('mobile') ? 'has-error' : '' }}">
                        <label>رقم الجوال <span class="req">*</span></label>
                        <input type="tel" dir="ltr" name="mobile" value="{{ old('mobile') }}" placeholder="05XXXXXXXX" required pattern="^05[0-9]{8}$">
                        <span class="error-msg">{{ $errors->first('mobile') ?: 'رقم الجوال يجب أن يكون بصيغة 05XXXXXXXX' }}</span>
                    </div>
                    <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label>البريد الإلكتروني <span class="req">*</span></label>
                        <input type="email" dir="ltr" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
                        <span class="error-msg">{{ $errors->first('email') ?: 'يرجى إدخال بريد إلكتروني صحيح' }}</span>
                    </div>
                </div>

                <div class="section-title">البيانات الأكاديمية والمهنية</div>
                <div class="grid">
                    <div class="field {{ $errors->has('general_specialization') ? 'has-error' : '' }}">
                        <label>التخصص العام <span class="req">*</span></label>
                        <input type="text" name="general_specialization" value="{{ old('general_specialization') }}" required>
                        <span class="error-msg">{{ $errors->first('general_specialization') ?: 'يرجى إدخال التخصص العام' }}</span>
                    </div>
                    <div class="field {{ $errors->has('specific_specialization') ? 'has-error' : '' }}">
                        <label>التخصص الدقيق <span class="req">*</span></label>
                        <input type="text" name="specific_specialization" value="{{ old('specific_specialization') }}" required>
                        <span class="error-msg">{{ $errors->first('specific_specialization') ?: 'يرجى إدخال التخصص الدقيق' }}</span>
                    </div>
                    <div class="field {{ $errors->has('university') ? 'has-error' : '' }}">
                        <label>الجامعة <span class="req">*</span></label>
                        <input type="text" name="university" value="{{ old('university') }}" required>
                        <span class="error-msg">{{ $errors->first('university') ?: 'يرجى إدخال اسم الجامعة' }}</span>
                    </div>
                    <div class="field {{ $errors->has('graduation_year') ? 'has-error' : '' }}">
                        <label>سنة التخرج <span class="req">*</span></label>
                        <input type="number" name="graduation_year" value="{{ old('graduation_year') }}" min="1970" max="{{ date('Y') }}" required>
                        <span class="error-msg">{{ $errors->first('graduation_year') ?: 'يرجى إدخال سنة تخرج صحيحة' }}</span>
                    </div>
                    <div class="field {{ $errors->has('gpa') ? 'has-error' : '' }}">
                        <label>المعدل <span class="req">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="gpa" value="{{ old('gpa') }}" placeholder="مثال: 85.50" required>
                        <span class="error-msg">{{ $errors->first('gpa') ?: 'يرجى إدخال المعدل التراكمي' }}</span>
                    </div>
                    <div class="field {{ $errors->has('employer') ? 'has-error' : '' }}">
                        <label>جهة العمل <span class="req">*</span></label>
                        <input type="text" name="employer" value="{{ old('employer') }}" placeholder="اكتب 'لا يوجد' في حال عدم العمل" required>
                        <small class="hint">في حال عدم العمل، يرجى كتابة "لا يوجد"</small>
                        <span class="error-msg">{{ $errors->first('employer') ?: 'يرجى إدخال جهة العمل أو كتابة لا يوجد' }}</span>
                    </div>
                </div>

                <div class="submit-row">
                    <button type="submit" class="btn-submit" id="submit_btn">إرسال طلب التسجيل</button>
                    <span class="submit-note">بالضغط على إرسال، أنت تقر بصحة البيانات المدخلة أعلاه.</span>
                </div>
            </form>
            @endif
        </div>
    </div>

    <footer>© {{ date('Y') }} {{ $mysettings->name_ar ?? 'الوطن' }} - جميع الحقوق محفوظة</footer>

    <script>
        (function () {
            var form = document.getElementById('registration_form');
            if (!form) {
                return;
            }

            var mobilePattern = /^05[0-9]{8}$/;
            var idPattern = /^[0-9]{9,10}$/;

            function setError(field, message) {
                field.classList.add('has-error');
                var msg = field.querySelector('.error-msg');
                if (msg && message) {
                    msg.textContent = message;
                }
            }

            function clearError(field) {
                field.classList.remove('has-error');
            }

            function validateField(input) {
                var field = input.closest('.field');
                if (!field) {
                    return true;
                }
                var valid = input.checkValidity();

                if (valid && input.name === 'mobile') {
                    valid = mobilePattern.test(input.value);
                }
                if (valid && input.name === 'national_id') {
                    valid = idPattern.test(input.value);
                }

                if (valid) {
                    clearError(field);
                } else {
                    setError(field);
                }
                return valid;
            }

            form.querySelectorAll('input, select').forEach(function (input) {
                input.addEventListener('blur', function () {
                    validateField(input);
                });
            });

            form.addEventListener('submit', function (e) {
                var isValid = true;
                form.querySelectorAll('input, select').forEach(function (input) {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    var firstError = form.querySelector('.has-error');
                    if (firstError) {
                        firstError.scrollIntoView({behavior: 'smooth', block: 'center'});
                    }
                    return;
                }

                var btn = document.getElementById('submit_btn');
                btn.disabled = true;
                btn.textContent = 'جاري الإرسال...';
            });
        })();
    </script>
</body>
</html>
