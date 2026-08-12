{{-- صندوق رفع صورة واحد مع معاينة حية، يُستخدم مرتين (عربي/انجليزي) بنفس النموذج --}}
@php
    $previewUrl = $existing ? asset('uploads/achievements/' . $existing) : null;
@endphp
<div class="achievement-dropzone">
    <label for="{{ $field }}_input"
           class="d-flex flex-column align-items-center justify-content-center text-center rounded border border-dashed p-4 cursor-pointer position-relative overflow-hidden"
           style="min-height:160px;background:#f9f9f9;"
           id="{{ $field }}_dropzone">
        <img id="{{ $field }}_preview_img"
             src="{{ $previewUrl }}"
             class="{{ $previewUrl ? '' : 'd-none' }} position-absolute top-0 start-0 w-100 h-100"
             style="object-fit:cover;">
        <div id="{{ $field }}_placeholder" class="{{ $previewUrl ? 'd-none' : '' }}">
            <i class="ki-duotone ki-picture fs-3x text-muted mb-2 d-block"><span class="path1"></span><span class="path2"></span></i>
            <div class="text-muted fs-7">اضغط لاختيار صورة</div>
        </div>
        <span class="{{ $previewUrl ? '' : 'd-none' }} position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white fs-8 py-1">
            اضغط لتغيير الصورة
        </span>
    </label>
    <input type="file" name="{{ $field }}" id="{{ $field }}_input" class="d-none achievement-image-input" accept="image/*" {{ $required ? 'required' : '' }} data-preview="{{ $field }}_preview_img" data-placeholder="{{ $field }}_placeholder">
</div>
