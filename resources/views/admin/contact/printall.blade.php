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
            <div class="portlet-body" id="print">
                @include('admin.layout.error')
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="Contact_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th>اسم الجهة</th>
                            <th>الشخص المسؤول</th>
                            <th>رقم الجوال</th>
                            <th>الرقم البديل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($info as $contact)
                        <tr>
                            <td>#</td>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->master }}</td>
                            <td>{{ $contact->mobile }}</td>
                            <td>{{ $contact->another_mobile }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop