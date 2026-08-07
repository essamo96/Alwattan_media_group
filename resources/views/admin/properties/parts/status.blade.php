@if($status == 0)
<a data-href="{{ Crypt::encrypt($id) }}" class="badge badge-light-danger @can('admin.properties.status') status cursor-pointer @endcan">
    <i class="ki-duotone ki-cross fs-6 me-1"></i> مخفي
</a>
@elseif($status == 1)
<a data-href="{{ Crypt::encrypt($id) }}" class="badge badge-light-success @can('admin.properties.status') status cursor-pointer @endcan">
    <i class="ki-duotone ki-check fs-6 me-1"></i> منشور
</a>
@endif
