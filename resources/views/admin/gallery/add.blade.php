@extends('layouts.admin')

@section('title', 'إضافة صورة')

@section('page-title')
معرض الصور
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('gallery.view') }}" class="text-muted text-hover-primary">معرض الصور</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة صورة جديدة</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة صورة جديدة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">العنوان (اختياري)</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control" placeholder="العنوان">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الصورة</label>
                <div class="col-md-6">
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الترتيب</label>
                <div class="col-md-6">
                    <input type="number" value="{{ old('sort_order', 0) }}" name="sort_order" id="sort_order" class="form-control">
                    <div class="form-text">الأصغر يظهر أولاً بمعرض الصور بالموقع الخارجي.</div>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الحالة</label>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="status" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('gallery.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
