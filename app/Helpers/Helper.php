<?php

// Code within app\Helpers\Helper.php

namespace App\Helpers;

use Intervention\Image\Facades\Image;

class Helper {

    public static function get_image($img) {
        return $img;
    }

    public static function humanTiming($time) {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor($time / $unit);
            $text_tr = 'site.' . $text . (($numberOfUnits > 1) ? 's' : '');
            return $numberOfUnits . ' ' . __($text_tr);
        }
    }

    public static function get_image_thumb($image, $width, $height) {
        if (file_exists(public_path() . '/' . $image)) {
            if ($image) {
                $image_explode = explode('/', $image);
                $size = sizeof($image_explode);
                $image_name = $image_explode[$size - 1];
                $final_explode = explode($image_name, $image);
                $thumb = $final_explode[0] . 'thumbs/' . $width . '_' . $height . '_' . $image_name;
                $full = $final_explode[0] . $image_name;
                if (file_exists(public_path() . $thumb)) {
                    return $thumb;
                } else {
                    if (!file_exists(public_path() . '/' . $final_explode[0] . 'thumbs')) {
                        // public_path() . '/' . $final_explode[0] . 'thumbs';
                        //exit;
                        mkdir(public_path() . '/' . $final_explode[0] . 'thumbs/', 0755, true);
                    }
                    $image = Image::make(public_path() . '/' . $full);
                    $image->resize($width, $height)
                            ->save(public_path() . '/' . $thumb);
                    $image->destroy();
//                $img->resize($width, $height, function ($constraint) {
//                    $constraint->aspectRatio();
//                })->save(public_path() . '/' . $thumb);
                    //  $img->resize($width, NULL)->save(public_path() . '/' . $thumb);
                    return $thumb;
                }
            }
        }
        return url('assets/site/images/nobookimage.png');
    }

    public static function get_client_img($alubm_id) {
        $imge = new Images();
        $image = $imge->getCoverImage($alubm_id);
        //print_r($image);
        if ($image) {
            return url('File/Images/photo/' . $image->image);
        }
        return url('File/Images/photo/noimage.jpg');
    }

    public static function getExamImageResult($degree, $type) {
        $img = url('assets/site/images/' . $type . '.png');
        $im = imagecreatefrompng($img);
        $white = imagecolorallocate($im, 235, 138, 39);
        $x = 230;
        $y = 400;
        $angle = 0;
        $size = 50;
        $font = '/assets/site/fonts/TheSans-Bold.otf';

        $dimensions = imagettfbbox($size, $angle, $font, $degree);
        $textWidth = abs($dimensions[4] - $dimensions[0]);
        $x1 = $x - ($textWidth / 2);
        imagettftext($im, $size, $angle, $x1, $y, $white, $font, $degree);

        //  header('Cache-Control: no-cache');
        //  header('Pragma: no-cache');
        //  header("Content-type: image/png");
        $save = time();
        $save1 = public_path('File') . "/cert/" . $save . ".png";
        @imagepng($im, $save1);
        imagedestroy($im);
        // $image = ob_get_contents();
        // $data = base64_encode($image);
        //ob_end_clean();
        return $save;
    }

}
