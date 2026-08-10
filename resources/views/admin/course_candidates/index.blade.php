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
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <select name="course_id" id="course_filter" class="form-select form-select-solid w-250px">
                    <option value="">كل الدورات</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
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
