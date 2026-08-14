@extends('layouts.admin')

@section('title', 'إضافة خدمة')

@section('page-title')
الخدمات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('services.view') }}" class="text-muted text-hover-primary">إدارة الخدمات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة خدمة جديدة</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة خدمة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <ul class="nav nav-tabs mb-5">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_0">البيانات الاساسية</a>
                </li>
                @foreach($languages as $item)
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_{{ $loop->iteration }}">{{ $item->name }}</a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab_0">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">ايقونة الخدمة</label>
                        <div class="col-md-5">
                            <input type="text" value="{{ old('image') }}" name="image" id="image" class="form-control" placeholder="ايقونة الخدمة" readonly>
                        </div>
                        <div class="col-md-1">
                            <a id="lfm" data-input="image" class="btn btn-primary">
                                <i class="ki-duotone ki-picture fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">رابط الخدمة</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('url') }}" name="url" id="url" class="form-control" placeholder="رابط الخدمة">
                        </div>
                    </div>
                </div>

                @foreach($languages as $item)
                <div class="tab-pane fade" id="tab_{{ $loop->iteration }}">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">العنوان</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old($item->prefix.'_title') }}" name="{{ $item->prefix }}_title" class="form-control" placeholder="العنوان">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">التفاصيل</label>
                        <div class="col-md-6">
                            <textarea name="{{ $item->prefix }}_details" id="{{ $item->prefix }}_details" class="form-control ckeditor" data-lang="{{ $item->prefix }}" rows="3">{{ old($item->prefix.'_details') }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('services.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
var domain = "{{ asset('/admin').'/file_manager' }}";
$('#lfm').filemanager('image', {prefix: domain});
</script>
@endpush
