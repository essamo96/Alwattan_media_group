@extends('layouts.admin')

@section('title', 'إضافة عقار')

@section('page-title')
العقارات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('properties.view') }}" class="text-muted text-hover-primary">إدارة العقارات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة عقار</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">إضافة عقار</div>
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
                        <label class="col-md-3 col-form-label">القسم</label>
                        <div class="col-md-6">
                            <select name="category_id" class="form-select">
                                <option value="">اختر القسم</option>
                                @foreach($series as $sr)
                                <option value="{{ $sr->id }}" {{ old('category_id') == $sr->id ? 'selected' : '' }}>{{ $sr->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">نوع العقار</label>
                        <div class="col-md-6">
                            <select name="property_type" class="form-select">
                                <option value="">اختر النوع</option>
                                @foreach($types as $sr)
                                <option value="{{ $sr->id }}" {{ old('property_type') == $sr->id ? 'selected' : '' }}>{{ $sr->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">المدينة</label>
                        <div class="col-md-6">
                            <select name="city" class="form-select">
                                <option value="">اختر المدينة</option>
                                @foreach($cities as $sr)
                                <option value="{{ $sr->id }}" {{ old('city') == $sr->id ? 'selected' : '' }}>{{ $sr->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">السعر</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('price') }}" name="price" class="form-control" placeholder="السعر">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">المساحة</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('area') }}" name="area" class="form-control" placeholder="المساحة">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عدد الحمامات</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('bathroom') }}" name="bathroom" class="form-control" placeholder="عدد الحمامات">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عدد الغرف</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('bedroom') }}" name="bedroom" class="form-control" placeholder="عدد الغرف">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">العائد السنوي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('annual_return') }}" name="annual_return" class="form-control" placeholder="العائد السنوي">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="col-form-label">latitude</label>
                            <input type="number" id="latitude" name="latitude" class="form-control" value="{{ old('latitude') }}" step="0.00000001">
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">longitude</label>
                            <input type="number" id="longitude" name="longitude" class="form-control" value="{{ old('longitude') }}" step="0.00000001">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12" style="height: 300px;">
                            <div id="map"></div>
                        </div>
                    </div>
                    <div class="row mb-5" id="image">
                        <label class="col-md-3 col-form-label">صورة العقار</label>
                        <div class="col-md-5">
                            <input id="thumbnail" value="" class="form-control" type="text" name="image" style="direction: ltr;" readonly>
                            <img id="holder" style="margin-top:15px;max-height:100px;">
                        </div>
                        <div class="col-md-1">
                            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="ki-duotone ki-picture fs-5"><span class="path1"></span><span class="path2"></span></i> حدد صورة
                            </a>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="col-md-3 col-form-label">عقار جديد</label>
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" name="is_new" {{ old('is_new') == 1 ? 'checked' : '' }}>
                            </div>
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
                            <textarea name="{{ $item->prefix }}_details" id="{{ $item->prefix }}_details" class="form-control" rows="3">{{ old($item->prefix.'_details') }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-5">
                <a href="{{ route('properties.view') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css" rel="stylesheet">
<style>
    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js"></script>
<script src="vendor/laravel-filemanager/js/lfm.js"></script>
<script src="{{ asset_v('assets/metronic/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script>
var domain = "{{ asset('/admin').'/file_manager' }}";
$('#lfm').filemanager('image', {prefix: domain});
@foreach($languages as $item)
ClassicEditor.create(document.querySelector('#{{ $item->prefix }}_details'), { language: '{{ $item->prefix }}' }).catch(error => console.error(error));
@endforeach
mapboxgl.accessToken = '{{ config('services.mapbox.token') }}';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-0.11462688775253582, 51.526867517823575],
    zoom: 7
});


map.on('style.load', function () {
    map.on('click', function (e) {
        var coordinates = e.lngLat;
        $('#longitude').val(coordinates.lng.toFixed(5));
        $('#latitude').val(coordinates.lat.toFixed(5));
    });
});
</script>
@endpush
