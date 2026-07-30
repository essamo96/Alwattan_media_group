<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Testimonials extends Model {

    use SoftDeletes,
        Translatable;

    protected $table = 'testimonials';
    protected $fillable = ['image', 'status', 'p_order'];
    public $translatedAttributes = ['name', 'descs', 'title'];

    //////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    function deletePartner($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getPartner($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function getTestimonials($start, $limit) {
        return $this
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->paginate($limit);
    }

    //////////////////////////////////////////////
    function getLastTestimonials($start, $limit) {
        return $this
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->get();
    }

//////////////////////////////////////////////
    function getTestimonialsByType($type) {
        return $this->where('type', '=', $type)
                        ->orderBy('id', 'desc')
                        ->get();
    }

    function getTestimonialsByType1($type1, $type2) {
        return $this->whereIn('type', [$type1, $type2])
                        ->orderBy('p_order', 'asc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getLastPartner() {
        return $this
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    //////////////////////////////////////////////
    function getLastPartnerByType($type) {
        return $this->where('type', '=', $type)
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    //////////////////////////////////
    function getAllTestimonials() {
        return $this->where('status', '=', 1)->get();
    }

    //////////////////////////////////////////////
    function getSearchTestimonials($title) {
        return $this->where(function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
}
