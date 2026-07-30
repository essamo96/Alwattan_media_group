@extends('admin.layout.master')

@section('title')
    إدارة الشبكات الإجتماعية
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
            <a href="{{ route('socials.view') }}">الشبكات الإجتماعية</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>إدارة الشبكات الإجتماعية</span>
        </li>
    </ul>
@stop

@section('page-title')
    <h1 class="page-title"> الشبكات الإجتماعية
        <small>إدارة الشبكات الإجتماعية</small>
    </h1>
@stop

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box {{ $form_class }}">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-social-twitter"></i>إدارة الشبكات الإجتماعية</div>
                </div>
                <div class="portlet-body form">
                    @include('admin.layout.error')
                    <form role="form" method="post" action="" class="">
                        <div class="form-body">
                            <h3 class="form-section">الشبكات الإجتماعية</h3>
                            @foreach($socials as $row)
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" value="{{ $row->name }}" name="name" id="name" class="form-control" placeholder="الفيس بوك" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" value="{{ $row->link }}" name="link[{{  $row->id }}]" id="link" class="form-control" placeholder="الرابط">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" value="{{ $row->icon }}" name="icon[{{  $row->id }}]" id="icon" class="form-control" placeholder="الايقونة">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="hidden" value="0" name="status[{{  $row->id }}]">
                                            <input type="checkbox" value="1" name="status[{{  $row->id }}]" class="make-switch" data-on-text="&nbsp;تفعيل&nbsp;" data-off-text="&nbsp;تعطيل&nbsp;" {{ $row->status == 1 ? "checked" : "" }}>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ $row->id }}" name="id[]">
                            @endforeach
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                            {{ csrf_field() }}
                        </div>
                    </form>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
@stop

@section('js')

@stop

