<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sliders extends Model {

    use SoftDeletes;

    protected $table = 'sliders';
    protected $fillable = [
        'name', 'name1', 'language', 'image', 'link', 'txt_status', 'status',
    ];
    protected $hidden = [
        '',
    ];

    //////////////////////////////////////////////
    function addSlider($name, $name1, $name2, $language, $image, $link, $txt_status, $status) {
        $this->name = $name;
        $this->name1 = $name1;
        $this->name2 = $name2;
        $this->language = $language;
        $this->image = $image;
        $this->link = $link;
        $this->txt_status = $txt_status;
        $this->status = $status;
        $this->save();
        return $this;
    }

    //////////////////////////////////////////////
    function updateSlider($obj, $name, $name1, $name2, $language, $image, $link, $txt_status, $status) {
        $obj->name = $name;
        $obj->name1 = $name1;
        $obj->name2 = $name2;
        $obj->language = $language;
        $obj->image = $image;
        $obj->link = $link;
        $obj->txt_status = $txt_status;
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
    function deleteSlider($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getSlider($id) {
        return $this->find($id);
    }

    //////////////////////////////////
    function getActiveSliders($id) {
        return $this->where('status', '=', 1)->where('id', '=', $id)->first();
    }

    //////////////////////////////////
    function getAllSliders() {
        return $this->get();
    }

    //////////////////////////////////
    function getSliderByName($name) {
        return $this->where('name_ar', '=', $name)->first();
    }

    //////////////////////////////////////////////
    function getSliders($name = null) {
        return $this->where(function ($query) use ($name) {
                    if ($name != "") {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })->get();
    }

    //////////////////////////////////
    function getAllActiveSliders($locale) {
        return $this->where('status', '=', 1)->where('language', '=', $locale)->get();
    }

    //////////////////////////////////
    function countSliders() {
        return $this->count('id');
    }

}
