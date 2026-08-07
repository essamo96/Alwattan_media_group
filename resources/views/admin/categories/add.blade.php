@extends('layouts.admin')

@section('title', 'إضافة قسم')

@section('page-title')
الأقسام
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('categories.view') }}" class="text-muted text-hover-primary">إدارة الأقسام</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة قسم جديد</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة قسم جديد</div>
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
                        <label class="col-md-3 col-form-label">القسم الاب</label>
                        <div class="col-md-6">
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="0" {{ old('category_id') == 0 ? 'selected' : '' }}>لا يوجد قسم اب</option>
                                @foreach($categories as $item)
                                <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الترتيب</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('sort') }}" name="sort" id="sort" class="form-control" placeholder="الترتيب">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عدد الأعمدة</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('col_no') }}" name="col_no" class="form-control" placeholder="عدد الأعمدة">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الكلمات الدلالية</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('tags') }}" name="tags" id="tags" class="form-control" placeholder="الكلمات الدلالية">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الرابط المخصص</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('slug') }}" name="slug" class="form-control" placeholder="الرابط المخصص">
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
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">يظهر في القائمة</label>
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" name="in_menu" {{ old('in_menu') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($languages as $item)
                <div class="tab-pane fade" id="tab_{{ $loop->iteration }}">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الاسم</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old($item->prefix.'_name') }}" name="{{ $item->prefix }}_name" class="form-control" placeholder="الاسم">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('categories.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
