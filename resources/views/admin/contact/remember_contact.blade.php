@extends('admin.layout.master')

@section('title')
  ادارة  التنبيهات
@stop

@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('contact.view') }}">ادارة جهات الاتصال : التنبيهات</a>
            <i class="fa fa-angle-right"></i>
            </i>
        </li>
        <li>
            <span> الرئيسية</span>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title">جهات الاتصال
        <small>إدارة جهات الاتصال : التنبيهات</small>
    </h1>
@stop

@section('page-content')
 <div class="portlet box {{ $form_class }}" id="jj">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-magnifier"></i>البحث
            </div>
        </div>

        <div class="portlet-body">
            <form role="form" class="form-horizontal">
                <div class="form-body">
                    <div class="form-group">
                        <div class="row-12"></div>
                        <label class="col-md-2 control-labelf">الإسم</label>
                        <div class="col-md-2">
                            <input type="text" name="name" id="name" class="form-control searchable"
                                placeholder="الإسم">
                        </div>
                        <label class="col-md-2 control-labelf">جهة الاتصال</label>
                        <div class="col-md-2 ">
                            <select class="form-select form-control typesC " name="typesC">
                                <option value = '' selected>اختر...</option>
                                <option value="الجهات الحكومية"> الجهات الحكومية</option>
                                <option value="المؤسسات والشركات"> المؤسسات والشركات</option>
                                <option value="القطاع غير الربحي"> القطاع غير الربحي</option>
                            </select>
                        </div>
                        @can('admin.contact.viewAll')
                        <label class="col-md-2 control-labelf">المدخل</label>
                        <div class="col-md-2 ">
                            <select class="form-select form-control form-select-lg byUser " name="byUser" id="byUser"
                            aria-label="Default select example">
                            <option value ='' selected>اختر...</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        @endcan
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box {{ $form_class }}">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-grid"></i>إدارة جهات الاتصال : التنبيهات</div>
                    @can('admin.contact.add')
                    <div class="actions">
                        <a href="{{ route('contact.add') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-plus"></i> إضافة </a>
                    </div>
                    @endcan
                </div>
                <div class="portlet-body">
                    @include('admin.layout.error')
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="Contact_table">
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> اسم الجهة </th>
                            <th> النوع </th>
                            <th> المسؤول </th>
                            <th> الهاتف </th>
                            <th> انشئ بواسطة </th>
                            <th>  الحالة </th>
                            <th> تعديل </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
@stop
@section('modal')
    @include('admin.layout.ajax')
@stop
@section('css')

@stop
@section('js')
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
            var oTable = $('#Contact_table').DataTable({
                "processing": true,
                "serverSide": true,
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
                "bJQueryUI": false,
                "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"clearfix">>><"table-scrollable"t><"row"<"col-md-5 col-sm-12"i><"col-md-7 col-sm-12"p>>r',
                "ajax": {
                    url: "{{ route('contact.remember.list') }}",
                    data: function (d) {
                        d.name = $('input[name="name"]').val();
                         d.byUser = $('select[name="byUser"]').val();
                        d.typesC = $('select[name="typesC"]').val();
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
                        "data": "contact_type",
                        "title": "النوع",
                        "orderable": true,
                        "searchable": false
                    },
                    {
                        "data": "master",
                        "title": "المسؤول",
                        "orderable": true,
                        "searchable": false
                    },
                    {
                        "data": "mobile",
                        "title": "الهاتف",
                        "orderable": true,
                        "searchable": false
                    },
                    {
                        "data": "created_by",
                        "title": "انشئ بواسطة",
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
                        "title": "تعديل",
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

              $('.typesC').on('change', function(e) {
                e.preventDefault();
                oTable.draw();
            });
              $('.byUser').on('change', function(e) {
                e.preventDefault();
                oTable.draw();
            });

            $(document).on('click', ".status", function() {
                var id = $(this).data('href');
                var item = $(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('contact.status') }}",
                    data: {'id' : id}
                }).success(function (data) {
                    if(data.type == 'yes')
                    {
                        item.removeClass("red");
                        item.addClass("green-dark");
                        item.html('<i class="fa fa-check"></i> تفعيل');
                    }
                    else if(data.type == 'no')
                    {
                        item.removeClass("green-dark");
                        item.addClass("red");
                        item.html('<i class="fa fa-times"></i> تعطيل ');
                    }
                    toastr[data.status](data.message);
                });
            });
            ///////////////////////////////////////////////////
            $(document).on('click', ".delete", function() {
                var id = $("#delete_id").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('contact.delete') }}",
                    data: {'id' : id}
                }).success(function (data) {
                    toastr[data.status](data.message);
                    oTable.draw();
                });
            });
        });
    </script>
@stop
