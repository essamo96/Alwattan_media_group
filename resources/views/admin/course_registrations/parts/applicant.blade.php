<div class="d-flex align-items-center">
    <div class="symbol symbol-40px me-4">
        <img src="{{ asset('assets/admin/Default.jpg') }}" alt="" class="rounded-circle">
    </div>
    <div class="d-flex flex-column">
        <span class="fw-bold">{{ $row->full_name }}</span>
        <span class="text-muted fs-7">
            {{ $row->age !== null ? $row->age . ' سنة' : '-' }}
            &middot;
            {{ $row->genderLabel() }}
        </span>
    </div>
</div>
