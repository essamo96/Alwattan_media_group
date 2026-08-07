@extends('layouts.admin')

@section('title', 'تعديل سلايدر')

@section('page-title')
السلايدات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('sliders.view') }}" class="text-muted text-hover-primary">ادارة السلايدات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تعديل سلايدر</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">السطر الاول</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="السطر الاول">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">السطر الثاني</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name1 }}" name="name1" id="name1" class="form-control" placeholder="السطر الثاني">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">السطر الثالث</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name2 }}" name="name2" id="name2" class="form-control" placeholder="السطر الثالث">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اللغة</label>
                <div class="col-md-6">
                    <select name="language" id="category_id" class="form-select">
                        <option value="ar" {{ $info->language == 'ar' ? 'selected' : '' }}>عربي</option>
                        <option value="en" {{ $info->language == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الرابط</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->link }}" name="link" id="link" class="form-control" placeholder="Link">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">عرض النص</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="txt_status" {{ $info->txt_status == 1 ? 'checked' : '' }}>
                    </div>
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
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الصورة</label>
                <div class="col-md-6">
                    <img src="uploads/sliders/{{ $info->image }}" alt="" class="img-thumbnail mb-3" style="max-width: 200px; max-height: 150px;">
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('sliders.view') }}" class="btn btn-light me-3">الغاء</a>
                <button type="submit" class="btn btn-primary">تعديل</button>
            </div>
        </form>
    </div>
</div>
@endsection
