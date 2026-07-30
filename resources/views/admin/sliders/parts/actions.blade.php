@if(auth()->user()->can('admin.sliders.edit') || auth()->user()->can('admin.sliders.delete'))
    <div class="btn-group">
        <button class="btn btn-sm {{ $btn_class }} dropdown-toggle" type="button"
                data-toggle="dropdown" aria-expanded="false"> الأوامر
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu pull-left" role="menu">
            @can('admin.sliders.edit')
                <li>
                    <a href="{{ route('sliders.edit',[ 'id' => Crypt::encrypt($id)]) }}">
                        <i class="fa fa-pencil"></i> تعديل </a>
                </li>
            @endcan
            @can('admin.sliders.delete')
                <li>
                    <a href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
                        <i class="fa fa-trash-o"></i> حذف </a>
                </li>
            @endcan
        </ul>
    </div>
@endif