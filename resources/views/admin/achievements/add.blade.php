@extends('layouts.admin')

@section('title', 'إضافة إنجاز')

@section('page-title')
الانجازات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('achievements.view') }}" class="text-muted text-hover-primary">الانجازات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">إضافة إنجاز جديد</li>
@endsection

@section('content')
@include('admin.achievements.parts.form', ['formAction' => route('achievements.add'), 'submitLabel' => 'حفظ الإنجاز', 'info' => null])
@endsection

@push('scripts')
@include('admin.achievements.parts.form-scripts')
@endpush
