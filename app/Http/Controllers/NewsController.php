<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NewsController extends Controller {

    public function __construct() {
        parent::__construct();
        self::$data['home'] = 0;
    }

    ///////////////////////////
    public function getIndex() {
        
    }

    ///////////////////////////////////////
    public function getNews($slug) {
        $locale = LaravelLocalization::getCurrentLocale();
        $news = new News();
        $info = $news->getNewBySlug($slug);
        if (!$info) {
            return response()->view('errors.404', parent::$data, 500);
        }
        parent::$data['post_news'] = $info;
        parent::$data['last_news'] = $news->getLastNews(4, $locale);

        return view('frontend.news.details', parent::$data);
    }
    ///////////////////////////////////////
    public function postNews(Request $request ) {
        $new_id = request()->get('new_id');
        $slug = request()->get('slug');
        // $locale = LaravelLocalization::getCurrentLocale();
        $news = new News();
        $info = $news->getNewBySlug($slug);
        if (!$info) {
            return response()->view('errors.404', parent::$data, 500);
        }
        parent::$data['post_news'] = $info;

        return view('frontend.news.news_detailsAjax', parent::$data);
    }

}
