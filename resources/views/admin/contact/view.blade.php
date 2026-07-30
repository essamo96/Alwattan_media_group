@extends('admin.layout.master')
@section('title')
    ادارة جهات الاتصال
@stop
@section('css')
    <style>
        .empty {
            justify-content: center;
            color: red;
            font-size: 18px;
            font-family: -webkit-body;
        }
        .empty strong{
            color: #000000
        }
           .swal2-show {
            background-color: #00142ba9;
            border-radius: 20px;
            color: white;
        }

        .swal2-title {
            color: #f5a700;
        }

        .swal2-success-circular-line-left,
        .swal2-success-circular-line-right,
        .swal2-success-fix {
            visibility: hidden;
        }

        .swal2-container.swal2-center>.swal2-popup {
            width: 640px;
            height: 370px;
            color: white;
            background-color: #1e1c1abf;
            border-radius: 25px;
        }

        .swal2-html-container {
            font-size: 17px;
        }

        .swal2-input {
            height: 2.625em;
            padding: 0 0.75em;
            width: 256px;
        }

        .swal2-styled.swal2-confirm {
            border: 0;
            border-radius: 0.25em;
            background: initial;
            background-color: #3ebce5;
            color: #fff;
            font-size: 1.3rem;
        }

        .swal2-styled.swal2-cancel {
            border: 0;
            border-radius: -1.75em;
            background: initial;
            background-color: #ff0e0e;
            color: #fff;
            font-size: 1.3em;
        }

        .swal2-textarea {
            width: 250px;
            height: 70px;
        }

        #swal_t {

            box-sizing: border-box;
            width: 80%;
            transition: border-color .1s, box-shadow .1s;
            border: 1px solid #d9d9d9;
            border-radius: 0.1875em;
            background: rgba(255, 254, 254, 0);
            box-shadow: inset 0 1px 1px rgb(0 0 0 / 6%), 0 0 0 3px rgb(0 0 0 / 0%);
            color: #ffff;
            font-size: 1.125em;


        }
    </style>
@endsection
@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-left"></i>
        </li>
        <li>
            <a href="{{ route('contact.view') }}">ادارة جهات الاتصال</a>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title">ادارة جهات الاتصال
        <small>كافة جهات الاتصال</small>
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
                        <label class="col-md-2 control-label">اسم الجهة</label>
                        <div class="col-md-2">
                            <input type="text" name="name" id="name" class="form-control searchable"
                                placeholder="الإسم">
                        </div>
                        <label class="col-md-2 control-label">القطاع</label>
                        <div class="col-md-2 ">
                            <select class="form-select form-control typesC " name="typesC">
                                <option value='' selected>اختر...</option>
                                <option value="الجهات الحكومية"> الجهات الحكومية</option>
                                <option value="المؤسسات والشركات"> المؤسسات والشركات</option>
                                <option value="القطاع غير الربحي"> القطاع غير الربحي</option>
                            </select>
                        </div>
                        @can('admin.contact.viewAll')
                            <label class="col-md-2 control-label">المدخل</label>
                            <div class="col-md-2 ">
                                <select class="form-select form-control form-select-lg byUser " name="byUser" id="byUser"
                                    aria-label="Default select example">
                                    <option value='' selected>اختر...</option>
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
                        <i class="icon-grid"></i>إدارة جهات الاتصال
                    </div>
                    @can('admin.contact.add')
                        <div class="actions">
                            <a href="{{ route('contact.add') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-plus"></i> إضافة </a>
                        </div>
                    @endcan
                    @can('admin.contact.viewAll')
                        <div class="actions">
                            <a href="{{ route('contact.printall') }}" class="btn btn-default btn-sm btnPrint">
                                <i class="fa fa-print"></i> طباعة الكل </a>
                        </div>
                    @endcan
                </div>
                <div class="portlet-body">
                    @include('admin.layout.error')
                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                        id="Contact_table">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>القطاع</th>
                                <th>اسم الجهة</th>
                                <th>الشخص المسؤول</th>
                                <th> الهاتف </th>
                                <th> الايميل </th>
                                <th> انشئ بواسطة </th>
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
    <script src="{{ url('assets/jquery.printPage.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".btnPrint").printPage();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ////////////////////////////////////////////////////
            $('#confirm').on('show.bs.modal', function(e) {
                $("#delete_id").val($(e.relatedTarget).data('href'));
            });
            var oTable = $('#Contact_table').DataTable({
                "stateSave": true,
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
                    url: "{{ route('contact.list') }}",
                    data: function(d) {
                        d.name = $('input[name="name"]').val();
                        d.byUser = $('select[name="byUser"]').val();
                        d.typesC = $('select[name="typesC"]').val();
                    }
                },
                "order": [
                    [1, 'asc']
                ],
                "columnDefs": [{
                    "targets": "_all",
                    "defaultContent": ""
                }],
                "columns": [{
                        "data": "",
                        "title": "#",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": "contact_type",
                        "title": "القطاع",
                        "orderable": true,
                        "searchable": false
                    },
                    {
                        "data": "name",
                        "title": "اسم الجهة",
                        "orderable": true,
                        "searchable": false
                    },

                    {
                        "data": "master",
                        "title": "الشخص المسؤول",
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
                        "data": "email",
                        "title": "الايميل",
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
                        "data": "actions",
                        "title": "تعديل",
                        "orderable": false,
                        "searchable": false
                    }
                ],
                "fnDrawCallback": function(oSettings) {
                    $('.tooltips').tooltip();

                    oTable.column(0).nodes().each(function(cell, i) {
                        cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                    });
                    $(".btnPrint").printPage();
                }
            });
            
            $('.searchable').on('input', function(e) {
                e.preventDefault();
                oTable.draw();
            });

            $('button[type="reset"]').on('click', function(e) {
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
            

            //        $(document).on('click', ".status", function () {
            //            var id = $(this).data('href');
            //            var item = $(this);
            //            $.ajax({
            //                type: "POST",
            //                url: "{{ route('contact.status') }}",
            //                data: {'id': id}
            //            }).success(function (data) {
            //                if (data.type == 'yes')
            //                {
            //                    item.removeClass("red");
            //                    item.addClass("green-dark");
            //                    item.html('<i class="fa fa-check"></i> تفعيل');
            //                } else if (data.type == 'no')
            //                {
            //                    item.removeClass("green-dark");
            //                    item.addClass("red");
            //                    item.html('<i class="fa fa-times"></i> تعطيل ');
            //                }
            //                toastr[data.status](data.message);
            //            });
            //        });
            ///////////////////////////////////////////////////
            $(document).on('click', ".delete", function() {
                var id = $("#delete_id").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('contact.delete') }}",
                    data: {
                        'id': id
                    }
                }).success(function(data) {
                    toastr[data.status](data.message);
                    oTable.draw();
                });
            });
            oTable.state.load();
        });
    </script>
    <script>
           $(document).on('click', "#contactInfo", function() {
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
    </script>
    

@stop
