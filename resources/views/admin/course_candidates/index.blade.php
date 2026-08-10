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
    });
</script>
@endpush
