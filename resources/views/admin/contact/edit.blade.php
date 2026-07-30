@extends('admin.layout.master')
@section('title')
    تعديل جهة اتصال
@stop
@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-left"></i>
        </li>
        <li>
            <a href="{{ route('contact.view') }}">إدارة جهات اتصال</a>
            <i class="fa fa-angle-left"></i>
        </li>
        <li>
            <a href="{{ route('contact.add') }}">تعديل جهة اتصال جديد</a>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> إدارة جهات اتصال
        <small>تعديل جهة اتصال جديد</small>
    </h1>
@stop

@section('page-content')
    <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-grid"></i>تعديل جهة اتصال جديد
            </div>
            <div class="actions ">
                <a href="{{ URL::previous() }}" class="btn btn-default btn-sm" style="color: #ffffff">
                    <i class="fa fa-backward"></i> <strong style="color: #ffffff"> رجوع
                    </strong> </a>
            </div>
        </div>
        <div class="portlet-body form">
            @include('admin.layout.error')
            <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="tabbable-line boxless tabbable-reversed">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_0" data-toggle="tab">معلومات جهة الاتصال</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_0">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-2">اسم الجهة</label>
                                        <div class="col-md-3">
                                            <input type="text" value="{{ $info->name }}" name="name"
                                                class="form-control">
                                        </div>
                                        <label class="control-label col-md-2">القطاع</label>
                                        <div class="col-md-3">
                                            <select name="contact_type" id="contact_type" class="form-control">
                                                <option value="" selected>اختر...</option>
                                                <option value="الجهات الحكومية"
                                                    {{ $info->contact_type == 'الجهات الحكومية' ? 'selected' : '' }}> الجهات
                                                    الحكومية</option>
                                                <option value="المؤسسات والشركات"
                                                    {{ $info->contact_type == 'المؤسسات والشركات' ? 'selected' : '' }}>
                                                    المؤسسات والشركات</option>
                                                <option value="القطاع غير الربحي"
                                                    {{ $info->contact_type == 'القطاع غير الربحي' ? 'selected' : '' }}>
                                                    القطاع غير الربحي</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">الهاتف</label>
                                        <div class="col-md-3">
                                            <input type="text" value="{{ $info->mobile }}" name="mobile"
                                                class="form-control">
                                        </div>
                                        <label class="control-label col-md-2"> الهاتف البديل</label>
                                        <div class="col-md-3">
                                            <input type="text" value="{{ $info->another_mobile }}" name="another_mobile"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">الشخص المسؤول</label>
                                        <div class="col-md-3">
                                            <input type="text" value="{{ $info->master }}" name="master"
                                                class="form-control">
                                        </div>
                                        <label class="control-label col-md-2"> الايميل</label>
                                        <div class="col-md-3">
                                            <input type="email" value="{{ $info->email }}" name="email"
                                                class="form-control" data-role="emailinput">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">تاريخ التنبيه</label>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group date" data-date-format="dd.mm.yyyy">
                                                    <input type="date" class="form-control" placeholder="remember_date"
                                                        name="remember_date" value="{{ $info->remember_date }}">
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">اضف ملاحظة</label>
                                        <div class="col-md-9">
                                            <textarea id="notes" type="text" value="" rows="30" cols="300" class="form-control"
                                                name="notes">{{ $info->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                        <a href="{{ route('contact.view') }}" type="button" class="btn default">إلغاء</a>
                        {{ csrf_field() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

    <link href="{{ asset('assets/admin/global/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet"
        type="text/css" />
    <script src="{{ asset('assets/admin/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/date/bootstrap-datepicker.min.css') }}">
    <script src="{{ asset('assets/admin/date/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            //   $('.input-group .date').datepicker({
            //                 format: "dd/mm/yyyy"
            //             });

            ClassicEditor
                .create(document.querySelector('#notes'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@stop
