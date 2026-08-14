@extends('layouts.admin')

@section('title', 'اضافة قسم')

@section('page-title')
اقسام العقارات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('properties_categories.view') }}" class="text-muted text-hover-primary">ادارة اقسام العقارات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">اضافة قسم</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">اضافة قسم</div>
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
                        <label class="col-md-3 col-form-label">الحالة</label>
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" name="status" {{ old('status') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عنوان الرابط</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('slug') }}" name="slug" class="form-control" placeholder="عنوان الرابط">
                        </div>
                    </div>
                </div>

                @foreach($languages as $item)
                <div class="tab-pane fade" id="tab_{{ $loop->iteration }}">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عنوان القسم</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old($item->prefix.'_title') }}" name="{{ $item->prefix }}_title" class="form-control" placeholder="الاسم">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الوصف</label>
                        <div class="col-md-6">
                            <textarea name="{{ $item->prefix }}_descs" id="{{ $item->prefix }}_descs" class="form-control ckeditor" data-lang="{{ $item->prefix }}" rows="3">{{ old($item->prefix.'_descs') }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('properties_categories.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

