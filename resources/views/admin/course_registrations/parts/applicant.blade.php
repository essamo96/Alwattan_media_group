@php
    $isFemale = $row->gender === 'female';
    $ringColor = $isFemale ? '#e91e63' : '#2196f3';
@endphp
<div class="d-flex align-items-center gap-3">
    <div class="symbol symbol-40px" style="border: 2px solid {{ $ringColor }}; border-radius: 50%; padding: 1px;">
        <img src="{{ asset('assets/admin/Default.jpg') }}" alt="" class="rounded-circle">
    </div>
    <div class="d-flex flex-column">
        <span class="fw-bold" style="color:#213478;">{{ $row->full_name }}</span>
        <span class="fs-7" style="color:#8a92a6;">
            <span style="color:#f1a501;">{{ $row->age !== null ? $row->age . ' سنة' : '-' }}</span>
            <span style="color:#c0212f;">&middot;</span>
            <span style="color: {{ $ringColor }};">{{ $row->genderLabel() }}</span>
        </span>
    </div>
</div>
