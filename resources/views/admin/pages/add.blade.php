@extends('layouts.admin')

@section('title', 'إضافة صفحة')

@section('page-title')
الصفحات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('pages.view') }}" class="text-muted text-hover-primary">إدارة الصفحات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة صفحة جديدة</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة صفحة جديدة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <ul class="nav nav-tabs mb-5">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_0">البيانات الاساسية</a>
                </li>
                @foreach($languages as $item)
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_{{ $loop->iteration }}">{{ $item->name }}</a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab_0">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">اسم مخصص لجوجل</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('slug') }}" name="slug" class="form-control" placeholder="اسم مخصص لجوجل">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الكلمات الدلالية</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('tags') }}" name="tags" class="form-control" placeholder="الكلمات الدلالية">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الصورة الأولى</label>
                        <div class="col-md-6">
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الصورة الثانية</label>
                        <div class="col-md-6">
                            <input type="file" name="image2" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الصورة الثالثة</label>
                        <div class="col-md-6">
                            <input type="file" name="image3" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الحالة</label>
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" name="status" {{ old('status') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($languages as $item)
                <div class="tab-pane fade" id="tab_{{ $loop->iteration }}">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عنوان الصفحة</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old($item->prefix.'_title') }}" name="{{ $item->prefix }}_title" class="form-control" placeholder="عنوان الصفحة">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">التفاصيل</label>
                        <div class="col-md-6">
                            <textarea name="{{ $item->prefix }}_details" id="{{ $item->prefix }}_descs" class="form-control ckeditor" rows="3">{{ old($item->prefix.'_details') }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('pages.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset_v('assets/metronic/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript">
    ClassicEditor.create(document.querySelector('#ar_descs'), {
        language: 'ar',
        ckfinder: { uploadUrl: "{{ route('news.upload', ['_token' => csrf_token()]) }}" }
    }).catch(error => console.error(error));
    ClassicEditor.create(document.querySelector('#en_descs'), {
        language: 'en',
        ckfinder: { uploadUrl: "{{ route('news.upload', ['_token' => csrf_token()]) }}" }
    }).catch(error => console.error(error));
</script>
@endpush
