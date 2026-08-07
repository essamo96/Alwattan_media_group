@extends('layouts.admin')

@section('title', 'عرض تفاصيل جهة الاتصال')

@section('page-title')
جهات الاتصال
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('contact.view') }}" class="text-muted text-hover-primary">إدارة جهات اتصال</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">عرض تفاصيل جهة الاتصال</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">عرض تفاصيل جهة الاتصال</div>
        <div class="card-toolbar">
            <a href="{{ URL::previous() }}" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-black-left fs-4"></i> رجوع
            </a>
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form">
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اسم الجهة</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->name }}" name="name" id="name" readonly class="form-control" placeholder="name">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">نوع الجهة</label>
                <div class="col-md-6">
                    <select name="contact_type" id="contact_type" class="form-select" disabled>
                        <option value="" selected>اختر...</option>
                        <option value="الجهات الحكومية" {{ $info->contact_type == 'الجهات الحكومية' ? 'selected' : '' }}> الجهات الحكومية</option>
                        <option value="المؤسسات والشركات" {{ $info->contact_type == 'المؤسسات والشركات' ? 'selected' : '' }}> المؤسسات والشركات</option>
                        <option value="القطاع غير الربحي" {{ $info->contact_type == 'القطاع غير الربحي' ? 'selected' : '' }}> القطاع غير الربحي</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الهاتف</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->mobile }}" name="mobile" id="mobile" readonly class="form-control" placeholder="الهاتف">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الهاتف البديل</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->another_mobile }}" name="another_mobile" readonly id="another_mobile" class="form-control" placeholder="الهاتف البديل">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الشخص المسؤول</label>
                <div class="col-md-6">
                    <input type="text" value="{{ $info->master }}" readonly name="master" id="master" class="form-control">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الايميل</label>
                <div class="col-md-6">
                    <input type="email" value="{{ $info->email }}" readonly name="email" id="email" placeholder="someone@hotmail.com" class="form-control" data-role="emailinput">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">تاريخ التنبيه</label>
                <div class="col-md-6">
                    <input type="date" class="form-control" readonly placeholder="remember_date" name="remember_date" value="{{ $info->remember_date }}">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اضف ملاحظة</label>
                <div class="col-md-9">
                    <textarea style="resize: none;" readonly id="notes" rows="8" class="form-control contact_note" name="notes">{!! strip_tags($info->notes) !!}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('contact.view') }}" class="btn btn-light">عودة</a>
            </div>
        </form>
    </div>
</div>
@endsection
