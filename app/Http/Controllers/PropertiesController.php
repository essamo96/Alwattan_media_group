<?php

namespace App\Http\Controllers;

use App\Models\Properties;

class PropertiesController extends Controller {

    public function __construct() {
        parent::__construct();
        self::$data['active_menu'] = 'book_detail';
    }

    ///////////////////////////
    public function getView($slug) {
        $books = new Properties();
        parent::$data['property'] = $books->getProduct($slug);
        if (parent::$data['property']) {
            //   parent::$data['books'] = $books->getAllBooksBySeries(parent::$data['book']->series_id, 5);
            return view('frontend.properties.view', parent::$data);
        }
        return response()->view('errors.404', parent::$data, 500);
    }

}
