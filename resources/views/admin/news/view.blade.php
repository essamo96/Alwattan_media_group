@extends('admin.layout.master')

@section('title')
إدارة الأخبار
@stop

@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <i class="icon-home"></i>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('news.view') }}">الأخبار</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <span>إدارة الأخبار</span>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> الاخبار
    <small>إدارة الأخبار</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-magnifier"></i>البحث  </div>
    </div>
    <div class="portlet-body">
        <form role="form" method="post" id="add_user" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-1 control-label">الخبر</label>
                    <div class="col-md-5">
                        <input type="text" name="news" id="news" class="form-control searchable" placeholder="الخبر">
                    </div>
                    <label class="col-md-1 control-label">الحالة</label>
                    <div class="col-md-2">
                        <select id="publish" name="publish"  class="form-control">
                            <option value="-1">الكل</option>
                            <option value="0">غير منشور</option>
                            <option value="1">منشور</option>
                        </select>
                    </div>
                    <label class="col-md-1 control-label">القسم</label>
                    <div class="col-md-2">
                        <select name="category_id" class="form-control" id="category_id">
                            <option value="-1">كل الاقسام</option>
                            @foreach($categories as $item)
                            <option value="{{ $item->id }}"> {{ $item->name }} </option>
                            @endforeach
                        </select>
                    </div>
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
                    <i class="icon-book-open"></i>إدارة الأخبار</div>
                @can('admin.news.add')
                <div class="actions">
                    <a href="{{ route('news.add') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> إضافة </a>
                    <a href="{{ route('news.cleaAllCache') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-trash-o"></i> حذف الكاش </a>
                </div>
                @endcan
            </div>
            <div class="portlet-body">
                @include('admin.layout.error')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="news_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> الخبر </th>
                            <th> القسم </th>
                            <th> اللغة </th>
                            @can('admin.news.publish')
                            <th> النشر </th>
                            @endcan
                            <th> ادوات </th>
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
<style>
    @media (max-width: 767px) {
        table th,table td {
            width: 100% !important;
            display: block;
            min-height: 20px;
        }
        td:nth-child(2){
            white-space: pre-line;
        }
    }
</style>
@stop
@section('js')

<script type="text/javascript">
    $(document).ready(function () {
    $.ajaxSetup({headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')}});
    ////////////////////////////////////////////////////
    $('#confirm').on('show.bs.modal', function (e) {
    $("#delete_id").val($(e.relatedTarget).data('href'));
    });
    var oTable = $('#news_table').DataTable({
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
            url: "{{ route('news.list') }}",
                    data: function (d) {
                    d.title = $('input[name="news"]').val();
                    d.publish = $("#publish").val();
                    d.category = $("#category_id").val();
                    }
            },
            "columnDefs": [{
            "targets": "_all",
                    "defaultContent": ""
            }],
            "columns": [
            {"data": "", "title": "#", "orderable": false, "searchable": false},
            {
            "data": "title",
                    "title": "الخبر",
                    "orderable": true,
                    "searchable": false
            },
            {
            "data": "category_name",
                    "title": "القسم",
                    "orderable": true,
                    "searchable": false
            },
            {
            "data": "language",
                    "title": "اللغة",
                    "orderable": true,
                    "searchable": false
            },
                    @can('admin.news.publish')
            {
            "data": "publish",
                    "title": "النشر",
                    "orderable": true,
                    "searchable": false
            },
                    @endcan

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
    $('.searchable').on('input', function (e) {
    e.preventDefault();
    oTable.draw();
    });
    $('#publish').on('click', function (e) {
    e.preventDefault();
    oTable.draw();
    });
    $('#category_id').on('change', function (e) {
    e.preventDefault();
    oTable.draw();
    });
    $("#add_user").submit(function(e){
    e.preventDefault();
    });
    $('button[type="reset"]').on('click', function (e) {
    e.preventDefault();
    $(this).closest('form').get(0).reset();
    oTable.draw();
    });
    $(document).on('click', ".publish", function () {
    var id = $(this).data('href');
    var item = $(this);
    $.ajax({
    type: "POST",
            url: "{{ route('news.publish') }}",
            data: {'id': id}
    }).success(function (data) {
    if (data.type == 'yes')
    {
    item.removeClass("red");
    item.addClass("green-dark");
    item.html('<i class="fa fa-check"></i> مفعل');
    } else if (data.type == 'no')
    {
    item.removeClass("green-dark");
    item.addClass("red");
    item.html('<i class="fa fa-times"></i> معطل ');
    }
    toastr[data.status](data.message);
    });
    });
    ///////////////////////////////////////////////////
    $(document).on('click', ".delete", function () {
    var id = $("#delete_id").val();
    $.ajax({
    type: "POST",
            url: "{{ route('news.delete') }}",
            data: {'id': id}
    }).success(function (data) {
    toastr[data.status](data.message);
    oTable.draw();
    });
    });
    });
</script>
@stop