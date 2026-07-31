@can('admin.menus.sort')
    <input type="number" class="form-control input-sm menu-sort" style="width: 80px;"
           value="{{ $sort }}" data-href="{{ Crypt::encrypt($id) }}">
@else
    {{ $sort }}
@endcan
