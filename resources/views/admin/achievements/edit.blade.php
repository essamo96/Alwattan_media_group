@extends('layouts.admin')

@section('title', 'تعديل إنجاز')

@section('page-title')
الانجازات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('achievements.view') }}" class="text-muted text-hover-primary">الانجازات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">تعديل إنجاز</li>
@endsection

@section('content')
@include('admin.achievements.parts.form', ['formAction' => route('achievements.edit', ['id' => rawurlencode(Crypt::encrypt($info->id))]), 'submitLabel' => 'حفظ التعديلات', 'info' => $info])
@endsection

@push('scripts')
@include('admin.achievements.parts.form-scripts')
@endpush
