@if(!empty($mysettings) && $mysettings->watermark_enabled && $mysettings->watermark_logo)
<?php
    $wm_position = $mysettings->watermark_position ?: 'bottom-right';
    $wm_size = (int) ($mysettings->watermark_size ?: 15);
    $wm_opacity = ((int) ($mysettings->watermark_opacity ?: 70)) / 100;

    $wm_style = 'position:absolute;width:' . $wm_size . '%;opacity:' . $wm_opacity . ';pointer-events:none;z-index:5;margin:3%;';
    $wm_pos_map = [
        'top-left' => 'top:0;left:0;',
        'top' => 'top:0;left:50%;transform:translateX(-50%);',
        'top-right' => 'top:0;right:0;',
        'left' => 'top:50%;left:0;transform:translateY(-50%);',
        'center' => 'top:50%;left:50%;transform:translate(-50%,-50%);',
        'right' => 'top:50%;right:0;transform:translateY(-50%);',
        'bottom-left' => 'bottom:0;left:0;',
        'bottom' => 'bottom:0;left:50%;transform:translateX(-50%);',
        'bottom-right' => 'bottom:0;right:0;',
    ];
    $wm_style .= $wm_pos_map[$wm_position] ?? $wm_pos_map['bottom-right'];
?>
<img src="{{ url($mysettings->watermark_logo) }}" style="{{ $wm_style }}" alt="">
@endif
