@extends('layouts.admin')

@section('title', 'تسجيلات الدورة')

@section('page-title')
تسجيلات الدورة
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">تسجيلات الدورة</li>
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
            @can('admin.course_candidates.manage')
            <a href="#" id="shortlist_btn" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#shortlist_modal">
                <i class="ki-duotone ki-people fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> ترشيح
            </a>
            @endcan
            @can('admin.registrations.export')
            <a href="#" id="export_btn" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#export_columns_modal">
                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> تصدير Excel
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control form-control-solid filter-field" placeholder="بحث بالاسم">
            </div>
            <div class="col-md-3">
                <label class="form-label">رقم الهوية</label>
                <input type="text" name="national_id" class="form-control form-control-solid filter-field" placeholder="رقم الهوية">
            </div>
            <div class="col-md-2">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-select form-select-solid filter-field">
                    <option value="">الكل</option>
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الحالة الاجتماعية</label>
                <select name="marital_status" class="form-select form-select-solid filter-field">
                    <option value="">الكل</option>
                    <option value="single">أعزب</option>
                    <option value="married">متزوج</option>
                    <option value="divorced">مطلق</option>
                    <option value="widowed">أرمل</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الجنسية</label>
                <input type="text" name="nationality" class="form-control form-control-solid filter-field" placeholder="الجنسية">
            </div>
            <div class="col-md-3">
                <label class="form-label">التخصص العام</label>
                <input type="text" name="general_specialization" class="form-control form-control-solid filter-field" placeholder="التخصص العام">
            </div>
            <div class="col-md-3">
                <label class="form-label">التخصص الدقيق</label>
                <input type="text" name="specific_specialization" class="form-control form-control-solid filter-field" placeholder="التخصص الدقيق">
            </div>
            <div class="col-md-3">
                <label class="form-label">الجامعة</label>
                <input type="text" name="university" class="form-control form-control-solid filter-field" placeholder="الجامعة">
            </div>
            <div class="col-md-3">
                <label class="form-label">جهة العمل</label>
                <input type="text" name="employer" class="form-control form-control-solid filter-field" placeholder="جهة العمل">
            </div>
            <div class="col-md-2">
                <label class="form-label">سنة التخرج من</label>
                <input type="number" name="graduation_year_from" class="form-control form-control-solid filter-field" placeholder="من">
            </div>
            <div class="col-md-2">
                <label class="form-label">سنة التخرج إلى</label>
                <input type="number" name="graduation_year_to" class="form-control form-control-solid filter-field" placeholder="إلى">
            </div>
            <div class="col-md-2">
                <label class="form-label">المعدل من</label>
                <input type="number" step="0.01" name="gpa_from" class="form-control form-control-solid filter-field" placeholder="من">
            </div>
            <div class="col-md-2">
                <label class="form-label">المعدل إلى</label>
                <input type="number" step="0.01" name="gpa_to" class="form-control form-control-solid filter-field" placeholder="إلى">
            </div>
            <div class="col-md-2">
                <label class="form-label">العمر من</label>
                <input type="number" min="0" name="age_from" class="form-control form-control-solid filter-field" placeholder="من">
            </div>
            <div class="col-md-2">
                <label class="form-label">العمر إلى</label>
                <input type="number" min="0" name="age_to" class="form-control form-control-solid filter-field" placeholder="إلى">
            </div>
            <div class="col-md-2">
                <label class="form-label">رقم الجوال</label>
                <input type="text" name="mobile" class="form-control form-control-solid filter-field" placeholder="رقم الجوال">
            </div>
            <div class="col-md-2">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="text" name="email" class="form-control form-control-solid filter-field" placeholder="البريد الإلكتروني">
            </div>
            <div class="col-md-2">
                <label class="form-label">تاريخ التسجيل من</label>
                <input type="date" name="date_from" class="form-control form-control-solid filter-field">
            </div>
            <div class="col-md-2">
                <label class="form-label">تاريخ التسجيل إلى</label>
                <input type="date" name="date_to" class="form-control form-control-solid filter-field">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" id="reset_filters" class="btn btn-light w-100">تصفير الفلاتر</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body pt-6">
        @include('admin.layout.error')
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed table-hover fs-6 gy-5 w-100" id="registrations_table" style="width:100%">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>المتقدم</th>
                        <th>رقم الهوية</th>
                        <th>الجوال</th>
                        <th>البريد الإلكتروني</th>
                        <th>الجامعة</th>
                        <th>سنة التخرج</th>
                        <th>العلامة</th>
                        <th>الحالة الاجتماعية</th>
                        <th>تاريخ التسجيل</th>
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
@include('layouts.partials.confirm-modal')
@include('admin.partials.sms-compose-modal')

