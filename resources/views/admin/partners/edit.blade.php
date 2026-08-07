@extends('layouts.admin')

@section('title', 'تعديل شريك')

@section('page-title')
الشركاء
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('partners.view') }}" class="text-muted text-hover-primary">إدارة الشركاء</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تعديل شريك</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <ul class="nav nav-tabs mb-5">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_0">البيانات الاساسية</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab_0">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الاسم</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="الاسم">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الترتيب</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->p_order }}" name="p_order" id="p_order" class="form-control" placeholder="الترتيب">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الصورة</label>
                        <div class="col-md-6">
                            <input type="file" name="image" class="form-control" placeholder="الصورة">
                            <img src="{{ url($info->image) }}" style="margin-top:15px;max-height:100px;">
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
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('partners.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
