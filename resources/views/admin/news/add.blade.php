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
                    <textarea name="descs" id="descs" class="form-control ckeditor" data-lang="ar" rows="3">{{ old('descs') }}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">نوع الوسائط</label>
                <div class="col-md-6">
                    <div class="form-check form-check-custom form-check-solid me-5 d-inline-block">
                        <input class="form-check-input media-type-radio" type="radio" name="type" id="type_image" value="image" {{ old('type', 'image') == 'image' ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_image">صورة</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid d-inline-block">
                        <input class="form-check-input media-type-radio" type="radio" name="type" id="type_video" value="video" {{ old('type') == 'video' ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_video">فيديو</label>
                    </div>
                </div>
            </div>
            <div id="image" class="row mb-5 media-type-field media-type-field-image">
                <label class="col-md-3 col-form-label">صورة</label>
                <div class="col-md-6">
                    <input id="image_input" class="form-control" type="file" name="image" accept="image/*">
                    <img id="holder" src="" class="d-none" style="margin-top:15px;max-height:150px;border-radius:6px;">
                </div>
            </div>
            <div id="video" class="row mb-5 media-type-field media-type-field-video d-none">
                <label class="col-md-3 col-form-label">الفيديو</label>
                <div class="col-md-6">
                    <div class="form-check form-check-custom form-check-solid me-5 d-inline-block">
                        <input class="form-check-input video-source-radio" type="radio" name="video_source" id="video_source_url" value="url" {{ old('video_source', 'url') == 'url' ? 'checked' : '' }}>
                        <label class="form-check-label" for="video_source_url">رابط خارجي</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid d-inline-block">
                        <input class="form-check-input video-source-radio" type="radio" name="video_source" id="video_source_file" value="file" {{ old('video_source') == 'file' ? 'checked' : '' }}>
                        <label class="form-check-label" for="video_source_file">رفع ملف فيديو</label>
                    </div>
                    <div class="mt-3 video-source-field video-source-field-url">
                        <input type="text" value="{{ old('video') }}" name="video" id="video_input" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="mt-3 video-source-field video-source-field-file d-none">
                        <input type="file" name="video_file" class="form-control" accept="video/*">
                        <div class="form-text">صيغ مدعومة: mp4, mov, webm, ogg, mkv, avi — بحد أقصى 100 ميجابايت.</div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">معرض إضافي (صور/فيديوهات)</label>
                <div class="col-md-9">
                    <div id="media_repeater">
                        @if(old('media_type'))
                        @foreach(old('media_type', []) as $old_index => $old_type)
                        <div class="row mb-3 align-items-center media-repeater-row" data-index="{{ $old_index }}">
                            <div class="col-md-3">
                                <select name="media_type[{{ $old_index }}]" class="form-select media-row-type">
                                    <option value="image" {{ $old_type == 'image' ? 'selected' : '' }}>صورة</option>
                                    <option value="video" {{ $old_type == 'video' ? 'selected' : '' }}>فيديو</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="file" name="media_image[{{ $old_index }}]" accept="image/*" class="form-control media-row-image {{ $old_type == 'video' ? 'd-none' : '' }}">
                                @if($old_type == 'image')
                                <div class="form-text text-warning">فشل الحفظ ولم يُحتفظ بالملف — يرجى إعادة اختيار الصورة.</div>
                                @endif
                                @php $old_video_source = old('media_video_source.' . $old_index, 'url'); @endphp
                                <div class="media-row-video-wrap {{ $old_type == 'video' ? '' : 'd-none' }}">
                                    <div class="form-check form-check-custom form-check-solid me-4 d-inline-block">
                                        <input class="form-check-input media-row-video-source" type="radio" name="media_video_source[{{ $old_index }}]" value="url" {{ $old_video_source != 'file' ? 'checked' : '' }}>
                                        <label class="form-check-label">رابط خارجي</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid d-inline-block">
                                        <input class="form-check-input media-row-video-source" type="radio" name="media_video_source[{{ $old_index }}]" value="file" {{ $old_video_source == 'file' ? 'checked' : '' }}>
                                        <label class="form-check-label">رفع ملف</label>
                                    </div>
                                    <input type="text" name="media_video_url[{{ $old_index }}]" value="{{ old('media_video_url.' . $old_index) }}" class="form-control mt-2 media-row-video-url {{ $old_video_source == 'file' ? 'd-none' : '' }}" placeholder="https://www.youtube.com/watch?v=...">
                                    <input type="file" name="media_video_file[{{ $old_index }}]" accept="video/*" class="form-control mt-2 media-row-video-file {{ $old_video_source == 'file' ? '' : 'd-none' }}">
                                    @if($old_video_source == 'file')
                                    <div class="form-text text-warning">فشل الحفظ ولم يُحتفظ بالملف — يرجى إعادة اختيار الفيديو.</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger media-row-remove"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <button type="button" id="media_add_row" class="btn btn-sm btn-light-primary mt-2">
                        <i class="fa fa-plus"></i> إضافة عنصر
                    </button>
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
        holder.classList.add('d-none');
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
        $('#image_input').prop('required', false);
    } else {
        $('.media-type-field-video').addClass('d-none');
        $('.media-type-field-image').removeClass('d-none');
    }
}
$('input.media-type-radio').on('change', toggleMediaType);
toggleMediaType();

function toggleVideoSource() {
    var source = $('input.video-source-radio:checked').val();
    if (source === 'file') {
        $('.video-source-field-url').addClass('d-none');
        $('.video-source-field-file').removeClass('d-none');
    } else {
        $('.video-source-field-file').addClass('d-none');
        $('.video-source-field-url').removeClass('d-none');
    }
}
$('input.video-source-radio').on('change', toggleVideoSource);
toggleVideoSource();

var mediaRowIndex = {{ old('media_type') ? count(old('media_type')) : 0 }};
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
        '    <div class="media-row-video-wrap d-none">' +
        '      <div class="form-check form-check-custom form-check-solid me-4 d-inline-block">' +
        '        <input class="form-check-input media-row-video-source" type="radio" name="media_video_source[' + index + ']" value="url" checked>' +
        '        <label class="form-check-label">رابط خارجي</label>' +
        '      </div>' +
        '      <div class="form-check form-check-custom form-check-solid d-inline-block">' +
        '        <input class="form-check-input media-row-video-source" type="radio" name="media_video_source[' + index + ']" value="file">' +
        '        <label class="form-check-label">رفع ملف</label>' +
        '      </div>' +
        '      <input type="text" name="media_video_url[' + index + ']" class="form-control mt-2 media-row-video-url" placeholder="https://www.youtube.com/watch?v=...">' +
        '      <input type="file" name="media_video_file[' + index + ']" accept="video/*" class="form-control mt-2 media-row-video-file d-none">' +
        '    </div>' +
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
        $row.find('.media-row-video-wrap').removeClass('d-none');
    } else {
        $row.find('.media-row-video-wrap').addClass('d-none');
        $row.find('.media-row-image').removeClass('d-none');
    }
});
$(document).on('change', '.media-row-video-source', function () {
    var $row = $(this).closest('.media-repeater-row');
    if ($(this).val() === 'file') {
        $row.find('.media-row-video-url').addClass('d-none');
        $row.find('.media-row-video-file').removeClass('d-none');
    } else {
        $row.find('.media-row-video-file').addClass('d-none');
        $row.find('.media-row-video-url').removeClass('d-none');
    }
});
$(document).on('click', '.media-row-remove', function () {
    $(this).closest('.media-repeater-row').remove();
});
$('#media_add_row').on('click', addMediaRow);
</script>
@endpush
