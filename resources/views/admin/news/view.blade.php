@extends('layouts.admin')

@section('title', 'إدارة الأخبار')

@section('page-title')
الأخبار
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">الأخبار</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title d-flex align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="news" id="news" class="form-control form-control-solid w-200px ps-12 searchable" placeholder="بحث بالخبر">
            </div>
            <select id="publish" name="publish" class="form-select form-select-solid w-150px">
                <option value="-1">الحالة: الكل</option>
                <option value="0">غير منشور</option>
                <option value="1">منشور</option>
            </select>
            <select name="category_id" class="form-select form-select-solid w-175px" id="category_id">
                <option value="-1">كل الاقسام</option>
                @foreach($categories as $item)
                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                @endforeach
            </select>
        </div>
        @can('admin.news.add')
        <div class="card-toolbar">
            <a href="{{ route('news.add') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> إضافة
            </a>
            <a href="{{ route('news.cleaAllCache') }}" class="btn btn-light">
                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> حذف الكاش
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="news_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th> # </th>
                    <th> الخبر </th>
                    <th> القسم </th>
                    <th> اللغة </th>
                    @can('admin.news.publish')
                    <th> النشر </th>
                    @endcan
                    <th class="text-end"> ادوات </th>
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

        var oTable = $('#news_table').DataTable({
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
                url: "{{ route('news.list') }}",
                data: function (d) {
                    d.title = $('input[name="news"]').val();
                    d.publish = $("#publish").val();
                    d.category = $("#category_id").val();
                }
            },
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "title", "title": "الخبر", "orderable": true, "searchable": false},
                {"data": "category_name", "title": "القسم", "orderable": true, "searchable": false},
                {"data": "language", "title": "اللغة", "orderable": true, "searchable": false},
                @can('admin.news.publish')
                {"data": "publish", "title": "النشر", "orderable": true, "searchable": false},
                @endcan
                {"data": "actions", "title": "أدوات", "orderable": false, "searchable": false}
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

        $('#publish').on('change', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $('#category_id').on('change', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $(document).on('click', ".publish", function () {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('news.publish') }}",
                data: {'id': id}
            }).done(function (data) {
                if (data.type == 'yes') {
                    item.removeClass("badge-light-danger").addClass("badge-light-success");
                    item.html('<i class="ki-duotone ki-check fs-6 me-1"></i> منشور');
                } else if (data.type == 'no') {
                    item.removeClass("badge-light-success").addClass("badge-light-danger");
                    item.html('<i class="ki-duotone ki-cross fs-6 me-1"></i> غير منشور');
                }
                toastr[data.status](data.message);
            });
        });

        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('news.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });

        $(document).on('click', ".watermark-toggle", function (e) {
            e.preventDefault();
            var id = $(this).data('href');
            $.ajax({
                type: "POST",
                url: "{{ route('news.watermark') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                if (data.status == 'success') {
                    oTable.draw(false);
                }
            });
        });
    });
</script>
@endpush
