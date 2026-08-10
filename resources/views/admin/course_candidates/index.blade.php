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
