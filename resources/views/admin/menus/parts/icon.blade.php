@if(!empty($image))
    <img src="{{ asset('uploads/menus/'.$image) }}" alt="" style="height: 28px;">
@elseif(!empty($icon))
    <i class="{{ $icon }}" style="font-size: 18px;"></i> <small>{{ $icon }}</small>
@else
    N/A
@endif
