@extends('layouts.admin')

@section('title', 'عرض تسجيل')

@section('page-title')
عرض تسجيل
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('course_registrations.view') }}" class="text-muted text-hover-primary">تسجيلات الدورة</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->full_name }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bold">{{ $info->full_name }}</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('course_registrations.view') }}" class="btn btn-light">
                <i class="ki-duotone ki-arrow-right fs-3"><span class="path1"></span><span class="path2"></span></i> رجوع للقائمة
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-dashed align-middle fs-6 gy-4">
                <tbody>
                    <tr><th class="w-250px text-muted">الاسم رباعي</th><td class="fw-semibold">{{ $info->full_name }}</td></tr>
                    <tr><th class="text-muted">رقم الهوية</th><td class="fw-semibold">{{ $info->national_id }}</td></tr>
                    <tr><th class="text-muted">الجنس</th><td><span class="badge badge-light-info">{{ $info->genderLabel() }}</span></td></tr>
                    <tr><th class="text-muted">تاريخ الميلاد</th><td class="fw-semibold">{{ optional($info->birth_date)->format('Y-m-d') }}</td></tr>
                    <tr><th class="text-muted">مكان الميلاد</th><td class="fw-semibold">{{ $info->birth_place }}</td></tr>
                    <tr><th class="text-muted">الجنسية</th><td class="fw-semibold">{{ $info->nationality }}</td></tr>
                    <tr>
                        <th class="text-muted">نوع المسجل</th>
                        <td>
                            <span class="badge {{ $info->citizen_type === 'refugee' ? 'badge-light-danger' : 'badge-light-success' }}">{{ $info->citizenTypeLabel() }}</span>
                            @if($info->citizen_type === 'refugee')
                            <div class="fw-semibold mt-2">رقم تسجيل بطاقة الوكالة: {{ $info->unrwa_card_number }}</div>
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">الحالة الاجتماعية</th><td><span class="badge badge-light-primary">{{ $info->maritalStatusLabel() }}</span></td></tr>
                    <tr><th class="text-muted">التخصص العام</th><td class="fw-semibold">{{ $info->general_specialization }}</td></tr>
                    <tr><th class="text-muted">التخصص الدقيق</th><td class="fw-semibold">{{ $info->specific_specialization }}</td></tr>
                    <tr><th class="text-muted">الجامعة</th><td class="fw-semibold">{{ $info->university }}</td></tr>
                    <tr><th class="text-muted">سنة التخرج</th><td class="fw-semibold">{{ $info->graduation_year }}</td></tr>
                    <tr><th class="text-muted">المعدل</th><td class="fw-semibold">{{ $info->gpa }}</td></tr>
                    <tr><th class="text-muted">جهة العمل</th><td class="fw-semibold">{{ $info->employer }}</td></tr>
                    <tr>
                        <th class="text-muted">إعاقة صحية</th>
                        <td>
                            @if($info->has_disability)
                            <span class="badge badge-light-warning">نعم</span>
                            <div class="fw-semibold mt-2">{{ $info->disability_description }}</div>
                            @else
                            <span class="badge badge-light-secondary">لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">العنوان الحالي</th><td class="fw-semibold">{{ $info->current_address }}</td></tr>
                    <tr><th class="text-muted">رقم الجوال</th><td class="fw-semibold" dir="ltr">{{ $info->mobile }}</td></tr>
                    <tr><th class="text-muted">البريد الإلكتروني</th><td class="fw-semibold" dir="ltr">{{ $info->email }}</td></tr>
                    <tr><th class="text-muted">تاريخ التسجيل</th><td class="fw-semibold">{{ optional($info->created_at)->format('Y-m-d H:i') }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
