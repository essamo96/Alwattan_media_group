@extends('layouts.admin')

@section('title', 'الصفحة الرئيسية - الملف الشخصي')

@section('page-title')
الصفحة الرئيسية
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الصفحة الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">الملف الشخصي</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ki-duotone ki-profile-circle fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            الملف الشخصي
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <h3 class="mb-5">معلومات الحساب</h3>
        <div class="row mb-5">
            <label class="col-md-3 col-form-label">إسم المستخدم</label>
            <div class="col-md-9">
                <div class="fw-semibold fs-6 pt-2">{{ $info->username }}</div>
            </div>
        </div>
        <div class="row mb-5">
            <label class="col-md-3 col-form-label">الإسم الكامل</label>
            <div class="col-md-9">
                <div class="fw-semibold fs-6 pt-2">{{ $info->name }}</div>
            </div>
        </div>
        <div class="row mb-5">
            <label class="col-md-3 col-form-label">البريد الإلكتروني</label>
            <div class="col-md-9">
                <div class="fw-semibold fs-6 pt-2">{{ $info->email }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
