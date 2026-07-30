@if(auth()->user()->can('admin.properties_categories.edit') || auth()->user()->can('admin.properties_categories.delete'))
    <div class="btn-group">
        <button class="btn btn-sm {{ $btn_class }} dropdown-toggle" type="button"
                data-toggle="dropdown" aria-expanded="false"> الاداوت
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu pull-left" role="menu">
            @can('admin.properties_categories.edit')
                <li>
                    <a href="{{ route('properties_categories.edit',[ 'id' => Crypt::encrypt($id)]) }}">
                        <i class="fa fa-pencil"></i> تعديل </a>
                </li>
            @endcan
            @can('admin.properties_categories.delete')
                    <li>
                        <a href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
                            <i class="fa fa-trash-o"></i> حذف </a>
                    </li>
            @endcan
        </ul>
    </div>
@endif 