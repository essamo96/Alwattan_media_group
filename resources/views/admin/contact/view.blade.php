@extends('layouts.admin')

@section('title', 'ادارة جهات الاتصال')

@section('page-title')
جهات الاتصال
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">ادارة جهات الاتصال</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title d-flex align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="name" id="name" class="form-control form-control-solid w-200px ps-12 searchable" placeholder="بحث باسم الجهة">
            </div>
            <select class="form-select form-select-solid w-175px typesC" name="typesC">
                <option value="" selected>القطاع...</option>
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
        <div class="card-toolbar">
            @can('admin.contact.add')
            <a href="{{ route('contact.add') }}" class="btn btn-primary me-2">
                <i class="ki-duotone ki-plus fs-2"></i> إضافة
            </a>
            @endcan
            @can('admin.contact.viewAll')
            <a href="{{ route('contact.printall') }}" class="btn btn-light-primary btnPrint">
                <i class="ki-duotone ki-printer fs-2"><span class="path1"></span><span class="path2"></span></i> طباعة الكل
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="Contact_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>القطاع</th>
                    <th>اسم الجهة</th>
                    <th>الشخص المسؤول</th>
                    <th>الهاتف</th>
                    <th>الايميل</th>
                    <th>انشئ بواسطة</th>
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
<script src="{{ url('assets/jquery.printPage.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".btnPrint").printPage();

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
                url: "{{ route('contact.list') }}",
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
                {"data": "contact_type", "title": "القطاع", "orderable": true, "searchable": false},
                {"data": "name", "title": "اسم الجهة", "orderable": true, "searchable": false},
                {"data": "master", "title": "الشخص المسؤول", "orderable": true, "searchable": false},
                {"data": "mobile", "title": "الهاتف", "orderable": true, "searchable": false},
                {"data": "email", "title": "الايميل", "orderable": true, "searchable": false},
                {"data": "created_by", "title": "انشئ بواسطة", "orderable": true, "searchable": false},
                {"data": "actions", "title": "تعديل", "orderable": false, "searchable": false}
            ],
            "fnDrawCallback": function (oSettings) {
                oTable.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                });
                $(".btnPrint").printPage();
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

        $(document).on('click', "#contactInfo", function () {
            var date = this.getAttribute('data-date');
            var name = this.getAttribute('data-name');
            Swal.fire({
                title: 'تفاصيل الشخص المضيف',
                html: '<input id="number2" style="border: 1.5px solid #3ebce5; font-size: 17px;" value ="' +
                    name + '" class="swal2-input" readonly>' +
                    '<input id="datetime2" style="border: 1.5px solid #3ebce5; font-size: 17px;" type="datetime-local" value ="' +
                    date + '" class="swal2-input" placeholder="Date and time">',
                text: 'This will generate a random key and date time. Do you want to proceed?',
                icon: 'info',
                showCancelButton: true,
                cancelButtonText: 'اغلاق',
                showConfirmButton: false,
            });
        });

        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('contact.delete') }}",
                data: {'id': id}
            }).success(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
