<?php

namespace App\Http\Controllers;

use App\Models\Pages;

class PageController extends Controller {

    public function __construct() {
        parent::__construct();
        self::$data['active_menu'] = '';
    }

///////////////////////////////////////
    public function getIndex() {
        
    }

///////////////////////////////////////
    public function getPage($slug) {
        parent::$data['active_menu'] = $slug;
        $page = new Pages();
        $info = $page->getPageBySlug($slug);
        if (!$info) {
            return response()->view('errors.404', parent::$data, 500);
        }
        parent::$data['page'] = $info;
        parent::$data['slug'] = $slug;

        return view('frontend.page.view', parent::$data);
    }

    public function getServicesPage($slug) {
        parent::$data['active_menu'] = $slug;
        $page = new Pages();
        $info = $page->getPageBySlug($slug);
        if (!$info) {
            return response()->view('errors.404', parent::$data, 500);
        }
        parent::$data['page'] = $info;
        parent::$data['slug'] = $slug;

        return view('frontend.page.services', parent::$data);
    }

    public function getAbout($slug) {
        parent::$data['active_menu'] = 'about';
        $page = new Pages();

        parent::$data['page'] = $page->getPageBySlug($slug);
        parent::$data['abed'] = $page->getPageBySlug('abed');
        parent::$data['slug'] = $slug;

        return view('frontend.page.about', parent::$data);
    }

//    public function switchLang($lang) {
//// so we can redir back to where the user was
//        $url = url()->previous();
//        $url_explode = explode("/", $url);
//        $url_explode[3] = $lang;
//        $redir = implode('/', $url_explode);
//
//        return redirect($redir);
//    }

///////////////////////////////////////
}
