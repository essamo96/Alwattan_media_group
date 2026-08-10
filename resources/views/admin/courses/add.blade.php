@extends('layouts.admin')

@section('title', 'إضافة دورة')

@section('page-title')
الدورات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('courses.view') }}" class="text-muted text-hover-primary">إدارة الدورات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة دورة جديدة</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة دورة جديدة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اسم الدورة</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('name') }}" name="name" id="name" class="form-control" placeholder="اسم الدورة">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تاريخ البدء</label>
                <div class="col-md-6">
                    <input type="date" value="{{ old('start_date') }}" name="start_date" class="form-control">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تاريخ الانتهاء</label>
                <div class="col-md-6">
                    <input type="date" value="{{ old('end_date') }}" name="end_date" class="form-control">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">أيام الانعقاد</label>
                <div class="col-md-6">
                    @foreach($days_of_week_labels as $key => $label)
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" type="checkbox" name="days_of_week[]" value="{{ $key }}" id="day_{{ $key }}" {{ in_array($key, old('days_of_week', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="day_{{ $key }}">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">ملاحظات</label>
                <div class="col-md-6">
                    <textarea name="notes" class="form-control" rows="3" placeholder="ملاحظات">{{ old('notes') }}</textarea>
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

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('courses.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
