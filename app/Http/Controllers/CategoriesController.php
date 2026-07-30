<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Categories;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoriesController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function getIndex($category_slug) {
        $locale = LaravelLocalization::getCurrentLocale();
        $categories = new Categories();
        $category_info = $categories->getActiveCategories($category_slug);
        parent::$data['category_info'] = $category_info;
        $news = new News();
        parent::$data['category_news'] = $news->getNewsByCategory($category_info->id, 0, 6, $locale);
        return view('frontend.categories.view', parent::$data);
    }

}
