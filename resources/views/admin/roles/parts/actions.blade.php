<div class="btn-group">
    <button class="btn btn-sm {{ $btn_class }} dropdown-toggle" type="button"
            data-toggle="dropdown" aria-expanded="false"> أدوات
        <i class="fa fa-angle-down"></i>
    </button>
    <ul class="dropdown-menu pull-left" role="menu">
        @can('admin.roles.edit')
        <li>
            <a href="{{ route('roles.edit',[ 'id' => Crypt::encrypt($id)]) }}">
                <i class="fa fa-pencil"></i> تعديل </a>
        </li>
        @endcan
        @can('admin.roles.permissions')
        <li>
            <a href="{{ route('roles.permissions',[ 'id' => Crypt::encrypt($id)]) }}">
                <i class="fa fa-lock"></i> الصلاحيات </a>
        </li>
        @endcan
        @can('admin.roles.delete')
        <li class="divider"></li>
        <li>
            <a href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
                <i class="fa fa-trash-o"></i> حذف </a>
        </li>
        @endcan
    </ul>
</div>