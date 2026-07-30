@extends('admin.layout.master')

@section('title')
تعديل قسم
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
        <a href="{{ route('categories.view') }}">إدارة الأقسام</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <strong> {{ $info->name }}</strong>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('categories.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل قسم</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> الأقسام
    <small>تعديل قسم</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>تعديل قسم </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="tabbable-line boxless tabbable-reversed">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_0" data-toggle="tab"> البيانات الاساسية </a>
                        </li>
                        @foreach($languages as $item)
                        <li>
                            <a href="#tab_{{ $loop->iteration}}" data-toggle="tab">{{$item->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_0">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3">الأقسام</label>
                                    <div class="col-md-6">
                                        <select name="category_id" class="form-control" id="category_id">
                                            <option value="0" {{ old('category_id') == 0 ? 'selected' : '' }}>لا يوجد قسم اب</option>
                                            @foreach($categories as $item)
                                            <option value="{{ $item->id }}" {{ $info->category_id == $item->id ? 'selected' : '' }}> {{ $item->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">الترتيب</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->sort }}" name="sort" id="sort" class="form-control" placeholder="الترتيب">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">عدد الأعمدة</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->col_no }}" name="col_no" id="sort" class="form-control" placeholder="عدد الأعمدة">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">الكلمات الدلالية</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->tags }}" name="tags" id="tags" class="form-control input-large" data-role="tagsinput">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">الرابط المخصص</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ $info->slug }}" name="slug" id="slug" class="form-control" placeholder="الرابط المخصص">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">الحالة</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">يظهر في القائمة</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="in_menu" class="make-switch" data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;" {{ $info->in_menu == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($languages as $item)

                        <div class="tab-pane" id="tab_{{ $loop->iteration }}">
                            <div class="form-group">
                                <label class="control-label col-md-3">الاسم </label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$info->translate($item->prefix)?$info->translate($item->prefix)->name:''}}" name="{{$item->prefix}}_name" class="form-control" placeholder="اسم الدولة">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    <a href="{{ route('categories.view') }}" type="button" class="btn default">إلغاء</a>
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
