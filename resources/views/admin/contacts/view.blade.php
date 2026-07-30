@extends('admin.layout.master')

@section('title')
    إدارة إتصل بنا
@stop

@section('page-title')
    <h3 class="page-title"> إتصل بنا
        <small>إدارة إتصل بنا</small>
    </h3>
@stop

@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('contacts.view') }}">اتصل بنا</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>إدارة إتصل بنا</span>
        </li>
    </ul>
@stop

@section('page-content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box {{ $form_class }}">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i>
                        بحث
                    </div>
                </div>
                <div class="portlet-body form">

                    <form method="post" action="" role="form" id="" class="form-horizontal">
                        <div class="form-body">

                            <div class="form-group">
                                <label class="col-md-3 control-label">الإسم</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{ old('name') }}" class="form-control searchable" name="name" id="name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">البريد الإلكتروني</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{ old('email') }}" class="form-control searchable" name="email" id="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">الجوال</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{ old('mobile') }}" class="form-control searchable" name="mobile" id="mobile">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box {{ $form_class }}">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-users"></i>إدارة إتصل بنا </div>
                </div>
                <div class="portlet-body">
                    @include('admin.layout.error')
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="contacts_table">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th> الإسم </th>
                            <th> البريد الإلكتروني </th>
                            <th> الجوال </th>
                            <th> الموضوع </th>
                            <th> الحالة </th>
                            <th> أدوات </th>
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
            ////////////////////////////////////////////////////
            var oTable = $('#contacts_table').DataTable({
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
                }).success(function (data) {
                    if(data.type == 'yes')
                    {
                        item.removeClass("red");
                        item.addClass("green-dark");
                        item.html('<i class="fa fa-check"></i> تم التواصل');
                    }
                    else if(data.type == 'no')
                    {
                        item.removeClass("green-dark");
                        item.addClass("red");
                        item.html('<i class="fa fa-times"></i> قيد الإنتظار ');
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
                }).success(function (data) {
                    toastr[data.status](data.message);
                    oTable.draw();
                });
            });
        });
    </script>
@stop