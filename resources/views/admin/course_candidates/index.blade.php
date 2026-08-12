@extends('layouts.admin')

@section('title', 'قائمة المرشحين')

@section('page-title')
الدورات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">قائمة المرشحين</li>
@endsection

@section('content')
<div class="card mb-5">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">فلاتر البحث</h3>
        </div>
        <div class="card-toolbar">
            @can('admin.sms.send')
            <a href="#" id="sms_bulk_btn" class="btn btn-info me-3">
                <i class="ki-duotone ki-message-text-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> إرسال SMS
            </a>
            @endcan
            <a href="#" id="import_btn" class="btn btn-warning me-3" data-bs-toggle="modal" data-bs-target="#import_modal">
                <i class="ki-duotone ki-file-up fs-2"><span class="path1"></span><span class="path2"></span></i> استيراد من Excel
            </a>
            <a href="#" id="export_btn" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#export_columns_modal">
                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> تصدير Excel
            </a>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label">الدورة</label>
                <select name="course_id" id="course_filter" class="form-select form-select-solid">
                    <option value="">كل الدورات</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control form-control-solid filter-field" placeholder="بحث بالاسم">
            </div>
            <div class="col-md-3">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-select form-select-solid filter-field">
                    <option value="">الكل</option>
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الجامعة</label>
                <input type="text" name="university" class="form-control form-control-solid filter-field" placeholder="الجامعة">
            </div>
            <div class="col-md-3">
                <label class="form-label">نوع المسجل</label>
                <select name="citizen_type" class="form-select form-select-solid filter-field">
                    <option value="">الكل</option>
                    <option value="citizen">مواطن</option>
                    <option value="refugee">لاجئ</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">العمر من</label>
                <input type="number" min="0" name="age_from" class="form-control form-control-solid filter-field" placeholder="من">
            </div>
            <div class="col-md-3">
                <label class="form-label">العمر إلى</label>
                <input type="number" min="0" name="age_to" class="form-control form-control-solid filter-field" placeholder="إلى">
            </div>
            <div class="col-md-3">
                <label class="form-label">العلامة من</label>
                <input type="number" step="0.01" name="gpa_from" class="form-control form-control-solid filter-field" placeholder="من">
            </div>
            <div class="col-md-3">
                <label class="form-label">العلامة إلى</label>
                <input type="number" step="0.01" name="gpa_to" class="form-control form-control-solid filter-field" placeholder="إلى">
            </div>
            <div class="col-md-3">
                <label class="form-label">التخصص العام</label>
                <input type="text" name="general_specialization" class="form-control form-control-solid filter-field" placeholder="التخصص العام">
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة الاجتماعية</label>
                <select name="marital_status" class="form-select form-select-solid filter-field">
                    <option value="">الكل</option>
                    <option value="single">أعزب</option>
                    <option value="married">متزوج</option>
                    <option value="divorced">مطلق</option>
                    <option value="widowed">أرمل</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">سنة التخرج من</label>
                <input type="number" name="graduation_year_from" class="form-control form-control-solid filter-field" placeholder="من">
            </div>
            <div class="col-md-3">
                <label class="form-label">سنة التخرج إلى</label>
                <input type="number" name="graduation_year_to" class="form-control form-control-solid filter-field" placeholder="إلى">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" id="reset_filters" class="btn btn-light w-100">تصفير الفلاتر</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body pt-6">
        @include('admin.layout.error')
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed table-hover fs-6 gy-5 w-100" id="all_candidates_table" style="width:100%">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>المتقدم</th>
                        <th>الدورة</th>
                        <th>الجامعة</th>
                        <th>العلامة</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modals')
@include('admin.partials.sms-compose-modal')

