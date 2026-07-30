@if($publish == 0)
<a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm red @can('admin.news.publish') publish @endcan">
    <i class="fa fa-times"></i> تعطيل
</a>
@elseif($publish == 1)
<a data-href="{{ Crypt::encrypt($id) }}" class="btn btn-sm green-dark @can('admin.news.publish') publish @endcan">
    <i class="fa fa-check"></i>  تفعيل
</a>
@endif