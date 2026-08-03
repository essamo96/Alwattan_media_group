@extends('admin.layout.master')
@section('title')
ØªØ¹Ø¯ÙŠÙ„  Ø¹Ù‚Ø§Ø±
@stop
@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <a href="{{ route('dashboard.view') }}">Ø§Ù„Ø±Ø¦ÙŠØ³ÙŠØ©</a>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <a href="{{ route('properties.view') }}">Ø¥Ø¯Ø§Ø±Ø©  Ø§Ù„Ø¹Ù‚Ø§Ø±Ø§Øª</a>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <strong> {{ $info->title }}</strong>
        <i class="fa fa-angle-left"></i>
    </li>
    <li>
        <a href="{{ route('properties.edit',['id' => Crypt::encrypt($info->id)]) }}">ØªØ¹Ø¯ÙŠÙ„ Ø¹Ù‚Ø§Ø±</a>
    </li>
</ul>
@stop

@section('page-title')
<h1 class="page-title"> Ø§Ù„Ø¹Ù‚Ø§Ø±Ø§Øª
    <small>ØªØ¹Ø¯ÙŠÙ„ Ø¹Ù‚Ø§Ø±</small>
</h1>
@stop

@section('page-content')
<div class="portlet box {{ $form_class }}">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>ØªØ¹Ø¯ÙŠÙ„ Ø¹Ù‚Ø§Ø± </div>
    </div>
    <div class="portlet-body form">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="form-horizontal">
            <div class="form-body">
                <div class="tabbable-line boxless tabbable-reversed">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_0" data-toggle="tab"> Ø§Ù„Ø¨ÙŠØ§Ù†Ø§Øª Ø§Ù„Ø§Ø³Ø§Ø³ÙŠØ© </a>
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
                                    <label class="control-label col-md-3">Ø§Ù„Ù‚Ø³Ù…</label>
                                    <div class="col-md-6">
                                        <select name="category_id" class="form-control">
                                            <option value="">Ø§Ø®ØªØ± Ø§Ù„Ù‚Ø³Ù…</option> 
                                            @foreach($series as $sr)
                                            <option value="{{$sr->id}}" {{$info->category_id  == $sr->id ? 'selected' : '' }}>{{$sr->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ù†ÙˆØ¹ Ø§Ù„Ø¹Ù‚Ø§Ø±</label>
                                    <div class="col-md-6">
                                        <select name="property_type" class="form-control">
                                            <option value="">Ø§Ø®ØªØ± Ø§Ù„Ù†ÙˆØ¹</option> 
                                            @foreach($types as $sr)
                                            <option value="{{$sr->id}}" {{$info->property_type  == $sr->id ? 'selected' : '' }}>{{$sr->name_ar}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø§Ù„Ù…Ø¯ÙŠÙ†Ø©</label>
                                    <div class="col-md-6">
                                        <select name="city" class="form-control">
                                            <option value="">Ø§Ø®ØªØ± Ø§Ù„Ù…Ø¯ÙŠÙ†Ø©</option> 
                                            @foreach($cities as $sr)
                                            <option value="{{$sr->id}}" {{$info->city  == $sr->id ? 'selected' : '' }}>{{$sr->name_ar}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø§Ù„Ø³Ø¹Ø±</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$info->price}}" name="price" class="form-control" placeholder="Ø§Ù„Ø³Ø¹Ø±">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø§Ù„Ù…Ø³Ø§Ø­Ø©</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$info->area }}" name="area" class="form-control" placeholder="Ø§Ù„Ù…Ø³Ø§Ø­Ø©">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø¹Ø¯Ø¯ Ø§Ù„Ø­Ù…Ø§Ù…Ø§Øª</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$info->bathroom }}" name="bathroom" class="form-control" placeholder="Ø¹Ø¯Ø¯ Ø§Ù„Ø­Ù…Ø§Ù…Ø§Øª">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø¹Ø¯Ø¯ Ø§Ù„ØºØ±Ù</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$info->bedroom }}" name="bedroom" class="form-control" placeholder="Ø¹Ø¯Ø¯ Ø§Ù„ØºØ±Ù">
                                    </div>
                                </div>                  
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø§Ù„Ø¹Ø§Ø¦Ø¯ Ø§Ù„Ø³Ù†ÙˆÙŠ</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$info->annual_return }}" name="annual_return" class="form-control" placeholder="Ø§Ù„Ø¹Ø§Ø¦Ø¯ Ø§Ù„Ø³Ù†ÙˆÙŠ">
                                    </div>
                                </div>                  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">latitude</label>
                                            <div class="col-md-6">
                                                <input type="number" id="latitude" name="latitude" class="form-control" value="{{$info->latitude }}" step="0.00000001">
                                            </div>
                                        </div>                  
                                        <div class="form-group">
                                            <label class="control-label col-md-3">longitude</label>
                                            <div class="col-md-6">
                                                <input type="number" id="longitude" name="longitude" class="form-control" value="{{$info->longitude }}" step="0.00000001">
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="col-md-5" style="height: 300px; margin-bottom: 15px;">
                                        <div id="map"></div>
                                    </div>
                                </div>               
                                <div id="image" class="form-group">
                                    <label class="control-label col-md-3">ØµÙˆØ±Ø© Ø§Ù„Ø¹Ù‚Ø§Ø±</label>
                                    <div class="col-md-5">
                                        <input id="thumbnail" value="{{ $info->image }}" class="form-control" type="text" name="image" style="direction: ltr;" readonly>
                                        <img id="holder" src="{{ asset($info->image) }}" style="margin-top:15px;max-height:100px;">
                                    </div>
                                    <div class="col-md-1">
                                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> Ø­Ø¯Ø¯ ØµÙˆØ±Ø©
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø¹Ù‚Ø§Ø± Ø¬Ø¯ÙŠØ¯</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="is_new" class="make-switch" data-on-text="&nbsp;ØªÙØ¹ÙŠÙ„&nbsp;" data-off-text="&nbsp;ØªØ¹Ø·ÙŠÙ„&nbsp;" {{ $info->is_new == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ø§Ù„Ø­Ø§Ù„Ø©</label>
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="status" class="make-switch" data-on-text="&nbsp;ØªÙØ¹ÙŠÙ„&nbsp;" data-off-text="&nbsp;ØªØ¹Ø·ÙŠÙ„&nbsp;" {{ $info->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($languages as $item)
                        <div class="tab-pane" id="tab_{{ $loop->iteration }}">
                            <div class="form-group">
                                <label class="control-label col-md-3">Ø§Ù„Ø¹Ù†ÙˆØ§Ù†</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$info->translate($item->prefix)?$info->translate($item->prefix)->title:''}}" name="{{$item->prefix}}_title" class="form-control" placeholder="Ø§Ù„Ø¹Ù†ÙˆØ§Ù†">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Ø§Ù„ØªÙØ§ØµÙŠÙ„</label>
                                <div class="col-md-6">
                                    <textarea name="{{$item->prefix}}_details"  class="form-control ckeditor" rows="3">{{$info->translate($item->prefix)?$info->translate($item->prefix)->details:''}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn default {{ $btn_class }}">Ø­ÙØ¸</button>
                        <a href="{{ route('properties.view') }}" type="button" class="btn default">Ø¥Ù„ØºØ§Ø¡</a>
                        {{ csrf_field() }}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('css')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js"></script>
<style>
    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
    }
</style>
@stop
@section('js')
<script src="vendor/laravel-filemanager/js/lfm.js"></script>
<script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
<script>CKEDITOR.config.customConfig = "{{ asset('assets/admin/ckeditor/config.js') }}?v={{ filemtime(public_path('assets/admin/ckeditor/config.js')) }}";</script>
<script type="text/javascript">
var domain = "{{ asset('/admin').'/file_manager' }}";
$('#lfm').filemanager('image', {prefix: domain});
mapboxgl.accessToken = '{{ config('services.mapbox.token') }}';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [<?= $info->longitude ? $info->longitude : '-0.11462688775253582' ?>, <?= $info->latitude ? $info->latitude : '51.526867517823575' ?>],
    zoom: 16
});
<?php if ($info->longitude) { ?>
    const marker1 = new mapboxgl.Marker()
            .setLngLat([<?= $info->longitude ?>, <?= $info->latitude ?>])
            .addTo(map);
    map.addControl(new mapboxgl.NavigationControl());
<?php } ?>


map.on('style.load', function () {
    map.on('click', function (e) {
        var coordinates = e.lngLat;
        $('#longitude').val(coordinates.lng.toFixed(5));
        $('#latitude').val(coordinates.lat.toFixed(5));
    });
});
</script>
@stop

