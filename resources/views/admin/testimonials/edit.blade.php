@extends('admin.layout.master')
@section('title')
تعديل مقولة
@stop
@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <a href="{{ route('testimonials.view') }}">قالوا عنا</a>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <strong> {{ $info->name }}</strong>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <a href="{{ route('testimonials.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل مقولة</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> الشركاء
    <small>تعديل مقولة</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>تعديل مقولة </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal">
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
                                    <label class="control-label col-md-3">الترتيب</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->p_order }}" name="p_order" id="p_order" class="form-control" placeholder="الترتيب">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">الصورة</label>
                                    <div class="col-md-5">
                                        <input id="thumbnail" value="{{ $info->image }}" class="form-control" type="text" name="image" readonly>
                                        <img id="holder" src="{{ url($info->image) }}" style="margin-top:15px;max-height:100px;">
                                    </div>
                                    <div class="col-md-1">
                                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> حدد صورة
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">الحالة</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($languages as $item)
                        <div class="tab-pane" id="tab_{{ $loop->iteration }}">
                            <div class="form-group">
                                <label class="control-label col-md-3">الاسم</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$info->translate($item->prefix)?$info->translate($item->prefix)->name:''}}" name="{{$item->prefix}}_name" class="form-control" placeholder="الاسم">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">العنوان</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$info->translate($item->prefix)?$info->translate($item->prefix)->title:''}}" name="{{$item->prefix}}_title" class="form-control" placeholder="العنوان">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">الوصف</label>
                                <div class="col-md-6">
                                    <textarea name="{{$item->prefix}}_descs" id="descs" class="form-control ckeditor" rows="3">{{$info->translate($item->prefix)?$info->translate($item->prefix)->descs:''}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                        <a href="{{ route('testimonials.view') }}" type="button" class="btn default">إلغاء</a>
                        {{ csrf_field() }}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
<script src="vendor/laravel-filemanager/js/lfm.js"></script>
<script type="text/javascript">
var domain = "{{ asset('/admin').'/file_manager' }}";
$('#lfm').filemanager('image', {prefix: domain});
</script>
@stop
