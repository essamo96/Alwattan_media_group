@extends('layouts.admin')

@section('title', 'إضافة جهة اتصال')

@section('page-title')
جهات الاتصال
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('contact.view') }}" class="text-muted text-hover-primary">إدارة جهات اتصال</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة جهة اتصال جديد</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة جهة اتصال جديد</div>
        <div class="card-toolbar">
            <a href="{{ URL::previous() }}" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-black-left fs-4"></i> رجوع
            </a>
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <ul class="nav nav-tabs mb-5">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_0">معلومات جهة الاتصال</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab_0">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">اسم الجهة</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name') }}" name="name" id="name" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">القطاع</label>
                        <div class="col-md-6">
                            <select name="contact_type" id="contact_type" class="form-select">
                                <option value="" selected>اختر...</option>
                                <option value="الجهات الحكومية"> الجهات الحكومية</option>
                                <option value="المؤسسات والشركات"> المؤسسات والشركات</option>
                                <option value="القطاع غير الربحي"> القطاع غير الربحي</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الهاتف</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('mobile') }}" name="mobile" id="mobile" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الهاتف البديل</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('another_mobile') }}" name="another_mobile" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الشخص المسؤول</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('master') }}" name="master" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الايميل</label>
                        <div class="col-md-6">
                            <input type="email" value="{{ old('email') }}" name="email" class="form-control" data-role="emailinput">
                        </div>
                    </div>
                    <input type="hidden" name="add_date" value="">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">تاريخ التنبيه</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control" name="remember_date">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">اضف ملاحظة</label>
                        <div class="col-md-9">
                            <textarea id="notes" class="form-control" rows="10" name="notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('contact.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        ClassicEditor
            .create(document.querySelector('#notes'))
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush
