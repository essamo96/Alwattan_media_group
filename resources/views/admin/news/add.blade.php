@extends('layouts.admin')

@section('title', 'إضافة خبر')

@section('page-title')
الأخبار
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('news.view') }}" class="text-muted text-hover-primary">إدارة الأخبار</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة خبر جديد</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة خبر جديد</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">القسم</label>
                <div class="col-md-6">
                    <select name="category_id" id="category_id" class="form-select">
                        @foreach($info as $item)
                        <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اللغة</label>
                <div class="col-md-6">
                    <select name="language" class="form-select">
                        <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>عربي</option>
                        <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">العنوان</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control title" placeholder="العنوان">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الرابط المخصص</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('slug') }}" name="slug" id="slug" class="form-control" placeholder="الرابط المخصص">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">مقدمة</label>
                <div class="col-md-6">
                    <textarea name="sub" id="sub" maxlength="200" class="form-control" rows="3">{{ old('sub') }}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">التفاصيل</label>
                <div class="col-md-6">
                    <textarea name="descs" id="descs" class="form-control ckeditor" rows="3">{{ old('descs') }}</textarea>
                </div>
            </div>
            <div id="image" class="row mb-5">
                <label class="col-md-3 col-form-label">صورة</label>
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
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">كلمات مفتاحية</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('tags') }}" name="tags" id="tags" class="form-control input-large" data-role="tagsinput">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تاريخ النشر</label>
                <div class="col-md-6">
                    <input type="text" name="pub_date" id="pub_date" value="{{ old('pub_date') ? old('pub_date') : date('Y-m-d H:i:s') }}" class="form-control rtl" placeholder="تاريخ النشر">
                </div>
            </div>
            @can('admin.news.publish')
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">نشر</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="publish" {{ old('publish') == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            @endcan
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">مثبت</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="main" {{ old('main') == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('news.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script src="{{ asset_v('assets/metronic/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
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
ClassicEditor.create(document.querySelector('#descs'), {
    language: 'ar',
    ckfinder: {
        uploadUrl: "{{ route('news.upload', ['_token' => csrf_token()]) }}"
    }
}).catch(error => console.error(error));
var domain = "{{ asset('/admin').'/file_manager' }}";
$('#lfm').filemanager('image', {prefix: domain});
</script>
@endpush