<div class="modal fade" id="shortlist_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ترشيح المتقدمين المطابقين للفلاتر الحالية</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">اختر الدورة</label>
                <select id="shortlist_course_id" class="form-select">
                    <option value="">-- اختر --</option>
                    @foreach($courses as $course)
                    <option value="{{ rawurlencode(Crypt::encrypt($course->id)) }}">{{ $course->name }}</option>
                    @endforeach
                </select>
                <div class="text-muted fs-7 mt-3">سيتم نقل فلاتر البحث الحالية (الاسم، الجنس، العمر، العلامة...) تلقائياً إلى شاشة ترشيح هذه الدورة.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="shortlist_continue_btn" class="btn btn-primary" disabled>متابعة</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="export_columns_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">اختيار وترتيب أعمدة التصدير</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-muted fs-7 mb-4">فعّل الأعمدة المطلوبة، واسحب <i class="ki-duotone ki-arrow-mix fs-6"></i> لإعادة ترتيبها كما تريدها بالملف.</div>
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
<link href="{{ asset('assets/metronic/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('assets/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset_v('assets/admin/global/scripts/sms-compose.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#confirm').on('show.bs.modal', function (e) {
            $("#delete_id").val($(e.relatedTarget).data('href'));
        });

        function collectFilters() {
            var filters = {};
            $('.filter-field').each(function () {
                filters[$(this).attr('name')] = $(this).val();
            });
            return filters;
        }

        var oTable = $('#registrations_table').DataTable({
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
                "search": "بحث:",
                "paginate": {
                    "first": "الأول",
                    "previous": "السابق",
                    "next": "التالي",
                    "last": "الأخير"
                }
            },
            "pageLength": 25,
            "ajax": {
                url: "{{ route('course_registrations.list') }}",
                data: function (d) {
                    $.extend(d, collectFilters());
                }
            },
            "order": [[0, 'desc']],
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "applicant", "title": "المتقدم", "orderable": false, "searchable": false},
                {"data": "national_id", "title": "رقم الهوية", "orderable": false, "searchable": false},
                {"data": "mobile", "title": "الجوال", "orderable": false, "searchable": false},
                {"data": "email", "title": "البريد الإلكتروني", "orderable": false, "searchable": false},
                {"data": "university", "title": "الجامعة", "orderable": false, "searchable": false},
                {"data": "graduation_year", "title": "سنة التخرج", "orderable": false, "searchable": false},
                {"data": "gpa", "title": "العلامة", "orderable": false, "searchable": false},
                {"data": "marital_status", "title": "الحالة الاجتماعية", "orderable": false, "searchable": false},
                {"data": "created_at", "title": "تاريخ التسجيل", "orderable": false, "searchable": false},
                {"data": "actions", "title": "إجراءات", "orderable": false, "searchable": false}
            ],
            "fnDrawCallback": function (oSettings) {
                oTable.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                });
            }
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

        $(document).on('click', '.sms-single-btn', function (e) {
            e.preventDefault();
            var registrationId = $(this).data('id');
            var name = $(this).data('name');
            SmsCompose.open({
                title: 'إرسال SMS إلى: ' + name,
                recipientInfo: 'سيتم إرسال الرسالة لهذا المتقدم فقط',
                sendUrl: "{{ route('sms.send') }}",
                payload: function () {
                    return {target: 'single', registration_id: registrationId};
                }
            });
        });

        $('#sms_bulk_btn').on('click', function (e) {
            e.preventDefault();
            SmsCompose.open({
                title: 'إرسال SMS لكل النتائج المطابقة للفلاتر',
                recipientInfo: 'سيتم الإرسال لكل متقدم يطابق فلاتر البحث الحالية (عدد الصفوف الظاهرة بالجدول حالياً: ' + oTable.page.info().recordsDisplay + ')',
                sendUrl: "{{ route('sms.send') }}",
                payload: function () {
                    var data = collectFilters();
                    data.target = 'filtered';
                    return data;
                }
            });
        });

        $('#shortlist_course_id').on('change', function () {
            $('#shortlist_continue_btn').prop('disabled', !$(this).val());
        });

        $('#shortlist_continue_btn').on('click', function () {
            var courseId = $('#shortlist_course_id').val();
            if (!courseId) {
                return;
            }
            var params = $.param(collectFilters());
            window.location.href = "{{ url('admin/courses') }}/" + courseId + "/candidates?" + params;
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
            var params = $.param(collectFilters());
            columns.forEach(function (col) {
                params += '&columns[]=' + encodeURIComponent(col);
            });
            window.location.href = "{{ route('course_registrations.export') }}?" + params;
            $('#export_columns_modal').modal('hide');
        });

        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('course_registrations.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
