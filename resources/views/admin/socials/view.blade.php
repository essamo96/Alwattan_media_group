@extends('layouts.admin')

@section('title', 'إدارة الشبكات الإجتماعية')

@section('page-title')
الشبكات الإجتماعية
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إدارة الشبكات الإجتماعية</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ki-duotone ki-share fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
            إدارة الشبكات الإجتماعية
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')

        @if(count($socials) === 0)
            <div class="alert alert-warning d-flex align-items-center mb-5">
                <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-warning">لا توجد منصات مسجّلة</h4>
                    <span>شغّل الأمر: <code>php artisan db:seed --class=SocialsSeeder</code> لإضافة المنصات الافتراضية، ثم عدّل الروابط من هنا.</span>
                </div>
            </div>
        @else
        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4 mb-8">
            <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-stack flex-grow-1">
                <div class="fw-semibold">
                    <div class="fs-6 text-gray-700">
                        الروابط الظاهرة في فوتر الموقع الخارجي تُقرأ من هنا. قيمة الأيقونة = اسم Font Awesome 4 بدون بادئة
                        (مثال: <code>facebook</code> أو <code>instagram</code>).
                    </div>
                </div>
            </div>
        </div>

        <form role="form" method="post" action="" class="">
            @csrf
            <div class="row mb-3 fw-bold text-muted d-none d-md-flex">
                <div class="col-md-3">المنصة</div>
                <div class="col-md-4">الرابط</div>
                <div class="col-md-3">الأيقونة</div>
                <div class="col-md-2">مفعّل</div>
            </div>
            @foreach($socials as $row)
            <div class="row mb-5 align-items-center">
                <div class="col-md-3">
                    <label class="form-label d-md-none">المنصة</label>
                    <input type="text" value="{{ $row->name }}" class="form-control form-control-solid" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label d-md-none">الرابط</label>
                    <input type="url" value="{{ $row->link }}" name="link[{{ $row->id }}]" class="form-control form-control-solid" placeholder="https://...">
                </div>
                <div class="col-md-3">
                    <label class="form-label d-md-none">الأيقونة</label>
                    <input type="text" value="{{ $row->icon }}" name="icon[{{ $row->id }}]" class="form-control form-control-solid" placeholder="facebook">
                </div>
                <div class="col-md-2">
                    <label class="form-label d-md-none">مفعّل</label>
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input type="hidden" value="0" name="status[{{ $row->id }}]">
                        <input class="form-check-input" type="checkbox" value="1" name="status[{{ $row->id }}]" {{ $row->status == 1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ $row->id }}" name="id[]">
            @endforeach

            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
