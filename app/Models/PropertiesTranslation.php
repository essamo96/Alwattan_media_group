<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertiesTranslation extends Model {

    protected $table = 'properties_translations';
    protected $fillable = ['title', 'details'];
    public $timestamps = false;

//////////////////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

//////////////////////////////////////////////
    function deleteProduct($obj) {
        return $obj->delete();
    }

//////////////////////////////////////////////
    function getProduct($id) {
        return $this->find($id);
    }

//////////////////////////////////////////////
    function getProperties($start, $limit) {
        return $this->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->paginate($limit);
    }

//////////////////////////////////////////////
    function getLastProperties($start, $limit) {
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
    function getAllProperties() {
        return $this->where('status', '=', 1)->get();
    }

//////////////////////////////////////////////
    function getSearchProperties($title) {
        return $this->where(function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->get();
    }

//////////////////////////////////////////////
    function getCities($locale) {
        return $this->select('city')->where('locale', '=', $locale)
                        ->groupBy('city')
                        ->get();
    }

//////////////////////////////////
    public function getPropertiesByCategory($category_id, $start, $limit, $locale) {
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
