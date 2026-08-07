@extends('layouts.admin')

@section('title', 'إدارة الشبكات الإجتماعية')

@section('page-title')
الشبكات الإجتماعية
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إدارة الشبكات الإجتماعية</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ki-duotone ki-share fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
            إدارة الشبكات الإجتماعية
        </div>
    </div>
    <div class="card-body">
        @include('admin.layout.error')
        <form role="form" method="post" action="" class="">
            @csrf
            @foreach($socials as $row)
            <div class="row mb-5 align-items-center">
                <div class="col-md-3">
                    <input type="text" value="{{ $row->name }}" name="name" id="name" class="form-control" placeholder="الفيس بوك" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" value="{{ $row->link }}" name="link[{{  $row->id }}]" id="link" class="form-control" placeholder="الرابط">
                </div>
                <div class="col-md-3">
                    <input type="text" value="{{ $row->icon }}" name="icon[{{  $row->id }}]" id="icon" class="form-control" placeholder="الايقونة">
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input type="hidden" value="0" name="status[{{  $row->id }}]">
                        <input class="form-check-input" type="checkbox" value="1" name="status[{{  $row->id }}]" {{ $row->status == 1 ? "checked" : "" }}>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ $row->id }}" name="id[]">
            @endforeach

            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
