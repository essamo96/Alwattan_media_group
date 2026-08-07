@extends('layouts.admin')

@section('title', 'تعديل الاسئلة الشائعة')

@section('page-title')
الاسئلة الشائعة
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('faq.view') }}" class="text-muted text-hover-primary">إدارة الاسئلة الشائعة</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name_ar }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تعديل الاسئلة الشائعة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">السؤال عربي</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name_ar }}" name="name_ar" id="name_ar" class="form-control" placeholder="السؤال عربي">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الجواب عربي</label>
                <div class="col-md-6">
                    <textarea name="name1_ar" id="name1_ar" class="form-control" placeholder="الجواب عربي" rows="3">{{ $info->name1_ar }}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">السؤال انجليزي</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name_en }}" name="name_en" class="form-control" placeholder="السؤال انجليزي">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الجواب انجليزي</label>
                <div class="col-md-6">
                    <textarea name="name1_en" id="name1_en" class="form-control" placeholder="الجواب انجليزي">{{ $info->name1_en }}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الحالة</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="status" {{ $info->status == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('faq.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset_v('assets/metronic/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript">
    ClassicEditor.create(document.querySelector('#name1_ar'), { language: 'ar' }).catch(error => console.error(error));
    ClassicEditor.create(document.querySelector('#name1_en'), { language: 'en' }).catch(error => console.error(error));
</script>
@endpush
