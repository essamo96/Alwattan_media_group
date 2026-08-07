@extends('layouts.admin')

@section('title', 'إدارة العقارات')

@section('page-title')
العقارات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إدارة العقارات</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title d-flex align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="title" id="title" class="form-control form-control-solid w-250px ps-12 searchable" placeholder="بحث بالعنوان">
            </div>
            <select id="series_id" name="series_id" class="form-select form-select-solid w-200px">
                <option value="-1">القسم: عرض الكل</option>
                @foreach($series as $sr)
                <option value="{{ $sr->id }}" {{ old('series_id') == $sr->id ? 'selected' : '' }}>{{ $sr->title }}</option>
                @endforeach
            </select>
        </div>
        @can('admin.properties.add')
        <div class="card-toolbar">
            <a href="{{ route('properties.add') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> إضافة
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="properties_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>العنوان</th>
                    <th>القسم</th>
                    <th>الالبوم</th>
                    <th>الحالة</th>
                    <th class="text-end">تعديل</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
@include('layouts.partials.confirm-modal')
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

        $('#confirm').on('show.bs.modal', function (e) {
            $("#delete_id").val($(e.relatedTarget).data('href'));
        });

        var oTable = $('#properties_table').DataTable({
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
                url: "{{ route('properties.list') }}",
                data: function (d) {
                    d.title = $('input[name="title"]').val();
                    d.series_id = $("#series_id").val();
                }
            },
            "order": [[1, 'asc']],
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "title", "title": "العنوان", "orderable": true, "searchable": false},
                {"data": "series_id", "title": "القسم", "orderable": true, "searchable": false},
                {"data": "gallery", "title": "الالبوم", "orderable": false, "searchable": false},
                {"data": "status", "title": "الحالة", "orderable": true, "searchable": false},
                {"data": "actions", "title": "تعديل", "orderable": false, "searchable": false}
            ],
            "fnDrawCallback": function (oSettings) {
                oTable.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                });
            }
        });

        $('.searchable').on('input', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $('#series_id').on('click', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $(document).on('click', ".status", function () {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('properties.status') }}",
                data: {'id': id}
            }).done(function (data) {
                if (data.type == 'yes') {
                    item.removeClass("badge-light-danger").addClass("badge-light-success");
                    item.html('<i class="ki-duotone ki-check fs-6 me-1"></i> منشور');
                } else if (data.type == 'no') {
                    item.removeClass("badge-light-success").addClass("badge-light-danger");
                    item.html('<i class="ki-duotone ki-cross fs-6 me-1"></i> مخفي');
                }
                toastr[data.status](data.message);
            });
        });

        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('properties.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
