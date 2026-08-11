@extends('layouts.admin')

@section('title', 'ترشيح المتقدمين')

@section('page-title')
الدورات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('courses.view') }}" class="text-muted text-hover-primary">إدارة الدورات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">ترشيح المتقدمين - {{ $course->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold">فلاتر الترشيح</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control form-control-solid filter-field" placeholder="بحث بالاسم">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الجنس</label>
                        <select name="gender" class="form-select form-select-solid filter-field">
                            <option value="">الكل</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الجامعة</label>
                        <input type="text" name="university" class="form-control form-control-solid filter-field" placeholder="الجامعة">
                    </div>
                    <div class="col-md-4">
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
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold">نتائج البحث</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" id="add_all_matching_btn" class="btn btn-light-primary me-3">
                        <i class="ki-duotone ki-people fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> ترشيح كل النتائج المطابقة للفلاتر
                    </button>
                    <button type="button" id="add_selected_btn" class="btn btn-primary" disabled>
                        <i class="ki-duotone ki-plus fs-2"></i> إضافة المحدد للمرشحين
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="text-muted fs-7 mb-3">
                    التحديد يشتغل على المرشحين المضافين سابقاً لهذه الدورة بشكل تراكمي — تقدر ترشّح لنفس الدورة أكثر من مرة بفلاتر مختلفة كل مرة بدون ما تلغي المرشحين السابقين.
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed table-hover fs-6 gy-5 w-100" id="applicants_table" style="width:100%">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" id="select_all_checkbox">
                                    </div>
                                </th>
                                <th>المتقدم</th>
                                <th>الجامعة</th>
                                <th>العلامة</th>
                                <th>الحالة الاجتماعية</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold">المرشحون لدورة: {{ $course->name }}</h3>
                </div>
            </div>
            <div class="card-body">
                @can('admin.sms.send')
                <button type="button" id="sms_course_btn" class="btn btn-info w-100 mb-4">
                    <i class="ki-duotone ki-message-text-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> إرسال SMS لكل المرشحين
                </button>
                @endcan
                <button type="button" id="export_btn" class="btn btn-light-success w-100 mb-4" data-bs-toggle="modal" data-bs-target="#export_columns_modal">
                    <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> تصدير مرشحي الدورة Excel
                </button>
                <div id="candidates_list">
                    <div class="text-muted text-center py-5" id="candidates_empty">لا يوجد مرشحون بعد</div>
                </div>
            </div>
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
                <div class="text-muted fs-7 mb-4">فعّل الأعمدة المطلوبة، واسحب <i class="ki-duotone ki-arrow-mix fs-6"></i> لإعادة ترتيبها كما تريدها بالملف. سيتم تصدير مرشحي هذه الدورة فقط.</div>
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

        var encryptedId = @json($encrypted_id);
        var listUrl = @json(route('course_candidates.list', ['id' => $encrypted_id]));
        var currentUrl = @json(route('course_candidates.current', ['id' => $encrypted_id]));
        var addUrl = @json(route('course_candidates.add', ['id' => $encrypted_id]));
        var addAllUrl = @json(route('course_candidates.add_all', ['id' => $encrypted_id]));
        var removeUrlBase = @json(route('course_candidates.remove', ['id' => $encrypted_id]));

        function collectFilters() {
            var filters = {};
            $('.filter-field').each(function () {
                filters[$(this).attr('name')] = $(this).val();
            });
            return filters;
        }

        // فلاتر قادمة من زر "ترشيح" بشاشة تسجيلات الدورة (عبر رابط ?name=...&gender=...)
        var initialFilters = @json($initial_filters ?? []);
        $.each(initialFilters, function (name, value) {
            if (value !== null && value !== '') {
                $('.filter-field[name="' + name + '"]').val(value);
            }
        });

        var oTable = $('#applicants_table').DataTable({
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
            "pageLength": 10,
            "ajax": {
                url: listUrl,
                data: function (d) {
                    $.extend(d, collectFilters());
                }
            },
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "select", "orderable": false, "searchable": false},
                {"data": "applicant", "title": "المتقدم", "orderable": false, "searchable": false},
                {"data": "university", "title": "الجامعة", "orderable": false, "searchable": false},
                {"data": "gpa", "title": "العلامة", "orderable": false, "searchable": false},
                {"data": "marital_status", "title": "الحالة الاجتماعية", "orderable": false, "searchable": false}
            ],
            "drawCallback": function () {
                // كل رسم جديد للجدول (فلترة/صفحة جديدة) يفرغ حالة "تحديد الكل"
                $('#select_all_checkbox').prop('checked', false);
                toggleAddButton();
            }
        });

        $('#select_all_checkbox').on('change', function () {
            var checked = $(this).prop('checked');
            $('.candidate-checkbox:not(:disabled)').prop('checked', checked);
            toggleAddButton();
        });

        $('#sms_course_btn').on('click', function () {
            SmsCompose.open({
                title: 'إرسال SMS لكل مرشحي: {{ $course->name }}',
                recipientInfo: 'سيتم الإرسال لكل المرشحين المضافين حالياً لهذه الدورة',
                sendUrl: "{{ route('sms.send') }}",
                payload: function () {
                    return {target: 'course', course_id: encryptedId};
                }
            });
        });

        $('#add_all_matching_btn').on('click', function () {
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: addAllUrl,
                data: collectFilters()
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw(false);
                loadCandidates();
            }).always(function () {
                $btn.prop('disabled', false);
            });
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

        $(document).on('change', '.candidate-checkbox', function () {
            toggleAddButton();
        });

        function toggleAddButton() {
            var count = $('.candidate-checkbox:checked:not(:disabled)').length;
            $('#add_selected_btn').prop('disabled', count === 0);
        }

        $('#add_selected_btn').on('click', function () {
            var ids = $('.candidate-checkbox:checked:not(:disabled)').map(function () {
                return $(this).val();
            }).get();
            if (!ids.length) {
                return;
            }
            $.ajax({
                type: 'POST',
                url: addUrl,
                data: {ids: ids}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw(false);
                loadCandidates();
                $('#add_selected_btn').prop('disabled', true);
            });
        });

        function loadCandidates() {
            $.ajax({
                type: 'GET',
                url: currentUrl
            }).done(function (data) {
                renderCandidates(data.data);
            });
        }

        function renderCandidates(candidates) {
            var $list = $('#candidates_list');
            $list.empty();
            if (!candidates.length) {
                $list.append('<div class="text-muted text-center py-5" id="candidates_empty">لا يوجد مرشحون بعد</div>');
                return;
            }
            candidates.forEach(function (c) {
                var $item = $(
                    '<div class="d-flex align-items-center justify-content-between border-bottom py-3">' +
                        '<div class="d-flex flex-column">' +
                            '<span class="fw-bold">' + $('<div>').text(c.full_name).html() + '</span>' +
                            '<span class="text-muted fs-8">' + (c.age !== null ? c.age + ' سنة · ' : '') + $('<div>').text(c.gender).html() + ' · ' + $('<div>').text(c.university || '').html() + '</span>' +
                        '</div>' +
                        '<button type="button" class="btn btn-icon btn-sm btn-light-danger remove-candidate-btn" data-candidate-id="' + c.candidate_id + '">' +
                            '<i class="ki-duotone ki-cross fs-4"></i>' +
                        '</button>' +
                    '</div>'
                );
                $list.append($item);
            });
        }

        $(document).on('click', '.remove-candidate-btn', function () {
            var candidateId = $(this).data('candidate-id');
            $.ajax({
                type: 'POST',
                url: removeUrlBase,
                data: {candidate_id: candidateId}
            }).done(function (data) {
                toastr[data.status](data.message);
                loadCandidates();
                oTable.draw(false);
            });
        });

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
            var params = 'course_id={{ $course->id }}';
            columns.forEach(function (col) {
                params += '&columns[]=' + encodeURIComponent(col);
            });
            window.location.href = "{{ route('course_candidates.export') }}?" + params;
            $('#export_columns_modal').modal('hide');
        });

        loadCandidates();
    });
</script>
@endpush
