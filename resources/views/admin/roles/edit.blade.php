@extends('layouts.admin')

@section('title', 'تعديل صلاحية')

@section('page-title')
الصلاحيات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('roles.view') }}" class="text-muted text-hover-primary">إدارة الصلاحيات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تعديل صلاحية</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">إسم المجموعة</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="Role">
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
                <a href="{{ route('roles.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
