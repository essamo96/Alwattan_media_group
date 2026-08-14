@extends('layouts.admin')

@section('title', 'تعديل خبر')

@section('page-title')
الأخبار
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('news.view') }}" class="text-muted text-hover-primary">إدارة الأخبار</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->title }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تعديل خبر</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الأقسام</label>
                <div class="col-md-6">
                    <select name="category_id" class="form-select" id="category_id">
                        @foreach($categories as $item)
                        <option value="{{ $item->id }}" {{ $info->category_id == $item->id ? 'selected' : '' }}> {{ $item->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اللغة</label>
                <div class="col-md-6">
                    <select name="language" class="form-select">
                        <option value="ar" {{ $info->language == 'ar' ? 'selected' : '' }}>عربي</option>
                        <option value="en" {{ $info->language == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">العنوان</label>
                <div class="col-md-6">
                    <textarea name="title" class="form-control title">{!! $info->title !!}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الرابط المخصص</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->slug }}" name="slug" id="slug" class="form-control" placeholder="الرابط المخصص">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">مقدمة</label>
                <div class="col-md-6">
                    <textarea name="sub" maxlength="200" id="sub" class="form-control" rows="3">{{ $info->sub }}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">التفاصيل</label>
                <div class="col-md-6">
                    <textarea name="descs" id="descs" class="form-control ckeditor" data-lang="ar" rows="3">{!! $info->descs !!}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">نوع الوسائط</label>
                <div class="col-md-6">
                    <div class="form-check form-check-custom form-check-solid me-5 d-inline-block">
                        <input class="form-check-input media-type-radio" type="radio" name="type" id="type_image" value="image" {{ $info->type != 'video' ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_image">صورة</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid d-inline-block">
                        <input class="form-check-input media-type-radio" type="radio" name="type" id="type_video" value="video" {{ $info->type == 'video' ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_video">فيديو</label>
                    </div>
                </div>
            </div>
            <div id="image" class="row mb-5 media-type-field media-type-field-image">
                <label class="col-md-3 col-form-label">صورة</label>
                <div class="col-md-6">
                    <input id="image_input" class="form-control" type="file" name="image" accept="image/*">
                    <div class="form-text">اتركه فارغاً للإبقاء على الصورة الحالية.</div>
                    <img id="holder" src="{{ asset($info->image) }}" style="margin-top:15px;max-height:150px;border-radius:6px;">
                </div>
            </div>
            <div id="video" class="row mb-5 media-type-field media-type-field-video d-none">
                <label class="col-md-3 col-form-label">رابط الفيديو</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->video }}" name="video" id="video_input" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">معرض إضافي (صور/فيديوهات)</label>
                <div class="col-md-9">
                    <div id="media_repeater">
                        @foreach($info->media as $item)
                        <div class="row mb-3 align-items-center media-repeater-row" data-index="{{ $loop->index }}">
                            <div class="col-md-3">
                                <select name="media_type[{{ $loop->index }}]" class="form-select media-row-type">
                                    <option value="image" {{ $item->type == 'image' ? 'selected' : '' }}>صورة</option>
                                    <option value="video" {{ $item->type == 'video' ? 'selected' : '' }}>فيديو</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="hidden" name="media_id[{{ $loop->index }}]" value="{{ $item->id }}">
                                <input type="file" name="media_image[{{ $loop->index }}]" accept="image/*" class="form-control media-row-image {{ $item->type == 'video' ? 'd-none' : '' }}">
                                @if($item->type == 'image' && $item->path)
                                <img src="{{ asset($item->path) }}" style="margin-top:8px;max-height:80px;border-radius:6px;display:block;">
                                @endif
                                <input type="text" name="media_video_url[{{ $loop->index }}]" value="{{ $item->video_url }}" class="form-control media-row-video {{ $item->type == 'video' ? '' : 'd-none' }}" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger media-row-remove"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="media_add_row" class="btn btn-sm btn-light-primary mt-2">
                        <i class="fa fa-plus"></i> إضافة عنصر
                    </button>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">كلمات مفتاحية</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->tags }}" name="tags" id="tags" class="form-control input-large" data-role="tagsinput">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تاريخ النشر</label>
                <div class="col-md-6">
                    <input type="text" name="pub_date" id="pub_date" value="{{ $info->pub_date }}" class="form-control rtl" placeholder="تاريخ النشر">
                </div>
            </div>
            @can('admin.news.publish')
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">نشر</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="publish" {{ $info->publish == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            @endcan
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">مثبت</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="main" {{ $info->main == 1 ? 'checked' : '' }}>
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
document.getElementById('image_input').addEventListener('change', function (e) {
    var file = e.target.files[0];
    var holder = document.getElementById('holder');
    if (!file) {
        return;
    }
    var reader = new FileReader();
    reader.onload = function (ev) {
        holder.src = ev.target.result;
        holder.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

function toggleMediaType() {
    var type = $('input.media-type-radio:checked').val();
    if (type === 'video') {
        $('.media-type-field-image').addClass('d-none');
        $('.media-type-field-video').removeClass('d-none');
    } else {
        $('.media-type-field-video').addClass('d-none');
        $('.media-type-field-image').removeClass('d-none');
    }
}
$('input.media-type-radio').on('change', toggleMediaType);
toggleMediaType();

var mediaRowIndex = {{ $info->media->count() }};
function mediaRowTemplate(index) {
    return '' +
        '<div class="row mb-3 align-items-center media-repeater-row" data-index="' + index + '">' +
        '  <div class="col-md-3">' +
        '    <select name="media_type[' + index + ']" class="form-select media-row-type">' +
        '      <option value="image">صورة</option>' +
        '      <option value="video">فيديو</option>' +
        '    </select>' +
        '  </div>' +
        '  <div class="col-md-7">' +
        '    <input type="file" name="media_image[' + index + ']" accept="image/*" class="form-control media-row-image">' +
        '    <input type="text" name="media_video_url[' + index + ']" class="form-control media-row-video d-none" placeholder="https://www.youtube.com/watch?v=...">' +
        '  </div>' +
        '  <div class="col-md-2">' +
        '    <button type="button" class="btn btn-sm btn-icon btn-light-danger media-row-remove"><i class="fa fa-trash"></i></button>' +
        '  </div>' +
        '</div>';
}
function addMediaRow() {
    var $row = $(mediaRowTemplate(mediaRowIndex));
    mediaRowIndex++;
    $('#media_repeater').append($row);
}
$(document).on('change', '.media-row-type', function () {
    var $row = $(this).closest('.media-repeater-row');
    if ($(this).val() === 'video') {
        $row.find('.media-row-image').addClass('d-none');
        $row.find('.media-row-video').removeClass('d-none');
    } else {
        $row.find('.media-row-video').addClass('d-none');
        $row.find('.media-row-image').removeClass('d-none');
    }
});
$(document).on('click', '.media-row-remove', function () {
    var $row = $(this).closest('.media-repeater-row');
    var mediaId = $row.find('input[name^="media_id"]').val();
    if (mediaId) {
        $row.append('<input type="hidden" name="media_delete[]" value="' + mediaId + '">');
        $row.hide();
    } else {
        $row.remove();
    }
});
$('#media_add_row').on('click', addMediaRow);
</script>
@endpush
