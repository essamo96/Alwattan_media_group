@extends('layouts.admin')

@section('title', 'ادارة التنبيهات')

@section('page-title')
جهات الاتصال
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('contact.view') }}" class="text-muted text-hover-primary">ادارة جهات الاتصال</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">التنبيهات</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title d-flex align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="name" id="name" class="form-control form-control-solid w-200px ps-12 searchable" placeholder="بحث بالإسم">
            </div>
            <select class="form-select form-select-solid w-175px typesC" name="typesC">
                <option value="" selected>جهة الاتصال...</option>
                <option value="الجهات الحكومية"> الجهات الحكومية</option>
                <option value="المؤسسات والشركات"> المؤسسات والشركات</option>
                <option value="القطاع غير الربحي"> القطاع غير الربحي</option>
            </select>
            @can('admin.contact.viewAll')
            <select class="form-select form-select-solid w-175px byUser" name="byUser" id="byUser" aria-label="Default select example">
                <option value="" selected>المدخل...</option>
                @foreach ($users as $item)
                <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @endcan
        </div>
        @can('admin.contact.add')
        <div class="card-toolbar">
            <a href="{{ route('contact.add') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> إضافة
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="Contact_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>اسم الجهة</th>
                    <th>النوع</th>
                    <th>المسؤول</th>
                    <th>الهاتف</th>
                    <th>انشئ بواسطة</th>
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

        var oTable = $('#Contact_table').DataTable({
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
                url: "{{ route('contact.remember.list') }}",
                data: function (d) {
                    d.name = $('input[name="name"]').val();
                    d.byUser = $('select[name="byUser"]').val();
                    d.typesC = $('select[name="typesC"]').val();
                }
            },
            "order": [[1, 'asc']],
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "name", "title": "الإسم", "orderable": true, "searchable": false},
                {"data": "contact_type", "title": "النوع", "orderable": true, "searchable": false},
                {"data": "master", "title": "المسؤول", "orderable": true, "searchable": false},
                {"data": "mobile", "title": "الهاتف", "orderable": true, "searchable": false},
                {"data": "created_by", "title": "انشئ بواسطة", "orderable": true, "searchable": false},
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

        $('.typesC').on('change', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $('.byUser').on('change', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $(document).on('click', ".status", function () {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('contact.status') }}",
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

        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('contact.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