<div class="modal fade" id="export_columns_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">اختيار وترتيب أعمدة التصدير</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-muted fs-7 mb-4">فعّل الأعمدة المطلوبة، واسحب <i class="ki-duotone ki-arrow-mix fs-6"></i> لإعادة ترتيبها كما تريدها بالملف. سيتم تصدير النتائج المطابقة للفلاتر الحالية.</div>
                <ul id="export_columns_list" class="list-group">
                    @foreach($export_columns as $key => $col)
                    <li class="list-group-item d-flex align-items-center px-3 py-2" draggable="true" data-key="{{ $key }}" style="cursor:move;">
                        <i class="ki-duotone ki-arrow-mix fs-3 text-muted me-3"><span class="path1"></span><span class="path2"></span></i>
                        <div class="form-check form-check-custom form-check-solid flex-grow-1">
                            <input class="form-check-input export-col-checkbox" type="checkbox" value="{{ $key }}" id="export_col_{{ $key }}" checked>
                            <label class="form-check-label w-100" for="export_col_{{ $key }}">{{ $col['label'] }}</label>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" id="export_select_all_btn" class="btn btn-light me-auto">تحديد الكل</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="export_confirm_btn" class="btn btn-success">
                    <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> تصدير
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">استيراد مرشحين من ملف Excel</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="import_step_1">
                    <div class="row g-4 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">الدورة <span class="text-danger">*</span></label>
                            <select id="import_course_id" class="form-select">
                                <option value="">-- اختر الدورة --</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ملف Excel <span class="text-danger">*</span></label>
                            <input type="file" id="import_file" class="form-control" accept=".xlsx,.xls,.csv">
                        </div>
                    </div>
                    <div class="text-muted fs-7">
                        الملف لازم يحتوي صف عناوين وعمود واحد على الأقل من: <strong>رقم الجوال</strong> أو <strong>البريد الإلكتروني</strong>
                        (وممكن عمود <strong>الاسم</strong> اختيارياً). سيتم مطابقة كل صف مع المسجلين بالنظام عبر الجوال أو البريد.
                    </div>
                </div>

                <div id="import_step_2" class="d-none">
                    <div id="import_summary" class="alert alert-light-info mb-4"></div>

                    <div class="mb-4">
                        <h5 class="text-success">
                            <i class="ki-duotone ki-plus-circle fs-3"><span class="path1"></span><span class="path2"></span></i>
                            سيُضاف للمرشحين (<span id="import_add_count">0</span>)
                        </h5>
                        <div id="import_add_list" class="border rounded p-3" style="max-height:180px;overflow-y:auto;"></div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-danger">
                            <i class="ki-duotone ki-minus-circle fs-3"><span class="path1"></span><span class="path2"></span></i>
                            سيُحذف من المرشحين (<span id="import_remove_count">0</span>)
                        </h5>
                        <div id="import_remove_list" class="border rounded p-3" style="max-height:180px;overflow-y:auto;"></div>
                    </div>

                    <div class="mb-2" id="import_not_found_wrap">
                        <h5 class="text-warning">
                            <i class="ki-duotone ki-information-5 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            غير موجودين بالنظام (<span id="import_not_found_count">0</span>) - لن تتم إضافتهم
                        </h5>
                        <div id="import_not_found_list" class="border rounded p-3" style="max-height:150px;overflow-y:auto;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="import_preview_btn" class="btn btn-primary">
                    <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> معاينة الفرق
                </button>
                <button type="button" id="import_confirm_btn" class="btn btn-danger d-none">
                    <i class="ki-duotone ki-check fs-2"></i> تأكيد المزامنة
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="{{ asset_v('assets/metronic/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset_v('assets/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset_v('assets/admin/global/scripts/sms-compose.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var oTable = $('#all_candidates_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "language": {
                "processing": "جاري التحميل...",
                "lengthMenu": "إظهار _MENU_ عنصر",
                "zeroRecords": "لا توجد بيانات مطابقة",
                "info": "عرض _START_ إلى _END_ من أصل _TOTAL_ عنصر",
                "infoEmpty": "لا توجد بيانات",
                "infoFiltered": "(منتقاة من أصل _MAX_ عنصر)",
                "paginate": {"first": "الأول", "previous": "السابق", "next": "التالي", "last": "الأخير"}
            },
            "pageLength": 25,
            "ajax": {
                url: "{{ route('course_candidates.index_list') }}",
                data: function (d) {
                    d.course_id = $('#course_filter').val();
                    $('.filter-field').each(function () {
                        d[$(this).attr('name')] = $(this).val();
                    });
                }
            },
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "applicant", "title": "المتقدم", "orderable": false, "searchable": false},
                {"data": "course_name", "title": "الدورة", "orderable": false, "searchable": false},
                {"data": "university", "title": "الجامعة", "orderable": false, "searchable": false},
                {"data": "gpa", "title": "العلامة", "orderable": false, "searchable": false},
                {"data": "actions", "title": "إجراءات", "orderable": false, "searchable": false}
            ],
            "fnDrawCallback": function (oSettings) {
                oTable.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                });
            }
        });

        $('#course_filter').on('change', function () {
            oTable.draw();
        });

        var searchTimer;
        $('.filter-field').on('input change', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                oTable.draw();
            }, 400);
        });

        $('#reset_filters').on('click', function () {
            $('.filter-field').val('');
            oTable.draw();
        });

        function collectFilters() {
            var filters = {};
            filters.course_id = $('#course_filter').val();
            $('.filter-field').each(function () {
                filters[$(this).attr('name')] = $(this).val();
            });
            return filters;
        }

        // ترتيب أعمدة التصدير بالسحب والافلات (HTML5 drag & drop، بدون مكتبة خارجية)
        var exportList = document.getElementById('export_columns_list');
        var dragEl = null;
        if (exportList) {
            exportList.querySelectorAll('li').forEach(function (li) {
                li.addEventListener('dragstart', function () {
                    dragEl = li;
                    li.classList.add('opacity-50');
                });
                li.addEventListener('dragend', function () {
                    li.classList.remove('opacity-50');
                });
                li.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    if (!dragEl || dragEl === li) {
                        return;
                    }
                    var rect = li.getBoundingClientRect();
                    var after = (e.clientY - rect.top) > (rect.height / 2);
                    exportList.insertBefore(dragEl, after ? li.nextSibling : li);
                });
            });
        }

        $('#export_select_all_btn').on('click', function () {
            var allChecked = $('.export-col-checkbox').length === $('.export-col-checkbox:checked').length;
            $('.export-col-checkbox').prop('checked', !allChecked);
        });

        $('#export_confirm_btn').on('click', function () {
            var columns = [];
            $('#export_columns_list li').each(function () {
                var $cb = $(this).find('.export-col-checkbox');
                if ($cb.prop('checked')) {
                    columns.push($cb.val());
                }
            });
            if (!columns.length) {
                toastr.error('يرجى تحديد عمود واحد على الأقل');
                return;
            }
            var params = $.param(collectFilters());
            columns.forEach(function (col) {
                params += '&columns[]=' + encodeURIComponent(col);
            });
            window.location.href = "{{ route('course_candidates.export') }}?" + params;
            $('#export_columns_modal').modal('hide');
        });

        $('#sms_bulk_btn').on('click', function (e) {
            e.preventDefault();
            SmsCompose.open({
                title: 'إرسال SMS لكل المرشحين المطابقين للفلاتر',
                recipientInfo: 'سيتم الإرسال لكل مرشح يطابق فلاتر البحث الحالية (عدد الصفوف الظاهرة بالجدول حالياً: ' + oTable.page.info().recordsDisplay + ')',
                sendUrl: "{{ route('sms.send') }}",
                payload: function () {
                    var data = collectFilters();
                    data.target = 'candidates';
                    return data;
                }
            });
        });

        $(document).on('click', '.remove-candidate-btn', function () {
            var candidateId = $(this).data('candidate-id');
            $.ajax({
                type: 'POST',
                url: "{{ route('course_candidates.remove_global') }}",
                data: {candidate_id: candidateId}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw(false);
            });
        });

        // ================== استيراد ومزامنة من Excel ==================
        function escapeHtml(str) {
            return $('<div>').text(str || '').html();
        }

        function renderPersonList($container, items) {
            $container.empty();
            if (!items.length) {
                $container.append('<div class="text-muted fs-7">لا يوجد</div>');
                return;
            }
            items.forEach(function (item) {
                $container.append(
                    '<div class="d-flex justify-content-between border-bottom py-1">' +
                        '<span>' + escapeHtml(item.name || '(بدون اسم)') + '</span>' +
                        '<span class="text-muted fs-8" dir="ltr">' + escapeHtml(item.mobile || item.email || '') + '</span>' +
                    '</div>'
                );
            });
        }

        function resetImportModal() {
            $('#import_step_1').removeClass('d-none');
            $('#import_step_2').addClass('d-none');
            $('#import_preview_btn').removeClass('d-none');
            $('#import_confirm_btn').addClass('d-none');
            $('#import_course_id').val('');
            $('#import_file').val('');
        }

        $('#import_modal').on('hidden.bs.modal', resetImportModal);

        $('#import_preview_btn').on('click', function () {
            var courseId = $('#import_course_id').val();
            var fileInput = document.getElementById('import_file');
            if (!courseId) {
                toastr.error('يرجى اختيار الدورة');
                return;
            }
            if (!fileInput.files.length) {
                toastr.error('يرجى اختيار ملف Excel');
                return;
            }

            var formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('file', fileInput.files[0]);

            var $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: "{{ route('course_candidates.import') }}",
                data: formData,
                processData: false,
                contentType: false
            }).done(function (data) {
                if (data.status !== 'success') {
                    toastr.error(data.message);
                    return;
                }

                $('#import_summary').text(
                    'بالملف ' + (data.to_add.length + data.to_remove.length + data.unchanged_count) + ' مطابقة، ' +
                    data.unchanged_count + ' منهم مرشحون أصلاً بدون تغيير.'
                );
                $('#import_add_count').text(data.to_add.length);
                $('#import_remove_count').text(data.to_remove.length);
                $('#import_not_found_count').text(data.not_found.length);
                renderPersonList($('#import_add_list'), data.to_add);
                renderPersonList($('#import_remove_list'), data.to_remove);
                renderPersonList($('#import_not_found_list'), data.not_found);

                $('#import_step_1').addClass('d-none');
                $('#import_step_2').removeClass('d-none');

                if (data.to_add.length === 0 && data.to_remove.length === 0) {
                    toastr.info('لا يوجد أي فرق - قائمة المرشحين مطابقة للملف أصلاً');
                    $('#import_confirm_btn').addClass('d-none');
                    $('#import_preview_btn').addClass('d-none');
                } else {
                    $('#import_preview_btn').addClass('d-none');
                    $('#import_confirm_btn').removeClass('d-none');
                }
            }).fail(function () {
                toastr.error('تعذر الاتصال بالسيرفر');
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });

        $('#import_confirm_btn').on('click', function () {
            var courseId = $('#import_course_id').val();
            var fileInput = document.getElementById('import_file');
            if (!fileInput.files.length) {
                toastr.error('يرجى إعادة اختيار الملف');
                return;
            }

            var formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('file', fileInput.files[0]);
            formData.append('confirm', '1');

            var $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: "{{ route('course_candidates.import') }}",
                data: formData,
                processData: false,
                contentType: false
            }).done(function (data) {
                toastr[data.status](data.message);
                if (data.status === 'success') {
                    $('#import_modal').modal('hide');
                    oTable.draw(false);
                }
            }).fail(function () {
                toastr.error('تعذر الاتصال بالسيرفر');
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });
    });
</script>
@endpush
