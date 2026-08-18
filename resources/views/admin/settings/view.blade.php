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
            <hr class="my-8">
            <h4 class="mb-5">العلامة المائية</h4>
            <div class="row">
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">تفعيل العلامة المائية</label>
                    <div class="col-md-6">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" name="watermark_enabled" {{ $info->watermark_enabled == 1 ? 'checked' : '' }}>
                        </div>
                        <div class="form-text">تُطبع على الصور تلقائياً، وتُعرض كطبقة فوق الفيديوهات (المرفوعة أو الروابط الخارجية) في الواجهة.</div>
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">شعار العلامة المائية</label>
                    <div class="col-md-6">
                        <input type="file" name="watermark_logo" id="wm_logo_input" class="form-control" accept="image/png,image/webp">
                        <div class="form-text">يفضّل PNG بخلفية شفافة.</div>
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">مكان العلامة المائية</label>
                    <div class="col-md-6">
                        <select name="watermark_position" id="wm_position" class="form-select">
                            @php
                                $positions = [
                                    'top-left' => 'أعلى اليسار', 'top' => 'أعلى المنتصف', 'top-right' => 'أعلى اليمين',
                                    'left' => 'وسط اليسار', 'center' => 'المنتصف', 'right' => 'وسط اليمين',
                                    'bottom-left' => 'أسفل اليسار', 'bottom' => 'أسفل المنتصف', 'bottom-right' => 'أسفل اليمين',
                                ];
                            @endphp
                            @foreach($positions as $value => $label)
                            <option value="{{ $value }}" {{ $info->watermark_position == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">أو اضغط مباشرة على مكان العلامة داخل شاشة المعاينة بالأسفل.</div>
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">الشفافية (%)</label>
                    <div class="col-md-3">
                        <input type="number" min="10" max="100" value="{{ $info->watermark_opacity ?? 70 }}" name="watermark_opacity" id="wm_opacity" class="form-control">
                    </div>
                    <label class="col-md-2 col-form-label">الحجم (%)</label>
                    <div class="col-md-3">
                        <input type="number" min="5" max="50" value="{{ $info->watermark_size ?? 15 }}" name="watermark_size" id="wm_size" class="form-control">
                        <div class="form-text">نسبة من عرض الصورة/المشغل.</div>
                    </div>
                </div>
                <div class="row mb-5">
                    <label class="col-md-3 col-form-label">معاينة</label>
                    <div class="col-md-9">
                        <div id="wm_stage" style="position:relative;max-width:520px;aspect-ratio:16/9;border-radius:8px;overflow:hidden;border:1px solid #dee2e6;background:#20232a url('{{ asset('assets/front/images/bg/3.jpg') }}') center/cover no-repeat;">
                            <div id="wm_grid" style="position:absolute;inset:0;display:grid;grid-template-columns:repeat(3,1fr);grid-template-rows:repeat(3,1fr);">
                                @php
                                    $wm_grid_positions = ['top-left','top','top-right','left','center','right','bottom-left','bottom','bottom-right'];
                                @endphp
                                @foreach($wm_grid_positions as $pos)
                                <button type="button" class="wm-zone" data-position="{{ $pos }}" title="اضغط لوضع العلامة هنا" style="border:1px dashed transparent;background:transparent;cursor:pointer;transition:background .15s;"></button>
                                @endforeach
                            </div>
                            <img id="wm_preview_logo" src="{{ \App\Support\MediaUpload::previewUrl($info->watermark_logo) }}" style="position:absolute;pointer-events:none;{{ $info->watermark_logo ? '' : 'display:none;' }}">
                            <div id="wm_no_logo" class="d-flex align-items-center justify-content-center h-100 text-white-50" style="{{ $info->watermark_logo ? 'display:none;' : '' }}">اختر شعاراً لمعاينته هنا</div>
                        </div>
                        <div class="form-text">صورة توضيحية فقط لمعاينة الشكل — النتيجة الفعلية تُطبّق على صور وفيديوهات الأخبار كما اخترتها هنا.</div>
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

@push('scripts')
<script type="text/javascript">
(function () {
    var stage = document.getElementById('wm_stage');
    var logo = document.getElementById('wm_preview_logo');
    var noLogo = document.getElementById('wm_no_logo');
    var positionSelect = document.getElementById('wm_position');
    var opacityInput = document.getElementById('wm_opacity');
    var sizeInput = document.getElementById('wm_size');
    var logoInput = document.getElementById('wm_logo_input');
    var zones = document.querySelectorAll('.wm-zone');

    var posStyles = {
        'top-left': {top: '0', left: '0', right: 'auto', bottom: 'auto', transform: 'none'},
        'top': {top: '0', left: '50%', right: 'auto', bottom: 'auto', transform: 'translateX(-50%)'},
        'top-right': {top: '0', right: '0', left: 'auto', bottom: 'auto', transform: 'none'},
        'left': {top: '50%', left: '0', right: 'auto', bottom: 'auto', transform: 'translateY(-50%)'},
        'center': {top: '50%', left: '50%', right: 'auto', bottom: 'auto', transform: 'translate(-50%,-50%)'},
        'right': {top: '50%', right: '0', left: 'auto', bottom: 'auto', transform: 'translateY(-50%)'},
        'bottom-left': {bottom: '0', left: '0', right: 'auto', top: 'auto', transform: 'none'},
        'bottom': {bottom: '0', left: '50%', right: 'auto', top: 'auto', transform: 'translateX(-50%)'},
        'bottom-right': {bottom: '0', right: '0', left: 'auto', top: 'auto', transform: 'none'},
    };

    function applyMargin() {
        logo.style.margin = '3%';
    }

    function updatePosition() {
        var pos = positionSelect.value || 'bottom-right';
        var style = posStyles[pos] || posStyles['bottom-right'];
        logo.style.top = style.top || 'auto';
        logo.style.bottom = style.bottom || 'auto';
        logo.style.left = style.left || 'auto';
        logo.style.right = style.right || 'auto';
        logo.style.transform = style.transform || 'none';
        applyMargin();

        zones.forEach(function (z) {
            z.style.background = z.getAttribute('data-position') === pos ? 'rgba(13,110,253,.15)' : 'transparent';
            z.style.borderColor = z.getAttribute('data-position') === pos ? 'rgba(13,110,253,.6)' : 'transparent';
        });
    }

    function updateOpacity() {
        var val = parseInt(opacityInput.value, 10);
        if (isNaN(val)) {
            val = 70;
        }
        logo.style.opacity = Math.max(0, Math.min(100, val)) / 100;
    }

    function updateSize() {
        var val = parseInt(sizeInput.value, 10);
        if (isNaN(val)) {
            val = 15;
        }
        logo.style.width = Math.max(5, Math.min(50, val)) + '%';
    }

    zones.forEach(function (zone) {
        zone.addEventListener('click', function () {
            positionSelect.value = zone.getAttribute('data-position');
            updatePosition();
        });
    });

    positionSelect.addEventListener('change', updatePosition);
    opacityInput.addEventListener('input', updateOpacity);
    sizeInput.addEventListener('input', updateSize);

    logoInput.addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (!file) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function (ev) {
            logo.src = ev.target.result;
            logo.style.display = '';
            noLogo.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    updatePosition();
    updateOpacity();
    updateSize();
})();
</script>
@endpush
