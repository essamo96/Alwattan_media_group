<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model {

    use SoftDeletes;

    protected $table = 'faqs';
    protected $fillable = [
        'name', 'description', 'price', 'discount', 'language', 'background', 'status',
    ];
    protected $hidden = [
        '',
    ];

    //////////////////////////////////////////////
    function addFaq($name_ar, $name1_ar, $name_en, $name1_en, $status) {
        $this->name_ar = $name_ar;
        $this->name1_ar = $name1_ar;
        $this->name_en = $name_en;
        $this->name1_en = $name1_en;
        $this->status = $status;
        $this->save();
        return $this;
    }

    //////////////////////////////////////////////
    function updateFaq($obj, $name_ar, $name1_ar, $name_en, $name1_en, $status) {
        $obj->name_ar = $name_ar;
        $obj->name1_ar = $name1_ar;
        $obj->name_en = $name_en;
        $obj->name1_en = $name1_en;
        $obj->status = $status;

        $obj->save();
        return $obj;
    }

    //////////////////////////////////////////////
    function updateStatus($id, $status) {
        return $this
                        ->where('id', '=', $id)
                        ->update([
                            'status' => $status
        ]);
    }

    //////////////////////////////////////////////
    function deleteFaq($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getFaq($id) {
        return $this->find($id);
    }

    //////////////////////////////////
    function getActiveFaqs($id) {
        return $this->where('status', '=', 1)->where('id', '=', $id)->first();
    }

    //////////////////////////////////
    function getAllFaqs() {
        return $this->get();
    }

    //////////////////////////////////
    function getFaqByName($name) {
        return $this->where('name_ar', '=', $name)->first();
    }

    //////////////////////////////////////////////
    function getFaqs($name = null) {
        return $this->where(function($query) use ($name) {
                    if ($name != "") {
                        $query->where('name_ar', 'LIKE', '%' . $name . '%');
                    }
                })->get();
    }

    //////////////////////////////////
    function getAllActiveFaqs() {
        return $this->where('status', '=', 1)->get();
    }

    //////////////////////////////////
    function countFaqs() {
        return $this->count('id');
    }

}
