@can('admin.contacts.status') 
@if($status == 0)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm red @can('admin.contacts.status') status @endcan">
        <i class="fa fa-times"></i>  غير فعال
    </a>
@elseif($status == 1)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm green-dark @can('admin.contacts.status') status @endcan">
        <i class="fa fa-check"></i> فعال
    </a>
@endif
@endcan
{{-- @can('admin.categories.status') status @endcan@can('admin.categories.status') status @endcan --}}