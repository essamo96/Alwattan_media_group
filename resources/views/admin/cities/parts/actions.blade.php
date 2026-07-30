@if(auth()->user()->can('admin.cities.edit') || auth()->user()->can('admin.cities.delete'))
    <div class="btn-group">
        <button class="btn btn-sm {{ $btn_class }} dropdown-toggle" type="button"
                data-toggle="dropdown" aria-expanded="false"> Tools
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu pull-left" role="menu">
            @can('admin.cities.edit')
                <li>
                    <a href="{{ route('cities.edit',[ 'id' => Crypt::encrypt($id)]) }}">
                        <i class="fa fa-pencil"></i> Edit </a>
                </li>
            @endcan
            @can('admin.cities.delete')
                <li>
                    <a href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
                        <i class="fa fa-trash-o"></i> Delete </a>
                </li>
            @endcan
        </ul>
    </div>
@endif