@extends('admin.layout.master')

@section('title')
اضافة سلايدر جديد
@stop

@section('css')

@stop

@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('sliders.view') }}">ادارة الاسئلة الشائعة</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('sliders.add') }}">اضافة الاسئلة الشائعة</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> ادارة الاسئلة الشائعة
    <small></small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-puzzle"></i>اضافة الاسئلة الشائعة </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3">السؤال عربي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name_ar') }}" name="name_ar" id="name_ar" class="form-control" placeholder="السطر الاول عربي">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الجواب عربي</label>
                        <div class="col-md-6">
                            <textarea name="name1_ar" id="name1_ar" class="form-control" placeholder="الجواب عربي">{{ old('name1_ar') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">السؤال انجليزي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name_en') }}" name="name_en" id="name_en" class="form-control" placeholder="السطر الاول انجليزي">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الجواب انجليزي</label>
                        <div class="col-md-6">
                            <textarea name="name1_en" class="form-control" placeholder="الجواب انجليزي انجليزي">{{ old('name1_en') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">الحالة</label>
                        <div class="col-md-6">
                            <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;Enable&nbsp;" data-off-text="&nbsp;Disable&nbsp;" {{ old('status') == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    <a href="{{ route('faq.view') }}" type="button" class="btn default">الغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
<script type="text/javascript">
CKEDITOR.replace('name1_ar');
CKEDITOR.replace('name1_en');
</script>
@stop