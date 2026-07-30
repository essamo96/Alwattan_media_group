@extends('admin.layout.master')

@section('title')
    تغيير كلمة المرور
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
            <a href="{{ route('users.view') }}">إدارة المستخدمين</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <strong> {{  $info->name }}</strong>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('users.password',['id' => Crypt::encrypt($info->id)]) }}">تغيير كلمة المرور</a>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> إدارة المستخدمين
        <small></small>
    </h1>
@stop

@section('page-content')
    <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-key"></i>تغيير كلمة المرور </div>
        </div>
        <div class="portlet-body form">
            @include('admin.layout.error')
            <form method="post" action="" role="form" class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">الإسم</label>
                            <div class="col-md-6">
                                <input type="text" value="{{ $info->username }}" name="username" id="username" class="form-control" placeholder="الإسم" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">الإسم الكامل</label>
                            <div class="col-md-6">
                                <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="الإسم الكامل" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">كلمة المرور</label>
                            <div class="col-md-6">
                                <input type="password" value="" name="password" id="password" class="form-control" placeholder="كلمة المرور">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">تأكيد كلمة المرور</label>
                            <div class="col-md-6">
                                <input type="password" value="" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                        <a href="{{ route('users.view') }}" type="button" class="btn default">إلغاء</a>
                        {{ csrf_field() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')

@stop

@section('modals')

@stop
