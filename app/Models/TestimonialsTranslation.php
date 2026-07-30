<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialsTranslation extends Model {

    protected $table = 'testimonials_translations';
    protected $fillable = ['name', 'nickname', 'descs', 'title'];
    public $timestamps = false;

    //////////////////////////////////////////////
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
    function getLastPartner() {
        return $this
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    //////////////////////////////////////////////
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
