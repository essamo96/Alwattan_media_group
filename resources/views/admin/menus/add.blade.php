@extends('layouts.admin')

@section('title', 'اضافة قائمة جديدة')

@section('page-title')
قوائم الموقع
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('menus.view') }}" class="text-muted text-hover-primary">ادارة قوائم الموقع</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">اضافة قائمة</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">اضافة قائمة</div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الاسم بالعربي</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('name_ar') }}" name="name_ar" class="form-control" placeholder="الاسم بالعربي">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الاسم بالانجليزي</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('name_en') }}" name="name_en" class="form-control" placeholder="الاسم بالانجليزي">
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">القائمة الاب</label>
                <div class="col-md-6">
                    <select name="parent_id" id="parent_id" class="form-select">
                        <option value="0" data-next-sort="{{ $next_sort }}" {{ old('parent_id', 0) == 0 ? 'selected' : '' }}>لا يوجد - قائمة رئيسية</option>
                        @foreach($parent_menus as $item)
                        <option value="{{ $item->id }}" data-next-sort="{{ (new \App\Models\Menus())->getNextSort($item->id) }}" {{ old('parent_id') == $item->id ? 'selected' : '' }}> {{ $item->name_ar }} </option>
                        @endforeach
                    </select>
                    <div class="form-text">اختر قائمة رئيسية لتظهر هذه القائمة كعنصر منسدل تحتها</div>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">المسار</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('url', '#') }}" name="url" class="form-control" placeholder="#section-hero او https://example.com">
                    <div class="form-text">المسارات التي تبدأ بـ # تعتبر أقسام داخل الصفحة الرئيسية. للقوائم التي تُستخدم فقط لفتح قائمة فرعية اترك # كما هي</div>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الايقونة</label>
                <div class="col-md-6">
                    <input type="text" value="{{ old('icon') }}" name="icon" class="form-control" placeholder="fa fa-home">
                    <div class="form-text">كلاس الايقونة (Font Awesome) الظاهرة بجانب اسم القائمة</div>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">اللوجو (اختياري)</label>
                <div class="col-md-6">
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-text">في حال رفع لوجو سيتم عرضه بدلاً من الايقونة</div>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">فتح الرابط</label>
                <div class="col-md-6">
                    <select name="target" class="form-select">
                        <option value="_self" {{ old('target') == '_self' ? 'selected' : '' }}>في نفس الصفحة</option>
                        <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>في صفحة جديدة</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <label class="col-md-3 col-form-label">الترتيب</label>
                <div class="col-md-6">
                    <input type="number" id="sort" value="{{ old('sort', $next_sort) }}" name="sort" class="form-control" placeholder="الترتيب">
                    <div class="form-text">الترتيب مستقل لكل قائمة اب: يتحدد تلقائياً حسب القائمة الاب المختارة اعلاه</div>
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
                <a href="{{ route('menus.view') }}" class="btn btn-light me-3">الغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        // الترتيب مستقل داخل كل قائمة اب، فيُحدَّث تلقائياً عند تغيير الاب
        $('#parent_id').on('change', function () {
            var nextSort = $(this).find(':selected').data('next-sort');
            if (typeof nextSort === 'undefined') {
                nextSort = 1;
            }
            $('#sort').val(nextSort);
        });
    });
</script>
@endpush
