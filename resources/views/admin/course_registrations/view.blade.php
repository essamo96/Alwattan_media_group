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
        @can('admin.registrations.export')
        <div class="card-toolbar">
            <a href="#" id="export_btn" class="btn btn-light-success">
                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> تصدير Excel
            </a>
        </div>
        @endcan
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
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>الجنس</th>
                        <th>الجوال</th>
                        <th>البريد الإلكتروني</th>
                        <th>الجامعة</th>
                        <th>سنة التخرج</th>
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
@endsection

@push('scripts')
<link href="{{ asset('assets/metronic/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('assets/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
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
                {"data": "full_name", "title": "الاسم", "orderable": false, "searchable": false},
                {"data": "national_id", "title": "رقم الهوية", "orderable": false, "searchable": false},
                {"data": "gender", "title": "الجنس", "orderable": false, "searchable": false},
                {"data": "mobile", "title": "الجوال", "orderable": false, "searchable": false},
                {"data": "email", "title": "البريد الإلكتروني", "orderable": false, "searchable": false},
                {"data": "university", "title": "الجامعة", "orderable": false, "searchable": false},
                {"data": "graduation_year", "title": "سنة التخرج", "orderable": false, "searchable": false},
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

        $('#export_btn').on('click', function (e) {
            e.preventDefault();
            var params = $.param(collectFilters());
            window.location.href = "{{ route('course_registrations.export') }}?" + params;
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
