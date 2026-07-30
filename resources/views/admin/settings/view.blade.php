@extends('admin.layout.master')

@section('title')
الإعدادات
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
        <a href="{{ route('settings.view') }}">إعدادات الموقع</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title">الإعدادات
    <small>إعدادات الموقع</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-key"></i>إعدادات الموقع </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form method="post" action="" role="form" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label col-md-3">الإسم عربي</label>
                        <div class="col-md-9">
                            <input type="text" value="{{ $info->title_ar }}" name="title_ar" id="title" class="form-control" placeholder="الإسم">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-md-3">الإسم انجليزي</label>
                        <div class="col-md-9">
                            <input type="text" value="{{ $info->title_en }}" name="title_en" id="title" class="form-control" placeholder="name">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-md-3">الوصف عربي</label>
                        <div class="col-md-9">
                            <textarea name="description_ar" id="descs" class="form-control" rows="6" style="resize: none">{{ $info->description_ar }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-md-3">الوصف انجليزي</label>
                        <div class="col-md-9">
                            <textarea name="description_en" id="descs_en" class="form-control" rows="6" style="resize: none">{{ $info->description_en }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الكلمات الدلالية عربي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->tags_ar }}" name="tags_ar" id="tags" class="form-control input-large" data-role="tagsinput">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الكلمات الدلالية انجليزي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->tags_en }}" name="tags_en" id="tags" class="form-control input-large" data-role="tagsinput">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-md-3">رقم هاتف1</label>
                        <div class="col-md-9">
                            <input type="text" value="{{ $info->phone1 }}" name="phone1" id="phone1" class="form-control" placeholder="رقم هاتف1">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label col-md-3">رقم هاتف2</label>
                        <div class="col-md-9">
                            <input type="text" value="{{ $info->phone2 }}" name="phone2" id="phone2" class="form-control" placeholder="رقم هاتف2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label col-md-5">مشاريع مكتملة</label>
                            <div class="col-md-7">
                                <input type="text" value="{{ $info->projects }}" name="projects" id="phone1" class="form-control" placeholder="مشاريع مكتملة">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label col-md-3">الافكار</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $info->idea }}" name="idea" id="phone1" class="form-control" placeholder="الافكار">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label col-md-5">سنوات الخبرة</label>
                            <div class="col-md-7">
                                <input type="text" value="{{ $info->experinace }}" name="experinace" id="phone2" class="form-control" placeholder="سنوات الخبرة">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label col-md-3">الجوائز</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $info->award }}" name="award" id="phone2" class="form-control" placeholder="الجوائز">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">العنوان</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->address }}" name="address" id="address" class="form-control" placeholder="العنوان">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">بريد التواصل</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ $info->contact_email }}" name="contact_email" id="contact_email" class="form-control" placeholder="contact_email">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">حفظ</button>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop