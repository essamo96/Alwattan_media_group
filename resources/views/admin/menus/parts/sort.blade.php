@can('admin.menus.sort')
<input type="number" class="form-control form-control-sm menu-sort" style="width: 80px;"
       value="{{ $sort }}" data-href="{{ Crypt::encrypt($id) }}">
@else
{{ $sort }}
@endcan
