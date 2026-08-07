@extends('layouts.admin')

@section('title', 'الصفحة الرئيسية - تغيير كلمة المرور')

@section('page-title')
الصفحة الرئيسية
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الصفحة الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">تغيير كلمة المرور</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ki-duotone ki-key fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
            تغيير كلمة المرور
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" id="update_password" action="" method="post">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">إسم المستخدم</label>
                <div class="col-md-6">
                    <input type="text" value="{{ Auth::user()->username }}" class="form-control" readonly disabled>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">كلمة المرور القديمة</label>
                <div class="col-md-6">
                    <input name="old_password" value="" type="password" class="form-control">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">كلمة المرور الجديدة</label>
                <div class="col-md-6">
                    <input name="password" value="" type="password" class="form-control">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تأكيد كلمة المرور</label>
                <div class="col-md-6">
                    <input name="password_confirmation" value="" type="password" class="form-control">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
