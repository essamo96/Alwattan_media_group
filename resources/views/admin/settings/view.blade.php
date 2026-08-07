@extends('layouts.admin')

@section('title', 'الإعدادات')

@section('page-title')
الإعدادات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إعدادات الموقع</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ki-duotone ki-setting-2 fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
            إعدادات الموقع
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form method="post" action="" role="form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الإسم عربي</label>
                    <div class="col-md-9">
                        <input type="text" value="{{ $info->title_ar }}" name="title_ar" id="title" class="form-control" placeholder="الإسم">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الإسم انجليزي</label>
                    <div class="col-md-9">
                        <input type="text" value="{{ $info->title_en }}" name="title_en" id="title" class="form-control" placeholder="name">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الوصف عربي</label>
                    <div class="col-md-9">
                        <textarea name="description_ar" id="descs" class="form-control" rows="6" style="resize: none">{{ $info->description_ar }}</textarea>
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الوصف انجليزي</label>
                    <div class="col-md-9">
                        <textarea name="description_en" id="descs_en" class="form-control" rows="6" style="resize: none">{{ $info->description_en }}</textarea>
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الكلمات الدلالية عربي</label>
                    <div class="col-md-6">
                        <input type="text" value="{{ $info->tags_ar }}" name="tags_ar" id="tags" class="form-control" data-role="tagsinput">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الكلمات الدلالية انجليزي</label>
                    <div class="col-md-6">
                        <input type="text" value="{{ $info->tags_en }}" name="tags_en" id="tags" class="form-control" data-role="tagsinput">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">رقم هاتف1</label>
                    <div class="col-md-9">
                        <input type="text" value="{{ $info->phone1 }}" name="phone1" id="phone1" class="form-control" placeholder="رقم هاتف1">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">رقم هاتف2</label>
                    <div class="col-md-9">
                        <input type="text" value="{{ $info->phone2 }}" name="phone2" id="phone2" class="form-control" placeholder="رقم هاتف2">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3">
                        <label class="col-form-label">مشاريع مكتملة</label>
                        <input type="text" value="{{ $info->projects }}" name="projects" id="phone1" class="form-control" placeholder="مشاريع مكتملة">
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">الافكار</label>
                        <input type="text" value="{{ $info->idea }}" name="idea" id="phone1" class="form-control" placeholder="الافكار">
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">سنوات الخبرة</label>
                        <input type="text" value="{{ $info->experinace }}" name="experinace" id="phone2" class="form-control" placeholder="سنوات الخبرة">
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">الجوائز</label>
                        <input type="text" value="{{ $info->award }}" name="award" id="phone2" class="form-control" placeholder="الجوائز">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">العنوان</label>
                    <div class="col-md-6">
                        <input type="text" value="{{ $info->address }}" name="address" id="address" class="form-control" placeholder="العنوان">
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">بريد التواصل</label>
                    <div class="col-md-6">
                        <input type="text" value="{{ $info->contact_email }}" name="contact_email" id="contact_email" class="form-control" placeholder="contact_email">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
