{{-- نموذج مشترك للإضافة والتعديل --}}
<form role="form" method="post" action="{{ $formAction }}" enctype="multipart/form-data" id="achievement_form">
    @csrf
    @include('admin.layout.error')

    <div class="row g-5">
        <div class="col-lg-8">
            {{-- البيانات الأساسية --}}
            <div class="card mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ki-duotone ki-note-2 fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                        <span class="fw-bold">البيانات الأساسية</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label for="title" class="form-label">عنوان الإنجاز <span class="text-danger">*</span></label>
                        <input type="text" value="{{ old('title', $info->title ?? '') }}" name="title" id="title" class="form-control form-control-solid" placeholder="مثال: افتتاح مركز اعلامي جديد" required maxlength="200">
                    </div>

                    <div class="mb-6">
                        <label for="short_description" class="form-label">الوصف المختصر <span class="text-danger">*</span></label>
                        <textarea name="short_description" id="short_description" rows="2" class="form-control form-control-solid" placeholder="جملة أو جملتان تظهر بمعاينة الإنجاز" maxlength="500" required>{{ old('short_description', $info->short_description ?? '') }}</textarea>
                        <div class="form-text">يظهر هذا الوصف بقوائم/بطاقات الانجازات المختصرة. <span id="short_desc_count">0</span>/500</div>
                    </div>

                    <div class="mb-2">
                        <label for="long_description" class="form-label">الوصف التفصيلي <span class="text-danger">*</span></label>
                        <textarea name="long_description" id="long_description" rows="8" class="form-control form-control-solid" placeholder="التفاصيل الكاملة للإنجاز" required>{{ old('long_description', $info->long_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- الوسوم --}}
            <div class="card mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ki-duotone ki-tag fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                        <span class="fw-bold">الوسوم (Tags)</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="tags_chip_box" class="d-flex flex-wrap gap-2 align-items-center border border-dashed rounded p-3 mb-2">
                        <input type="text" id="tag_input" class="form-control form-control-solid border-0 flex-grow-1 w-auto" style="min-width:180px;box-shadow:none;" placeholder="اكتب وسماً واضغط Enter أو فاصلة">
                    </div>
                    <input type="hidden" name="tags" id="tags_hidden" value="{{ old('tags', $info->tags ?? '') }}">
                    <div class="form-text">مثال: إعلام، تطوير، تدريب — تُستخدم للفلترة والبحث لاحقاً.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- النشر --}}
            <div class="card mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ki-duotone ki-setting-2 fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                        <span class="fw-bold">النشر</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label for="sort_order" class="form-label">ترتيب الظهور</label>
                        <input type="number" value="{{ old('sort_order', $info->sort_order ?? 0) }}" name="sort_order" id="sort_order" class="form-control form-control-solid">
                        <div class="form-text">الأصغر يظهر أولاً.</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="form-label mb-0">الحالة</label>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" name="status" {{ old('status', $info->status ?? 1) == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- صورة النسخة العربية --}}
            <div class="card mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ki-duotone ki-picture fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                        <span class="fw-bold">صورة الإنجاز (عربي)</span>
                        @if(!$info) <span class="text-danger ms-1">*</span> @endif
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.achievements.parts.image-dropzone', ['field' => 'image_ar', 'existing' => $info->image_ar ?? null, 'required' => !$info])
                </div>
            </div>

            {{-- صورة النسخة الانجليزية --}}
            <div class="card mb-5">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ki-duotone ki-picture fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                        <span class="fw-bold">صورة الإنجاز (English)</span>
                        @if(!$info) <span class="text-danger ms-1">*</span> @endif
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.achievements.parts.image-dropzone', ['field' => 'image_en', 'existing' => $info->image_en ?? null, 'required' => !$info])
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-2 mb-10">
        <a href="{{ route('achievements.view') }}" class="btn btn-light me-3">إلغاء</a>
        <button type="submit" class="btn btn-primary" id="achievement_submit_btn">
            <i class="ki-duotone ki-check fs-2"></i> {{ $submitLabel }}
        </button>
    </div>
</form>
