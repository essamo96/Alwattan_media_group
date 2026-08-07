@extends('layouts.admin')

@section('title', 'تعديل مستخدم')

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
        <div class="card-title">تعديل مستخدم</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form method="post" action="" role="form">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اسم المستخدم (User Name)</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->username }}" name="username" id="username" class="form-control" placeholder="اسم المستخدم (User Name)">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الإسم الشخصي</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="الإسم الشخصي">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">إدارة الصلاحيات</label>
                <div class="col-md-6">
                    <select name="role" id="role" class="form-select">
                        @foreach($roles as $item)
                        <option value="{{ $item->id }}" {{ $info->role == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
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
                <a href="{{ route('users.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
