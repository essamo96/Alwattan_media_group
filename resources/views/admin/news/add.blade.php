@extends('admin.layout.master')

@section('title')
إضافة خبر
@stop

@section('css')

@stop

@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <i class="icon-home"></i>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('news.view') }}">إدارة الأخبار</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('news.add') }}">إضافة خبر جديد</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> الأخبار
    <small>إضافة خبر</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-book-open"></i>إضافة خبر جديد </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3">القسم</label>
                        <div class="col-md-6">
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach($info as $item)
                                <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
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
                        <label class="control-label col-md-3">العنوان</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control title" placeholder="العنوان">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الرابط المخصص</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('slug') }}" name="slug" id="slug" class="form-control" placeholder="الرابط المخصص">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">مقدمة</label>
                        <div class="col-md-6">
                            <textarea name="sub" id="sub" maxlength="200" class="form-control" rows="3">{{ old('sub') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">التفاصيل</label>
                        <div class="col-md-6">
                            <textarea name="descs" id="descs" class="form-control ckeditor" rows="3">{{ old('descs') }}</textarea>

                        </div>
                    </div>
                    <div id="image" class="form-group">
                        <label class="control-label col-md-3">صورة</label>
                        <div class="col-md-5">
                            <input id="thumbnail" value="{{ old('image') }}" class="form-control" type="text" name="image" readonly>
                            <img id="holder" src="" style="margin-top:15px;max-height:100px;">
                        </div>
                        <div class="col-md-1">
                            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> حدد صورة
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">كلمات مفتاحية</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('tags') }}" name="tags" id="tags" class="form-control input-large" data-role="tagsinput">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">تاريخ النشر</label>
                        <div class="col-md-6">
                            <input type="text" name="pub_date" id="pub_date" value="{{ old('pub_date')?old('pub_date'):date('Y-m-d H:i:s')}}" class="form-control rtl" placeholder="تاريخ النشر">
                        </div>
                    </div>
                    @can('admin.news.publish')
                    <div class="form-group">
                        <label class="control-label col-md-3">نشر</label>
                        <div class="col-md-6">
                            <input type="checkbox" value="1" name="publish" class="make-switch" data-on-text="&nbsp;نعم&nbsp;" data-off-text="&nbsp;لا&nbsp;" {{ old('publish') == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                    @endcan

                    <div class="form-group">
                        <label class="control-label col-md-3">مثبت</label>
                        <div class="col-md-6">
                            <input type="checkbox" value="1" name="main" class="make-switch" data-on-text="&nbsp;نعم&nbsp;" data-off-text="&nbsp;لا&nbsp;" {{ old('main') == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    <a href="{{ route('news.view') }}" type="button" class="btn default">إلغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
<script src="vendor/laravel-filemanager/js/lfm.js"></script>
<script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
<script type="text/javascript">
function convertToSlug(str)
{
    //replace all special characters | symbols with a space
    str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();

    // trim spaces at start and end of string
    str = str.replace(/^\s+|\s+$/gm, '');

    // replace space with dash/hyphen
    str = str.replace(/\s+/g, '-');

    return str;
}
$(".title").keyup(function () {
    var slug = convertToSlug($(this).val());
    $("#slug").val(slug);
});
CKEDITOR.replace('descs', {
    contentsLangDirection: 'rtl',
    filebrowserUploadUrl: "{{route('news.upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form'
});
var domain = "admin/file_manager";
$('#lfm').filemanager('image', {prefix: domain});
</script>
@stop
