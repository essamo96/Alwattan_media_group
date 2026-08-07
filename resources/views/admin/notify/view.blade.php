@extends('layouts.admin')

@section('title', 'إدارة الاشعارات اليومية')

@section('page-title')
الاشعارات اليومية
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">عرض الاشعارات اليومية</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ki-duotone ki-message-text-2 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            جهات الاتصال المضافة خلال اليوم
        </div>
    </div>
    <div class="card-body">
        @foreach($notifications as $message)
        <div class="d-flex align-items-start mb-7">
            <div class="symbol symbol-40px me-4">
                <img src="{{ asset('assets/admin/Default.jpg') }}" alt="" class="rounded-circle">
            </div>
            <div class="flex-grow-1">
                <div class="mb-1">
                    <span class="fw-bold text-primary">{{ $message->created_by }}</span>
                    : قام بإضافة جهة الاتصال <strong>{{ $message->name }}</strong>
                    <span class="text-muted fs-8 ms-2">{{ $message->created_at->diffForHumans() }}</span>
                </div>
                <div class="bg-light-primary rounded p-3 mb-2">
                    {!! $message->notes !!}
                </div>
                <span class="badge badge-light-success">{{ $message->contact_type }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('modals')
@include('layouts.partials.confirm-modal')
@endsection
