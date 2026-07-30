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
        <a href="{{ route('sliders.view') }}">ادارة السلايدات</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('sliders.add') }}">اضافة سلايدر جديد</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> ادارة السلايدات
    <small></small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-puzzle"></i>اضافة سلايدر جديد </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3">السطر الاول </label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name') }}" name="name" id="name" class="form-control" placeholder="السطر الاول">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">السطر الثاني </label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name1') }}" name="name1" id="name1" class="form-control" placeholder="السطر الثاني">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">السطر الثالث </label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name2') }}" name="name2" id="name2" class="form-control" placeholder="السطر الثالث">
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label col-md-3">اللغة</label>
                        <div class="col-md-6">
                            <select name="language" id="category_id" class="form-control">
                                <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>عربي</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الرابط</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('link') }}" name="link" id="link" class="form-control" placeholder="Link">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">عرض النص</label>
                        <div class="col-md-6">
                            <input type="checkbox" value="1" name="txt_status" class="make-switch" data-on-text="&nbsp;Enable&nbsp;" data-off-text="&nbsp;Disable&nbsp;" {{ old('txt_status') == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الحالة</label>
                        <div class="col-md-6">
                            <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;Enable&nbsp;" data-off-text="&nbsp;Disable&nbsp;" {{ old('status') == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الصورة</label>
                        <div class="col-md-6">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file" name="image"> </span>
                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    <a href="{{ route('sliders.view') }}" type="button" class="btn default">الغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')

@stop

@section('modals')

@stop
