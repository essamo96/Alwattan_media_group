@extends('admin.layout.master')
@section('title')
تعديل مستخدم
@stop
@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="{{ route('dashboard.view') }}">الرئيسية</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('users.view') }}">إدارة المستخدمين</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <strong> {{$info->name}}</strong>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('users.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل مستخدم</a>
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
            <i class="icon-user"></i>تعديل مستخدم </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form method="post" action="" role="form" class="form-horizontal">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3">اسم المستخدم (User Name) </label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->username }}" name="username" id="username" class="form-control" placeholder="اسم المستخدم (User Name)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الإسم الشخصي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->name }}" name="name" id="name" class="form-control" placeholder="الإسم الشخصي">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">إدارة الصلاحيات</label>
                        <div class="col-md-6">
                            <select name="role" id="role" class="form-control">
                                @foreach($roles as $item)
                                <option value="{{ $item->id }}" {{ $info->role == $item->id ? 'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach
                            </select>
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
                    <a href="{{ route('users.view') }}" type="button" class="btn default">إلغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop