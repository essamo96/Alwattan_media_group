<div class="d-flex">
    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
        أدوات
        <i class="ki-duotone ki-down fs-5 ms-1"></i>
    </a>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
        @can('admin.contact.edit')
        <div class="menu-item px-3">
            <a href="{{ route('contact.edit', ['id' => Crypt::encrypt($id)]) }}" class="menu-link px-3">
                <i class="ki-duotone ki-pencil fs-5 me-1"><span class="path1"></span><span class="path2"></span></i> تعديل
            </a>
        </div>
        @endcan
        @can('admin.contact.detailes')
        <div class="menu-item px-3">
            <a href="{{ route('contact.info', ['id' => Crypt::encrypt($id)]) }}" class="menu-link px-3">
                <i class="ki-duotone ki-information fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> تفاصيل
            </a>
        </div>
        @endcan
        @can('admin.contacts.delete')
        <div class="menu-item px-3">
            <a href="#" data-href="{{ Crypt::encrypt($id) }}" data-bs-toggle="modal" data-bs-target="#confirm" class="menu-link px-3">
                <i class="ki-duotone ki-trash fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> حذف
            </a>
        </div>
        @endcan
        @can('admin.contacts.view')
        <div class="menu-item px-3">
            <a href="{{ url('admin/contact/print/'.Crypt::encrypt($id)) }}" data-href="{{ Crypt::encrypt($id) }}" class="menu-link px-3 btnPrint">
                <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span></i> طباعة
            </a>
        </div>
        @endcan
    </div>
</div>
