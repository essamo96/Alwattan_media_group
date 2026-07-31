@if($status == 0)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm red @can('admin.menus.status') status @endcan">
        <i class="fa fa-times"></i> Disable
    </a>
@elseif($status == 1)
    <a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm green-dark @can('admin.menus.status') status @endcan">
        <i class="fa fa-check"></i>  Enable
    </a>
@endif
