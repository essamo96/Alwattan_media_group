@extends('admin.layout.master')
@section('title')
طباعة جهات الاتصال
@stop
@section('page-content')
<div class="row">
    <div class="col-md-12" id="xx">
        <label class="tx-gray-600">معلومات جهة الاتصال</label>
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box {{ $form_class }}"  >
            <div class="portlet-title" >
                <div class="caption">
                    <i class="icon-grid"></i>إدارة جهات الاتصال
                </div>
            </div>
            <div class="portlet-body">
                @include('admin.layout.error')
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>#</td>
                            <td>{{ $info->name }}</td>
                            <td>{{ $info->master }}</td>
                            <td>{{ $info->mobile }}</td>
                            <td>{{ $info->another_mobile }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@stop