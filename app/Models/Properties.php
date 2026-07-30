<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use App\Models\PropertiesCategories;

class Properties extends Model {

    use SoftDeletes,
        Translatable;

    //////////////////////////////////////////////
    protected $table = 'properties';
    protected $fillable = ['area', 'image', 'annual_return', 'bathroom', 'price', 'bedroom', 'status', 'category_id', 'longitude', 'latitude', 'is_new', 'property_type', 'city'];
    public $translatedAttributes = ['title', 'details'];

    public function category() {
        return $this->belongsTo(PropertiesCategories::class, 'category_id', 'id');
    }

    public function gallary() {
        return $this->hasMany(Gallery::class, 'property_id', 'id');
    }

    public function mycity() {
        return $this->hasOne(Cities::class, 'id', 'city');
    }

    //////////////////////////////////
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
        $item = $this->with('category')->find($id);
        if ($item) {
            $item->views = $item->views + 1;
            $item->save();

            return $item;
        } else {
            return false;
        }
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
    function getPropertiesByCategory($category_id, $limit) {
        return $this->where('category_id', '=', $category_id)
                        ->orderBy('id', 'desc')
                        ->paginate($limit);
    }

    //////////////////////////////////////////////
    function getAllPropertiesBySeries($series_id, $limit = 12) {
        return $this->where('series_id', '=', $series_id)
                        ->orderBy('order', 'desc')
                        ->paginate($limit);
    }

    //////////////////////////////////////////////
    function getAllPropertiesBySlug($slug) {
        return $this->where('slug', '=', $slug)
                        ->orWhere('extra_slug', '=', $slug)
                        ->orderBy('order', 'asc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getBookBySlug($slug) {
        return $this->where('slug', '=', $slug)->first();
    }

    //////////////////////////////////////////////
    function getLastProduct() {
        return $this->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    //////////////////////////////////////////////
    function getAllProperties($limit = 12) {
        return $this->where('status', '=', 1)->orderBy('id', 'asc')->paginate($limit);
    }

    function getMaxPrice() {
        return $this->where('status', '=', 1)->max('price');
    }

    function getMaxBedRoom() {
        return $this->where('status', '=', 1)->max('bedroom');
    }

    //////////////////////////////////////////////
    function getSearchProperties($category_id, $city, $max_price, $min_price, $bedroom) {
        return $this->where(function ($query) use ($category_id, $max_price, $min_price, $bedroom, $city) {
                            if ($category_id != 0) {
                                $query->where('category_id', '=', $category_id);
                            }
                            if ($city != 0) {
                                $query->where('city', '=', $city);
                            }
                            if ($bedroom != 0) {
                                $query->where('bedroom', '=', $bedroom);
                            }
                            if ($max_price != 0) {
                                $query->where('price', '>=', $min_price);
                                $query->where('price', '<=', $max_price);
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(20);
    }

    function getAdminSearchProperties($title, $series_id = -1) {
        return $this->whereHas('translations', function ($query) use ($title, $series_id) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                            }
                            if ($series_id != -1) {
                                $query->where('series_id', '=', $series_id);
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->get();
    }

    function getAdvancedSearchProperties($title) {
        return $this->whereHas('translations', function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                                $query->orWhere('details', 'LIKE', '%' . $title . '%');
                            }
                        })
//        ->whereHas('category', function ($query) use ($title) {
//                            if ($title != "") {
//                                $query->where('title', 'LIKE', '%' . $title . '%');
//                                $query->orWhere('details', 'LIKE', '%' . $title . '%');
//                            }
//                        })
                        ->Orwhere('publisher', 'LIKE', '%' . $title . '%')
                        ->Orwhere('author', 'LIKE', '%' . $title . '%')
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    //////////////////////////////////////////////
    // function getpropertiescount() {
    //     $countproperties = Properties::count();
    //     return  $countproperties; 
    // }
}
