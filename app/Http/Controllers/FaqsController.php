<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Faq;

class FaqsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    ///////////////////////////
    public function getIndex() {
        $faq = new Faq();
        parent::$data['faqs'] = $faq->getAllActiveFaqs();
        return view('frontend.faq.view', parent::$data);
    }

}
