<div style="margin: 0;direction: rtl;">
    <p>
        <a href="{{ url('/')}}"><img  src="{{url('assets/site/images/logo.svg') }}" alt="logo"></a>
    </p>
    <h1 style="background-color: #f1f1f1;line-height: 90px;text-align: center;color: #cc3399;">إعادة تعيين كلمة المرور</h1>
    <p>يبدو انك نسيت كلمة المرور الخاصة بك لمنصة المعلم في موقع العربية للجميع. الرجاء النقر علي الرابط التالي <a href="{{ route('teacher.reset.password',['token' => $token]) }}">استعادة كلمة المرور</a> لإعادة ضبط كلمة المرور. </p>
    <p> اذا لم تطلب إعادة تعيين لكلمة المرور يمكنك تجاهل هذا البريد الإلكتروني. </p>


    <h3 style="color: #a6a6a6;">
        بحاجة مساعدة؟ 
    </h3>
    <p>
        رضاك محط اهتمامنا، لا تتردد في التواصل معنا على الايميل <a href="mail:contact@arabicforall.com" class="mail">contact@arabicforall.com</a> او الرقم إذا كان لديك أية استفسار حول طلبك. 
    </p>
    <p>
        من دواعي سرور قسم خدمة العملاء تقديم المساعدة لك.
    </p>
    <div class="divder" style=" border-bottom: 1px solid #cc3399;margin: 20px 0;"></div>
    <div class="footer">

        <p style="color: #939393; text-align: center;">
            All rights reserved. Copyright © {{ date('Y')}} Arabic For All.
        </p>
    </div>
</div>
