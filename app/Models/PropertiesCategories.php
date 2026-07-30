<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class PropertiesCategories extends Model {

    use SoftDeletes,
        Translatable;

    protected $table = 'properties_categories';
    protected $fillable = ['sort', 'slug', 'status'];
    public $translatedAttributes = ['title', 'descs'];

    //////////////////////////////////////////////
    public function Properties() {
        return $this->hasMany(Properties::class, 'category_id', 'id');
    }

    public function last_Properties() {
        return $this->hasMany(Properties::class, 'category_id', 'id')->limit(4)->orderBy('id', 'desc');
    }

    //////////////////////////////////////////////
    public function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    public function deletePropertiesCategories($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getPropertiesCategories($id) {
        return $this->find($id);
    }

    //////////////////////////////////
    function getPropertiesCategoriesBySlug($slug) {
        return $this->where('slug', '=', $slug)->first();
    }

    //////////////////////////////////////////////
    public function getAllActivePropertiesCategories() {
        return $this->where('status', '=', 1)->orderBy('sort', 'asc')->get();
    }

    public function getAllPropertiesCategories() {
        return $this->where('status', '=', 1)->orderBy('sort', 'asc')->get();
    }

    //////////////////////////////////////////////
    public function getSearchPropertiesCategories($title = null) {
        return $this->where(function ($query) use ($title) {
                    if ($title != '') {
                        $query->where('title', 'LIKE', '%' . $title . '%');
                    }
                })->get();
    }

    //////////////////////////////////////////////
}
