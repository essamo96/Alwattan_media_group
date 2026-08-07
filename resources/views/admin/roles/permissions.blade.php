@extends('layouts.admin')

@section('title', 'إدارة الصلاحيات')

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
        <div class="card-title">تحديد صلاحيات المجموعة: {{ $info->name }}</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="">
            @csrf
            @foreach($permission_group as $row)
            <div class="mb-8">
                <h3 class="text-primary fw-bold mb-4 pb-2 border-bottom">{{ $row->name }}</h3>
                <div class="row">
                    @foreach($row->permissions as $item)
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-check-custom form-check-solid">
                            <input id="permissions[]" name="permissions[]" type="checkbox" {{ in_array($item->id,array_column($role_permissions,'permission_id')) ? 'checked' : '' }} value="{{ $item->id }}" class="form-check-input">
                            <label class="form-check-label" for="permissions[]">{{ trans('permissions.'.$item->name) }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('roles.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
