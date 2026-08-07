@extends('layouts.admin')

@section('title', 'الرد ع رسالة')

@section('page-title')
إدارة اتصل بنا
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('contacts.view') }}" class="text-muted text-hover-primary">إدارة اتصل بنا</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name }}</li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('contacts.reply',['id' => Crypt::encrypt($info->id)]) }}" class="text-muted text-hover-primary">الرد ع رسالة</a></li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">الرد ع رسالة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الرسالة</label>
                <div class="col-md-6">
                    <textarea name="description" id="description" class="form-control" rows="3" readonly>{{ $info->message }}</textarea>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الرد ع الرسالة</label>
                <div class="col-md-6">
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('contacts.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
