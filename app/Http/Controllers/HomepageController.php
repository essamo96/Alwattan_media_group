<?php

namespace App\Http\Controllers;

use App\Models\Sliders;
use App\Models\Services;
use App\Models\Partners;
use App\Models\Testimonials;
use App\Models\News;
use App\Models\Pages;
use App\Models\Advertisements;
//use App\Models\Categories;
// use App\Models\Videos;
// use App\Models\Photos;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomepageController extends Controller {

    public function __construct() {
        parent::__construct();
        self::$data['active_menu'] = 'home';
    }

///////////////////////////
    public function getIndex() {
        $locale = LaravelLocalization::getCurrentLocale();
        parent::$data['locale'] = $locale;
        parent::$data['slides'] = Cache::rememberForever('slides_' . $locale, function () use ($locale) {
                    $slides = new Sliders();
                    return $slides->getAllActiveSliders($locale);
                });
        parent::$data['services'] = Cache::rememberForever('services', function () {
                    return Services::all();
                });
        parent::$data['news'] = Cache::rememberForever('lastnews_' . $locale, function () use ($locale) {
                    $news = new News();
                    return $news->getLastNews(4, $locale);
                });
        parent::$data['testimonials'] = Cache::rememberForever('testimonials', function () {
                    $items = new Testimonials();
                    return $items->getAllTestimonials();
                });
        // Not cached: partners is a small, cheap query and must always reflect
        // admin CRUD immediately (a stale "forever" cache previously made newly
        // added partners invisible on the public site until manually cleared).
        parent::$data['partners'] = (new Partners())->getAllPartners();
        $page = new Pages();
        parent::$data['about'] = $page->getPage(1);

        $ads = new Advertisements();
        parent::$data['ads_banner'] = $ads->getActiveAdvertisementsByType(1);

        return view('frontend.home.view', parent::$data);
    }

}
