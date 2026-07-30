@if(auth()->user()->can('admin.properties_types.edit') || auth()->user()->can('admin.properties_types.delete'))
    <div class="btn-group">
        <button class="btn btn-sm {{ $btn_class }} dropdown-toggle" type="button"
                data-toggle="dropdown" aria-expanded="false"> Tools
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu pull-left" role="menu">
            @can('admin.properties_types.edit')
                <li>
                    <a href="{{ route('properties_types.edit',[ 'id' => Crypt::encrypt($id)]) }}">
                        <i class="fa fa-pencil"></i> Edit </a>
                </li>
            @endcan
            @can('admin.properties_types.delete')
                <li>
                    <a href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
                        <i class="fa fa-trash-o"></i> Delete </a>
                </li>
            @endcan
        </ul>
    </div>
@endif