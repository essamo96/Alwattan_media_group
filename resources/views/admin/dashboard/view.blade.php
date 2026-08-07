@extends('layouts.admin')

@section('title', 'لوحة التحكم - الصفحة الرئيسية')

@section('page-title')
الصفحة الرئيسية
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted">
    <a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الصفحة الرئيسية</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-400 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">إحصائيات</li>
@endsection

@section('content')
<div class="row g-5 g-xl-8">
    @if(auth()->user()->can('admin.contacts.view'))
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('contact.view') }}" class="card bg-primary hoverable card-xl-stretch mb-xl-8 text-decoration-none">
            <div class="card-body">
                <i class="ki-duotone ki-message-text-2 fs-2hx text-white ms-n1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $contactTotal }}</div>
                <div class="fw-semibold text-white">جهات الاتصال</div>
            </div>
        </a>
    </div>
    @endif

    @if(auth()->user()->can('admin.news.view'))
        @php($colors = ['danger', 'success', 'warning', 'info'])
        @foreach($categories as $index => $row)
        <div class="col-xl-3 col-md-6">
            <div class="card bg-{{ $colors[$index % 4] }} hoverable card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <i class="ki-duotone ki-book-open fs-2hx text-white ms-n1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ sizeof($row->news) }}</div>
                    <div class="fw-semibold text-white">{{ $row->name }}</div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
