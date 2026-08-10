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
                <div id="candidates_list">
                    <div class="text-muted text-center py-5" id="candidates_empty">لا يوجد مرشحون بعد</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="{{ asset_v('assets/metronic/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset_v('assets/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
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

        loadCandidates();
    });
</script>
@endpush
