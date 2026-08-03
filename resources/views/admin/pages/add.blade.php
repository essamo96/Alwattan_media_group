@extends('admin.layout.master')

@section('title')
    إضافة صفحة
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
            <a href="{{ route('categories.view') }}">إدارة الصفحات</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('categories.add') }}">إضافة صفحة جديدة</a>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> الصفحات
        <small>إضافة صفحة</small>
    </h1>
@stop

@section('page-content')
    <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-grid"></i>إضافة صفحة جديدة
            </div>
        </div>
        <div class="portlet-body form">
            @include('admin.layout.error')
            <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-body">
                    <form role="form" method="post" action="" class="form-horizontal">
                        <div class="form-body">
                            <div class="tabbable-line boxless tabbable-reversed">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_0" data-toggle="tab"> البيانات الاساسية </a>
                                    </li>
                                    @foreach ($languages as $item)
                                        <li>
                                            <a href="#tab_{{ $loop->iteration }}" data-toggle="tab">{{ $item->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_0">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">اسم مخصص لجوجل</label>
                                                <div class="col-md-6">
                                                    <input type="text" value="{{ old('slug') }}" name="slug"
                                                        id="validationCustom03" class="form-control"
                                                        placeholder="اسم مخصص لجوجل">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">الكلمات الدلالية</label>
                                                <div class="col-md-6">
                                                    <input type="text" value="{{ old('tags') }}" name="tags"
                                                        id="validationCustom04" class="form-control input-large"
                                                        data-role="tagsinput" placeholder="الكلمات الدلالية">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">الصورة الأولى</label>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                            style="width: 200px; height: 150px;">
                                                            <img src="{{ old('image') }}" alt="" />
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                            style="max-width: 200px; max-height: 150px;"> </div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> إختيار صورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="image"> </span>
                                                            <a href="javascript:;" class="btn red fileinput-exists"
                                                                data-dismiss="fileinput"> إزالة </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">الصورة الثانية</label>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                            style="width: 200px; height: 150px;">
                                                            <img src="{{ old('image2') }}" alt="" />
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                            style="max-width: 200px; max-height: 150px;"> </div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> إختيار صورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="image2"> </span>
                                                            <a href="javascript:;" class="btn red fileinput-exists"
                                                                data-dismiss="fileinput"> إزالة </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">الصورة الثالثة</label>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                            style="width: 200px; height: 150px;">
                                                            <img src="{{ old('image3') }}" alt="" />
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                            style="max-width: 200px; max-height: 150px;"> </div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> إختيار صورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="image3"> </span>
                                                            <a href="javascript:;" class="btn red fileinput-exists"
                                                                data-dismiss="fileinput"> إزالة </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">الحالة</label>
                                                <div class="col-md-6">
                                                    <input type="checkbox" value="1" name="status" class="make-switch"
                                                        data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;"
                                                        {{ old('status') == 1 ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($languages as $item)
                                        <div class="tab-pane" id="tab_{{ $loop->iteration }}">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">عنوان الصفحة</label>
                                                <div class="col-md-6">
                                                    <input type="text" value="{{ old($item->prefix . '_title') }}"
                                                        name="{{ $item->prefix }}_title" class="form-control"
                                                        placeholder="عنوان الصفحة">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">التفاصيل</label>
                                                <div class="col-md-6">
                                                    <textarea name="{{ $item->prefix }}_details" id="{{ $item->prefix }}_descs" class="form-control ckeditor"
                                                        rows="3">{{ old($item->prefix . '_details') }}</textarea>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="col-md-offset-3 col-md-6">
                                <button type="submit" class="btn btn-sm default {{ $btn_class }}">حفظ</button>
                                <a href="{{ route('pages.view') }}" type="button" class="btn btn-sm default">إلغاء</a>
                                {{ csrf_field() }}
                            </div>
                        </div>
                    </form>
                </div>
            </form>
        </div>
    </div>
@stop
@section('js')
    <script src="{{ asset('assets/admin/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
    <script>CKEDITOR.config.customConfig = "{{ asset('assets/admin/ckeditor/config.js') }}?v=2";</script>
    <script type="text/javascript">
        CKEDITOR.replace('ar_details', {
            contentsLangDirection: 'rtl',
            filebrowserUploadUrl: "{{ route('news.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
        CKEDITOR.replace('en_details', {
            filebrowserUploadUrl: "{{ route('news.upload', ['_token' => csrf_token()]) }}",
            contentsLangDirection: 'ltr',
            filebrowserUploadMethod: 'form'
        });
    </script>
@stop
