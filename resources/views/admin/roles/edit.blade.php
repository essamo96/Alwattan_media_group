@extends('admin.layout.master')

@section('title')
    تعديل صلاحية
@stop

@section('css')

@stop

@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('roles.view') }}">إدارة الصلاحيات</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <strong> {{ $info->name }}</strong>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('roles.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل صلاحية</a>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> إدارة الصلاحيات
        <small></small>
    </h1>
@stop

@section('page-content')
    <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-direction"></i>تعديل صلاحية </div>
        </div>
        <div class="portlet-body form">
            @include('admin.layout.error')
            <form role="form" method="post" action="" class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">إسم المجموعة</label>
                            <div class="col-md-6">
                                <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="Role">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">الحالة</label>
                            <div class="col-md-6">
                                <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                        <a href="{{ route('roles.view') }}" type="button" class="btn default">إلغاء</a>
                        {{ csrf_field() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')

@stop