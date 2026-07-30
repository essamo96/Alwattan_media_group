@extends('admin.layout.master')

@section('title')
    إدارة الصلاحيات
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
            <a href="{{ route('roles.permissions',['id' => Crypt::encrypt($info->id)]) }}">تحديد صلاحيات المجموعة</a>
        </li>

    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> الصلاحيات
        <small>إدارة الصلاحيات</small>
    </h1>
@stop

@section('page-content')
    <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-directions"></i> إدارة الصلاحيات </div>
        </div>
        <div class="portlet-body form">
            @include('admin.layout.error')
            <form role="form" method="post" id="" action="" class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        @foreach($permission_group as $row)
                            <div class="col-md-12">
                                <h3 class="font-blue form-section">{{ $row->name }}</h3>
                                <div class="icheck-list form-group">
                                    @foreach($row->permissions as $item)
                                        <label class="col-md-3">
                                            <input id="permissions[]" name="permissions[]" type="checkbox" {{ in_array($item->id,array_column ($role_permissions,'permission_id')) ? 'checked' : '' }} value="{{ $item->id }}" class="icheck" data-checkbox="icheckbox_flat-blue">{{ trans('permissions.'.$item->name) }}</label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    <a href="{{ route('roles.view') }}" type="button" class="btn default">إلغاء</a>
                    {{ csrf_field() }}
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')

@stop

@section('modals')

@stop
