@if(auth()->user()->can('admin.menus.edit') || auth()->user()->can('admin.menus.delete'))
    <div class="btn-group">
        <button class="btn btn-sm {{ $btn_class }} dropdown-toggle" type="button"
                data-toggle="dropdown" aria-expanded="false"> Tools
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu pull-left" role="menu">
            @can('admin.menus.edit')
                <li>
                    <a href="{{ route('menus.edit',[ 'id' => Crypt::encrypt($id)]) }}">
                        <i class="fa fa-pencil"></i> Edit </a>
                </li>
            @endcan
            @can('admin.menus.delete')
                <li>
                    <a href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
                        <i class="fa fa-trash-o"></i> Delete </a>
                </li>
            @endcan
        </ul>
    </div>
@endif
