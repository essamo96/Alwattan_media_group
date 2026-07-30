@extends('frontend.layouts.master')
@section('title', $page->title)
@section('content')
<div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner innder-page" data-z-index="-100" data-appear-top-offset="600" data-parallax="scroll" data-image-src="{{ url('assets/site/images/slide.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tg-innerbannercontent">
                    <h1>{{ $page->title }}</h1>
                    <ol class="tg-breadcrumb">
                        <li><a href="javascript:void(0);">{{ __('site.home') }}</a></li>
                        <li>|</li>
                        <li class="tg-active">{{ $page->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="tg-sectionspace tg-haslayout ">
    <div class="container">
        <div class="tg-aboutus">
            <div class="row">
                <section class="tg-sectionspace tg-haslayout services">
                    <div class="container">
                        <div class="row">
                            {!!$page->details!!}
                        </div>
                        <div class="row">
                            <div class="tg-aboutus">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="tg-aboutus-head">
                                        <h1><span>قسم تطوير الأنظمة المكتبية والديسكتوب</h1>
                                    </div>
                                    <div class="tg-description">
                                        <p style="text-align: right;">o نظام إدارة التسهيلات البنكية (بانك إنفو)<br>
                                            o نظام الأرشفة ومتابعة المهمات (ديجي دوكس)<br>
                                            o نظام إدارة شئون الأعضاء<br>
                                            o نظام إدارة شئون الجامعات (ديجي كامباس)<br>
                                            o نظام فحص التوعية<br>
                                            o نظام المحاسب السحري<br>
                                            o نظام إدارة مكاتب الصرافة (مترو)<br>
                                            o نظام إدارة شئون المعلومات (إم آي إس)<br>
                                            .</p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-0">
                                    <img src="https://www.ukyas.tk/uploads/image/image_1664879497.jpg" class="img-fluid left-round-border" alt="image description">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="tg-aboutus">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-0">
                                    <img src="https://www.ukyas.tk/uploads/image/image_1664879497.jpg" class="img-fluid left-round-border" alt="image description">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="tg-aboutus-head">
                                        <h1><span>قسم تطوير الويب أبليكيشن</h1>
                                    </div>
                                    <div class="tg-description">
                                        <p style="text-align: right;">تنفرد شركة التقنيات الحديثة بتقديم نوع جديد من الخدمات للمؤسسات والشركات والأفراد التي تسعى دوما أن يكون لهم دور في تصميم وتطوير مواقعهم على الانترنت، حيث قام فريق من المبرمجين المتخصصين والذين يمتازون بدرجة عالية من المهارة والخبرة بتطوير أداة قوية وبناءة تتميز بالاستخدام الشيق والممتع لتصميم صفحات الانترنت الديناميكية والمدعومة بقواعد البيانات المطورة، والقادرة على التعامل مع عشرات الآف من المستخدمين المتزامنين مع توفير الدعم لعدد من اللغات تلقائياً.<br>
                                            .</p>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="tg-aboutus">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="tg-aboutus-head">
                                        <h1><span>قسم تطوير تطبيقات الموبايل</h1>
                                    </div>
                                    <div class="tg-description">
                                        <p>الكثير من مستخدمي الإنترنت يتصفحون الإنترنت عبر الهواتف الجوالة لذلك من المهم لأعمالك التجارية و لهويتكم الرقمية تصميم تطبيق جوال متناسق وجذاب لتكونوا اقرب الى عملائكم والأنسب من بين منافسيكم وللتواصل مع مستخدمي الإنترنت بشكل أسرع وأفضل.</p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-0">
                                    <img src="https://www.ukyas.tk/uploads/image/image_1664879497.jpg" class="img-fluid left-round-border" alt="image description">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
@stop
