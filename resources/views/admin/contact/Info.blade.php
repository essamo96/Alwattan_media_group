@extends('admin.layout.master')

@section('title')
    عرض بيانات القسم
@stop

@section('css')

@stop

@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('contact.view') }}">إدارة جهات اتصال</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('contact.add') }}">عرض تفاصيل جهة الاتصال</a>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> الأقسام
        <small>عرض بيانات القسم</small>
    </h1>
@stop

@section('page-content')
    <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-grid"></i>عرض تفاصيل جهة الاتصال
            </div>
            <div class="actions ">
                <a href="{{ URL::previous() }}" class="btn btn-default btn-sm" style="color: #ffffff">
                    <i class="fa fa-backward"></i> <strong style="color: #ffffff"> رجوع
                    </strong> </a>
            </div>
        </div>
        <div class="portlet-body form">
            @include('admin.layout.error')
            <form role="form" class="form-horizontal">
                <div class="form-body">


                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_0">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-2">اسم الجهة</label>
                                    <div class="col-md-3">
                                        <input type="text" value="{{ $info->name }}" name="name" id="name"
                                            readonly class="form-control" placeholder="name">
                                    </div>
                                    <label class="control-label col-md-2">نوع الجهة</label>
                                    <div class="col-md-3">
                                        <select name="contact_type" id="contact_type" class="form-control" disabled
                                            readonly>
                                            <option value=" " selected>اختر...</option>
                                            <option value="الجهات الحكومية"
                                                {{ $info->contact_type == 'الجهات الحكومية' ? 'selected' : '' }}> الجهات
                                                الحكومية</option>
                                            <option value="المؤسسات والشركات"
                                                {{ $info->contact_type == 'المؤسسات والشركات' ? 'selected' : '' }}> المؤسسات
                                                والشركات</option>
                                            <option value="القطاع غير الربحي"
                                                {{ $info->contact_type == 'القطاع غير الربحي' ? 'selected' : '' }}> القطاع
                                                غير الربحي</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">الهاتف</label>
                                    <div class="col-md-3">
                                        <input type="text" value="{{ $info->mobile }}" name="mobile" id="mobile"
                                            readonly class="form-control" placeholder="الهاتف">
                                    </div>
                                    <label class="control-label col-md-2"> الهاتف البديل</label>
                                    <div class="col-md-3">
                                        <input type="text" value="{{ $info->another_mobile }}" name="another_mobile"
                                            readonly id="another_mobile" class="form-control" placeholder="الهاتف البديل">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">الشخص المسؤول</label>
                                    <div class="col-md-3">
                                        <input type="text" value="{{ $info->master }}" readonly name="master"
                                            id="master" class="form-control">
                                    </div>
                                    <label class="control-label col-md-2"> الايميل</label>
                                    <div class="col-md-3">
                                        <input type="email" value="{{ $info->email }}" readonly name="email"
                                            id="email" placeholder="someone@hotmail.com"
                                            class="form-control input-large" data-role="emailinput">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">تاريخ التنبيه</label>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" readonly placeholder="remember_date"
                                            name="remember_date" value="{{ $info->remember_date }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">اضف ملاحظة</label>
                                    <div class="col-md-9">
                                        <textarea style="resize: none; width: 853px;" readonly id="notes" type="text" value="" rows="8"
                                            cols="8" class="form-control contact_note" name="notes">{!! strip_tags($info->notes) !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <a href="{{ route('contact.view') }}" type="button" class="btn default">عودة</a>
                        {{ csrf_field() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
