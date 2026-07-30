@if($status == 0)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm red @can('admin.roles.status') status @endcan">
        <i class="fa fa-times"></i> تعطيل
    </a>
@elseif($status == 1)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm green-dark @can('admin.roles.status') status @endcan">
        <i class="fa fa-check"></i>  تفعيل
    </a>
@endif