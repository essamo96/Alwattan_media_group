@extends('admin.layout.master')
@section('title')
تعديل قسم  
@stop
@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('properties_categories.view') }}">إدارة اقسام العقارات</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <strong> {{ $info->title }}</strong>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('properties_categories.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل قسم </a>
    </li>
</ul>
@stop
@section('page-title')
<h1 class="page-title"> اقسام العقارات
    <small>تعديل قسم</small>
</h1>
@stop
@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>تعديل قسم </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="tabbable-line boxless tabbable-reversed">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_0" data-toggle="tab"> البيانات الاساسية </a>
                        </li>
                        @foreach($languages as $item)
                        <li>
                            <a href="#tab_{{ $loop->iteration}}" data-toggle="tab">{{$item->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_0">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">الحالة</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">عنوان الرابط</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->slug }}" name="slug" class="form-control" placeholder="عنوان الرابط">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($languages as $item)

                        <div class="tab-pane" id="tab_{{ $loop->iteration }}">
                            <div class="form-group">
                                <label class="control-label col-md-3">عنوان القسم</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$info->translate($item->prefix)?$info->translate($item->prefix)->title:''}}" name="{{$item->prefix}}_title" class="form-control" placeholder="اسم السلسلة">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">الوصف</label>
                                <div class="col-md-6">
                                    <textarea name="{{$item->prefix}}_descs" id="{{$item->prefix}}_descs" class="form-control ckeditor" rows="3">{{$info->translate($item->prefix)?$info->translate($item->prefix)->descs:''}}</textarea>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    <a href="{{ route('properties_categories.view') }}" type="button" class="btn default">إلغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop