@extends('admin.layout.master')

@section('title')
تعديل القائمة
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
        <a href="{{ route('menus.view') }}">ادارة قوائم الموقع</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <strong> {{ $info->name_ar }}</strong>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <a href="{{ route('menus.edit',['id' => Crypt::encrypt($info->id)]) }}">تعديل القائمة</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> ادارة قوائم الموقع
    <small></small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-puzzle"></i>تعديل القائمة </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3">الاسم بالعربي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name_ar', $info->name_ar) }}" name="name_ar" class="form-control" placeholder="الاسم بالعربي">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الاسم بالانجليزي</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('name_en', $info->name_en) }}" name="name_en" class="form-control" placeholder="الاسم بالانجليزي">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">القائمة الاب</label>
                        <div class="col-md-6">
                            @if($has_children)
                            <input type="hidden" name="parent_id" value="0">
                            <input type="text" class="form-control" value="لا يوجد - قائمة رئيسية" disabled>
                            <span class="help-block text-warning">هذه القائمة لها قوائم فرعية، لذا تبقى قائمة رئيسية</span>
                            @else
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="0" {{ old('parent_id', $info->parent_id) == 0 ? 'selected' : '' }}>لا يوجد - قائمة رئيسية</option>
                                @foreach($parent_menus as $item)
                                <option value="{{ $item->id }}" {{ old('parent_id', $info->parent_id) == $item->id ? 'selected' : '' }}> {{ $item->name_ar }} </option>
                                @endforeach
                            </select>
                            <span class="help-block">اختر قائمة رئيسية لتظهر هذه القائمة كعنصر منسدل تحتها</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">المسار</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('url', $info->url) }}" name="url" class="form-control" placeholder="#section-hero او https://example.com">
                            <span class="help-block">المسارات التي تبدأ بـ # تعتبر أقسام داخل الصفحة الرئيسية. للقوائم التي تُستخدم فقط لفتح قائمة فرعية اترك # كما هي</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الايقونة</label>
                        <div class="col-md-6">
                            <input type="text" value="{{ old('icon', $info->icon) }}" name="icon" class="form-control" placeholder="fa fa-home">
                            <span class="help-block">كلاس الايقونة (Font Awesome) الظاهرة بجانب اسم القائمة</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">اللوجو (اختياري)</label>
                        <div class="col-md-6">
                            @if(!empty($info->image))
                            <img src="{{ asset('uploads/menus/'.$info->image) }}" alt="" style="height: 40px;display:block;margin-bottom:8px;">
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <span class="help-block">في حال رفع لوجو سيتم عرضه بدلاً من الايقونة</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">فتح الرابط</label>
                        <div class="col-md-6">
                            <select name="target" class="form-control">
                                <option value="_self" {{ $info->target == '_self' ? 'selected' : '' }}>في نفس الصفحة</option>
                                <option value="_blank" {{ $info->target == '_blank' ? 'selected' : '' }}>في صفحة جديدة</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الترتيب</label>
                        <div class="col-md-6">
                            <input type="number" value="{{ old('sort', $info->sort) }}" name="sort" class="form-control" placeholder="الترتيب">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">الحالة</label>
                        <div class="col-md-6">
                            <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;Enable&nbsp;" data-off-text="&nbsp;Disable&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="col-md-offset-3 col-md-6">
                    <button type="submit" class="btn default {{ $btn_class }}">تعديل</button>
                    <a href="{{ route('menus.view') }}" type="button" class="btn default">الغاء</a>
                    {{ csrf_field() }}
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
@stop
