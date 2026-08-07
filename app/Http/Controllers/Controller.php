<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Pages;
use App\Models\Socials;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {

    public static $data = [];

    public function __construct() {

        self::$data['minutes'] = 60 * 24;
        //////////////////////////////////////
        self::$data['active_menu'] = '';
        self::$data['mysettings'] = Cache::rememberForever('mysettings', function () {
                    return Settings::findOrFail(1);
                });
        self::$data['social'] = Cache::rememberForever('social', function () {
                    $social = new Socials();
                    return $social->getAllSocialActive();
                });
        $page = new Pages();
        self::$data['info'] = $page->getPage(2);
        self::$data['call'] = $page->getPage(4);
    }

}
