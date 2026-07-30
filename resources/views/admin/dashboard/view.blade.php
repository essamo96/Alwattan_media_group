@extends('admin.layout.master')
@section('title')
لوحة التحكم - الصفحة الرئيسية
@stop

@section('page-title')
<h3 class="page-title"> الصفحة الرئيسية
    <small>إحصائيات</small>
</h3>
@stop

@section('page-breadcrumb')
<ul class="page-breadcrumb">
    <li>
        <i class="fa fa-home"></i>
        <a href="{{ route('dashboard.view') }}"> الصفحة الرئيسية</a>
        <i class="fa fa-angle-right"></i>
    </li>
    <li>
        <span>إحصائيات</span>
    </li>
</ul>
@stop

@section('page-content')
<div class="row">
    @if(auth()->user()->can('admin.contacts.view'))
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="{{ route('contact.view') }}">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $contactTotal }}">{{ $contactTotal }}</span>
                </div>
                <div class="desc">جهات الاتصال</div>
            </div>
        </a>
    </div>
    @endif
    @if(auth()->user()->can('admin.news.view'))
    @php
    $counter = 0;
    $colors = ['blue','red','green','purple'];
    @endphp
    @foreach($categories as $row)
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 {{ $colors[$counter++] }}" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ sizeof($row->news) }}">{{ sizeof($row->news) }}</span>
                </div>
                <div class="desc"> {{ $row->name }} </div>
            </div>
        </a>
    </div>
    @if($counter == 4)
    @php
    $counter = 0;
    @endphp
    @endif
    @endforeach
    @endif
</div>
@stop