@extends('layouts.admin')

@section('title', 'تغيير كلمة المرور')

@section('page-title')
المستخدمين
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('users.view') }}" class="text-muted text-hover-primary">إدارة المستخدمين</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تغيير كلمة المرور</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form method="post" action="" role="form">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الإسم</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->username }}" name="username" id="username" class="form-control" placeholder="الإسم" readonly>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الإسم الكامل</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="الإسم الكامل" readonly>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">كلمة المرور</label>
                <div class="col-md-6">
                    <input type="password" value="" name="password" id="password" class="form-control" placeholder="كلمة المرور">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تأكيد كلمة المرور</label>
                <div class="col-md-6">
                    <input type="password" value="" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('users.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
