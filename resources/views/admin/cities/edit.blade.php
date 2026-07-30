@extends('admin.layout.master')

@section('title')
تعديل مدينة
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
        <a href="{{ route('sliders.view') }}">ادارة المدن</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <strong> {{ $info->title }}</strong>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('sliders.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل مدينة</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> ادارة المدن
    <small></small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-puzzle"></i>تعديل مدينة </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3">المدينة عربي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->name_ar }}" name="name_ar" id="name_ar" class="form-control" placeholder="المدينة الاول">
                        </div>
                    </div>                  
                    <div class="form-group">
                        <label class="control-label col-md-3">المدينة انجليزي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->name_en }}" name="name_en" class="form-control" placeholder="المدينة انجليزي">
                        </div>
                    </div>                 
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">الحالة</label>
                    <div class="col-md-6">
                        <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;Enable&nbsp;" data-off-text="&nbsp;Disable&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">تعديل</button>
                    <a href="{{ route('cities.view') }}" type="button" class="btn default">الغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop