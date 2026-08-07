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
            <div class="card-body">
                @include('admin.layout.error')
        <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>#</td>
                            <td>{{ $info->name }}</td>
                            <td>{{ $info->master }}</td>
                            <td>{{ $info->mobile }}</td>
                            <td>{{ $info->another_mobile }}</td>
                        </tr>
                    </tbody>
                </table>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
