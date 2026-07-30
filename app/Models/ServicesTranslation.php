<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesTranslation extends Model {

    protected $table = 'services_translations';
    protected $fillable = ['title', 'details', 'shortcut'];
    public $timestamps = false;

    //////////////////////////////////////////////
    function deleteProduct($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getProduct($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function getServices($start, $limit) {
        return $this->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->paginate($limit);
    }

    //////////////////////////////////////////////
    function getLastServices($start, $limit) {
        return $this->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->get();
    }

    //////////////////////////////////////////////
    function getLastProduct() {
        return $this->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    //////////////////////////////////////////////
    function getAllServices() {
        return $this->where('status', '=', 1)->get();
    }

    //////////////////////////////////////////////
    function getSearchServices($title) {
        return $this->where(function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    //////////////////////////////////
    public function getServicesByCategory($category_id, $start, $limit, $locale) {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('category_id', $category_id)
                        ->where('language', $locale)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->paginate($limit);
    }

}
