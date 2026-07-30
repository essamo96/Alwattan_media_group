@if($status == 0)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm red @can('admin.properties.status') status @endcan">
        <i class="fa fa-times"></i>  مخفي
    </a>
@elseif($status == 1)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm green-dark @can('admin.properties.status') status @endcan">
        <i class="fa fa-check"></i> منشور
    </a>
@endif