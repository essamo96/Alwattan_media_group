@extends('layouts.admin')

@section('title', 'الانجازات')

@section('page-title')
الانجازات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">الانجازات</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="title" id="title" class="form-control form-control-solid w-250px ps-12 searchable" placeholder="بحث بالعنوان أو الوسوم">
            </div>
        </div>
        @can('admin.achievements.add')
        <div class="card-toolbar">
            <a href="{{ route('achievements.add') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> إضافة إنجاز
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="achievements_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>الوسوم</th>
                    <th>الترتيب</th>
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

        var oTable = $('#achievements_table').DataTable({
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
                url: "{{ route('achievements.list') }}",
                data: function (d) {
                    d.title = $('input[name="title"]').val();
                }
            },
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "image_ar", "title": "الصورة", "orderable": false, "searchable": false, "render": function (data) {
                    return data ? '<img src="{{ asset("uploads/achievements/") }}/' + data + '" style="max-height:45px;max-width:80px;object-fit:cover;border-radius:4px;">' : '';
                }},
                {"data": "title", "title": "العنوان", "orderable": false, "searchable": false},
                {"data": "tags", "title": "الوسوم", "orderable": false, "searchable": false, "render": function (data) {
                    if (!data) {
                        return '';
                    }
                    return data.split(',').map(function (t) {
                        return '<span class="badge badge-light-primary me-1">' + $('<div>').text(t.trim()).html() + '</span>';
                    }).join('');
                }},
                {"data": "sort_order", "title": "الترتيب", "orderable": false, "searchable": false},
                {"data": "status", "title": "الحالة", "orderable": false, "searchable": false},
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

        $(document).on('click', ".status", function () {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('achievements.status') }}",
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
                url: "{{ route('achievements.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
