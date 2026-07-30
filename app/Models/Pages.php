<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Pages extends Model {

    use SoftDeletes,
        Translatable;

    protected $table = 'pages';
    protected $fillable = ['status', 'image', 'tags', 'slug'];
    public $translatedAttributes = ['title', 'details'];

    //////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

    //////////////////////////////////
    function getPage($id) {
        return $this->find($id);
    }

    //////////////////////////////////

    public function deletePages($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getAllPages() {
        return $this->get();
    }

    //////////////////////////////////
    function getPageBySlug($slug) {
        return $this->where('slug', '=', $slug)->first();
    }

    //////////////////////////////////////////////
    function getPages($page = null) {
        return $this->where(function($query) use ($page) {
                    if ($page != "") {
                        $query->where('title', 'LIKE', '%' . $page . '%');
                    }
                })->get();
    }

    function getAdvancedPages($page = null) {
        return $this->whereHas('translations', function($query) use ($page) {
                    if ($page != "") {
                        $query->where('title', 'LIKE', '%' . $page . '%');
                        $query->orWhere('details', 'LIKE', '%' . $page . '%');
                    }
                })->get();
    }

}
