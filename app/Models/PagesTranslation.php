<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PagesTranslation extends Model {
    protected $table = 'pages_translations';
    protected $fillable = ['title', 'details'];
    public $timestamps = false;
    //////////////////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                    ->update(['status' => $status]);
    }
    //////////////////////////////////
    function getAllPages() {
        return $this->get();
    }

    //////////////////////////////////
    function getPageByName($name) {
        return $this->where('title', '=', $name)->first();
    }

    function getPageBySlug($slug) {
        return $this->where('slug', '=', $slug)->first();
    }
    //////////////////////////////////
    public function getPageTranslations($pages_id) {
        return $this->where('pages_id', '=', $pages_id)
                    ->get();
    }
    //////////////////////////////////////////////
    function getPages($page = null) {
        return $this->where(function($query) use ($page) {
                    if ($page != "") {
                        $query->where('title', 'LIKE', '%' . $page . '%');
                    }
                })->get();
    }
    //////////////////////////////////////////////
}
