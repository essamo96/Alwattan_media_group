@extends('layouts.admin')

@section('title', 'تعديل مقولة')

@section('page-title')
قالوا عنا
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('testimonials.view') }}" class="text-muted text-hover-primary">قالوا عنا</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">تعديل مقولة</div>
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
                        <label class="col-md-3 col-form-label">الترتيب</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->p_order }}" name="p_order" id="p_order" class="form-control" placeholder="الترتيب">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الصورة</label>
                        <div class="col-md-6">
                            <input type="file" name="image" class="form-control" placeholder="الصورة">
                            <img src="{{ url($info->image) }}" style="margin-top:15px;max-height:100px;">
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
                </div>

                @foreach($languages as $item)
                <div class="tab-pane fade" id="tab_{{ $loop->iteration }}">
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الاسم</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->translate($item->prefix) ? $info->translate($item->prefix)->name : '' }}" name="{{ $item->prefix }}_name" class="form-control" placeholder="الاسم">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">العنوان</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->translate($item->prefix) ? $info->translate($item->prefix)->title : '' }}" name="{{ $item->prefix }}_title" class="form-control" placeholder="العنوان">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">الوصف</label>
                        <div class="col-md-6">
                            <textarea name="{{ $item->prefix }}_descs" id="{{ $item->prefix }}_descs" class="form-control" rows="3">{{ $info->translate($item->prefix) ? $info->translate($item->prefix)->descs : '' }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('testimonials.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset_v('assets/metronic/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript">
    @foreach($languages as $item)
    ClassicEditor.create(document.querySelector('#{{ $item->prefix }}_descs'), { language: '{{ $item->prefix }}' }).catch(error => console.error(error));
    @endforeach
</script>
@endpush
