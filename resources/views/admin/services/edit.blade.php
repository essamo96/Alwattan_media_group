@extends('admin.layout.master')
@section('title')
تعديل  خدمة
@stop
@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <a href="{{ route('services.view') }}">إدارة  الخدمات</a>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <strong> {{ $info->title }}</strong>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <a href="{{ route('services.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل خدمة</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> الخدمات
    <small>تعديل خدمة</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>تعديل خدمة </div>
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
                                    <label class="control-label col-md-3">ايقونة الخدمة </label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->image }}" name="image" id="image" class="form-control" placeholder="ايقونة الخدمة">
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">رابط الخدمة </label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->url }}" name="url" id="url" class="form-control" placeholder="رابط الخدمة">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        @foreach($languages as $item)
                        <div class="tab-pane" id="tab_{{ $loop->iteration }}">
                            <div class="form-group">
                                <label class="control-label col-md-3">العنوان</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$info->translate($item->prefix)?$info->translate($item->prefix)->title:''}}" name="{{$item->prefix}}_title" class="form-control" placeholder="العنوان">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">التفاصيل</label>
                                <div class="col-md-6">
                                    <textarea name="{{$item->prefix}}_details"  class="form-control ckeditor" rows="3">{{$info->translate($item->prefix)?$info->translate($item->prefix)->details:''}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                        <a href="{{ route('services.view') }}" type="button" class="btn default">إلغاء</a>
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
<script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
<script>CKEDITOR.config.customConfig = "{{ asset('assets/admin/ckeditor/config.js') }}?v=2";</script>
<script type="text/javascript">
var domain = "{{ asset('/admin').'/file_manager' }}";
$('#lfm').filemanager('image', {prefix: domain});
</script>

@stop
