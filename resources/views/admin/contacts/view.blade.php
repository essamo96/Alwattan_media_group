@extends('layouts.admin')

@section('title', 'إدارة إتصل بنا')

@section('page-title')
إتصل بنا
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('contacts.view') }}" class="text-muted text-hover-primary">اتصل بنا</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إدارة إتصل بنا</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title d-flex align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" value="{{ old('name') }}" class="form-control form-control-solid w-200px ps-12 searchable" name="name" id="name" placeholder="بحث بالإسم">
            </div>
            <input type="text" value="{{ old('email') }}" class="form-control form-control-solid w-200px searchable" name="email" id="email" placeholder="البريد الإلكتروني">
            <input type="text" value="{{ old('mobile') }}" class="form-control form-control-solid w-175px searchable" name="mobile" id="mobile" placeholder="الجوال">
        </div>
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="contacts_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>الإسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجوال</th>
                    <th>الموضوع</th>
                    <th>الحالة</th>
                    <th class="text-end">أدوات</th>
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
        ////////////////////////////////////////////////////
        $('#confirm').on('show.bs.modal', function (e) {
            $("#delete_id").val($(e.relatedTarget).data('href'));
        });
        ////////////////////////////////////////////////////
        var oTable = $('#contacts_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "language": {
                "sProcessing": "Processing...",
                "sLengthMenu": "Show _MENU_ entries",
                "sZeroRecords": "No matching records found",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
                "sInfoEmpty": "Showing 0 to 0 of 0 entries",
                "sInfoFiltered": "(filtered from _MAX_ total entries)",
                "sInfoPostFix": "",
                "sSearch": "Search=>:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "First",
                    "sPrevious": "Previous",
                    "sNext": "Next",
                    "sLast": "Last"
                }
            },
            "pageLength": 25,
            "ajax": {
                url: "{{ route('contacts.list') }}",
                data: function (d) {
                    d.name = $('input[name="name"]').val();
                    d.email = $('input[name="email"]').val();
                    d.mobile = $('input[name="mobile"]').val();
                }
            },
            "order": [[1, 'asc']],
            "columnDefs": [{
                "targets": "_all",
                "defaultContent": ""
            }],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {
                    "data": "name",
                    "title": "الإسم",
                    "orderable": true,
                    "searchable": false
                },
                {
                    "data": "email",
                    "title": "البريد الإلكتروني",
                    "orderable": true,
                    "searchable": false
                },
                {
                    "data": "mobile",
                    "title": "الجوال",
                    "orderable": true,
                    "searchable": false
                },
                {
                    "data": "subject",
                    "title": "الموضوع",
                    "orderable": true,
                    "searchable": false
                },
                {
                    "data": "status",
                    "title": "الحالة",
                    "orderable": true,
                    "searchable": false
                },
                {
                    "data": "actions",
                    "title": "أدوات",
                    "orderable": false,
                    "searchable": false
                }
            ],
            "fnDrawCallback": function (oSettings) {
                $('.tooltips').tooltip();

                oTable.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                });
            }
        });

        $('.searchable').on('input',function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $('button[type="reset"]').on('click', function (e) {
            e.preventDefault();
            $(this).closest('form').get(0).reset();
            oTable.draw();
        });
        ////////////////////////////////////////////////////
        $(document).on('click', ".status", function() {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('contacts.status') }}",
                data: {'id' : id}
            }).done(function (data) {
                if(data.type == 'yes')
                {
                    item.removeClass("badge-light-danger");
                    item.addClass("badge-light-success");
                    item.html('<i class="ki-duotone ki-check fs-6 me-1"></i> تم التواصل');
                }
                else if(data.type == 'no')
                {
                    item.removeClass("badge-light-success");
                    item.addClass("badge-light-danger");
                    item.html('<i class="ki-duotone ki-cross fs-6 me-1"></i> قيد الإنتظار');
                }
                toastr[data.status](data.message);
            });
        });
        ///////////////////////////////////////////////////
        $(document).on('click', ".delete", function() {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('contacts.delete') }}",
                data: {'id' : id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
