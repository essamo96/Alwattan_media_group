{{--
  منتقي صور موحّد بأسلوب Metronic image-input + Laravel File Manager.
  الاستخدام:
  @include('admin.partials.image-picker', [
      'name' => 'image',
      'label' => 'الصورة',
      'value' => old('image', $info->image ?? ''),
      'preview' => $previewUrl ?? null,          // رابط معاينة اختياري
      'folderHint' => 'sliders',                 // تلميح المجلد داخل مدير الملفات
      'required' => false,
      'colLabel' => 'col-md-3',
      'colField' => 'col-md-6',
  ])
--}}
@php
    $name = $name ?? 'image';
    $label = $label ?? 'الصورة';
    $inputId = $inputId ?? ($name . '_path');
    $previewId = $previewId ?? ($name . '_preview');
    $btnId = $btnId ?? ($name . '_lfm');
    $value = $value ?? old($name, '');
    $preview = $preview ?? null;
    $required = !empty($required);
    $colLabel = $colLabel ?? 'col-md-3';
    $colField = $colField ?? 'col-md-6';
    $help = $help ?? 'اختر صورة من مدير الملفات أو ارفع صورة جديدة من نافذة المدير.';

    if (!$preview && !empty($value)) {
        $preview = \App\Support\MediaUpload::previewUrl($value, $fallbackFolder ?? null);
    }
@endphp
<div class="row mb-5 admin-image-picker" data-lfm-type="image">
    <label class="{{ $colLabel }} col-form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    <div class="{{ $colField }}">
        <div class="image-input image-input-outline {{ $preview ? '' : 'image-input-empty' }}" data-kt-image-input="true">
            <div class="image-input-wrapper w-125px h-125px admin-image-preview-wrap"
                 id="{{ $previewId }}_wrap"
                 @if($preview) style="background-image: url('{{ $preview }}');" @endif></div>

            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                   data-kt-image-input-action="change" data-bs-toggle="tooltip" title="اختيار من مدير الملفات"
                   id="{{ $btnId }}"
                   data-input="{{ $inputId }}"
                   data-preview="{{ $previewId }}">
                <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
            </label>

            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow admin-image-picker-clear"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="إلغاء"
                  data-input="{{ $inputId }}" data-preview="{{ $previewId }}" data-wrap="{{ $previewId }}_wrap">
                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span>

            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow admin-image-picker-clear"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="إزالة"
                  data-input="{{ $inputId }}" data-preview="{{ $previewId }}" data-wrap="{{ $previewId }}_wrap">
                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span>
        </div>

        <div class="input-group mt-4">
            <input type="text"
                   class="form-control form-control-solid"
                   name="{{ $name }}"
                   id="{{ $inputId }}"
                   value="{{ $value }}"
                   placeholder="مسار الصورة"
                   {{ $required ? 'required' : '' }}
                   readonly>
            <button type="button"
                    class="btn btn-primary"
                    id="{{ $btnId }}_btn"
                    data-input="{{ $inputId }}"
                    data-preview="{{ $previewId }}"
                    data-lfm-open="1">
                <i class="ki-duotone ki-folder-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                مدير الملفات
            </button>
        </div>

        <img id="{{ $previewId }}" src="{{ $preview ?: '' }}" alt=""
             class="d-none admin-lfm-preview-img"
             data-wrap="{{ $previewId }}_wrap">

        <div class="form-text">{{ $help }}</div>
    </div>
</div>
