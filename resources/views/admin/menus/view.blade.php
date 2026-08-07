@extends('layouts.admin')

@section('title', 'ادارة قوائم الموقع')

@section('page-title')
قوائم الموقع
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">قوائم الموقع</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="name" id="name" class="form-control form-control-solid w-250px ps-12 searchable" placeholder="بحث بالاسم">
            </div>
        </div>
        @can('admin.menus.add')
        <div class="card-toolbar">
            <a href="{{ route('menus.add') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> اضافة قائمة
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="menus_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>الاسم بالعربي</th>
                    <th>الاسم بالانجليزي</th>
                    <th>القائمة الاب</th>
                    <th>المسار</th>
                    <th>الايقونة</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th class="text-end">الاوامر</th>
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

        var oTable = $('#menus_table').DataTable({
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
                url: "{{ route('menus.list') }}",
                data: function (d) {
                    d.name = $('input[name="name"]').val();
                }
            },
            "order": [[6, 'asc']],
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "name_ar", "title": "الاسم بالعربي", "orderable": true, "searchable": false},
                {"data": "name_en", "title": "الاسم بالانجليزي", "orderable": true, "searchable": false},
                {"data": "parent", "title": "القائمة الاب", "orderable": false, "searchable": false},
                {"data": "url", "title": "المسار", "orderable": false, "searchable": false},
                {"data": "icon", "title": "الايقونة", "orderable": false, "searchable": false},
                {"data": "sort", "title": "الترتيب", "orderable": true, "searchable": false},
                {"data": "status", "title": "الحالة", "orderable": true, "searchable": false},
                {"data": "actions", "title": "الاوامر", "orderable": false, "searchable": false}
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

        $(document).on('click', ".status", function () {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('menus.status') }}",
                data: {'id': id}
            }).done(function (data) {
                if (data.type == 'yes') {
                    item.removeClass("badge-light-danger").addClass("badge-light-success");
                    item.html('<i class="ki-duotone ki-check fs-6 me-1"></i> فعال');
                } else if (data.type == 'no') {
                    item.removeClass("badge-light-success").addClass("badge-light-danger");
                    item.html('<i class="ki-duotone ki-cross fs-6 me-1"></i> غير فعال');
                }
                toastr[data.status](data.message);
            });
        });
        ///////////////////////////////////////////////////
        $(document).on('change', ".menu-sort", function () {
            var id = $(this).data('href');
            var sort = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ route('menus.sort') }}",
                data: {'id': id, 'sort': sort}
            }).done(function (data) {
                toastr[data.status](data.message);
            });
        });
        ///////////////////////////////////////////////////
        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('menus.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
