@extends('layouts.admin')

@section('title', 'طباعة جهات الاتصال')

@section('page-title')
جهات الاتصال
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">طباعة جهات الاتصال</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" id="xx">
        <label class="text-gray-600">معلومات جهة الاتصال</label>
        <div class="card">
            <div class="card-header">
                <div class="card-title">إدارة جهات الاتصال</div>
            </div>
            <div class="card-body" id="print">
                @include('admin.layout.error')
        <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover align-middle" id="Contact_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الجهة</th>
                            <th>الشخص المسؤول</th>
                            <th>رقم الجوال</th>
                            <th>الرقم البديل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($info as $contact)
                        <tr>
                            <td>#</td>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->master }}</td>
                            <td>{{ $contact->mobile }}</td>
                            <td>{{ $contact->another_mobile }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
