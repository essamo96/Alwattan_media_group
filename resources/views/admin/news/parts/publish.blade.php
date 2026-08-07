@if($publish == 0)
<a data-href="{{ Crypt::encrypt($id) }}" class="badge badge-light-danger @can('admin.news.publish') publish cursor-pointer @endcan">
    <i class="ki-duotone ki-cross fs-6 me-1"></i> غير منشور
</a>
@elseif($publish == 1)
<a data-href="{{ Crypt::encrypt($id) }}" class="badge badge-light-success @can('admin.news.publish') publish cursor-pointer @endcan">
    <i class="ki-duotone ki-check fs-6 me-1"></i> منشور
</a>
@endif
