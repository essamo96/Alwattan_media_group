<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menus extends Model {

    use SoftDeletes;

    //////////////////////////////////////////////
    protected $table = 'menus';
    protected $fillable = [
        'name_ar', 'name_en', 'url', 'icon', 'image', 'target', 'type', 'sort', 'status',
    ];

    //////////////////////////////////////////////
    function addMenu($name_ar, $name_en, $url, $icon, $image, $target, $sort, $status) {
        $this->name_ar = $name_ar;
        $this->name_en = $name_en;
        $this->url = $url;
        $this->icon = $icon;
        $this->image = $image;
        $this->target = $target;
        $this->sort = $sort;
        $this->status = $status;

        $this->save();
        return $this;
    }

    //////////////////////////////////////////////
    function updateMenu($obj, $name_ar, $name_en, $url, $icon, $image, $target, $sort, $status) {
        $obj->name_ar = $name_ar;
        $obj->name_en = $name_en;
        $obj->url = $url;
        $obj->icon = $icon;
        $obj->image = $image;
        $obj->target = $target;
        $obj->sort = $sort;
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
    function deleteMenu($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getMenu($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function getMenus($name = null) {
        return $this->where(function($query) use ($name) {
                            if ($name != "") {
                                $query->where('name_ar', 'LIKE', '%' . $name . '%');
                                $query->orWhere('name_en', 'LIKE', '%' . $name . '%');
                            }
                        })
                        ->orderBy('sort', 'asc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getAllMenus() {
        return $this->orderBy('sort', 'asc')->get();
    }

    //////////////////////////////////////////////
    function getAllActiveMenus() {
        return $this->where('status', '=', 1)
                        ->orderBy('sort', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getNextSort() {
        return (int) $this->max('sort') + 1;
    }

    //////////////////////////////////////////////
    function countMenus() {
        return $this->count('id');
    }

    //////////////////////////////////////////////
    public function getNameAttribute() {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name_ar;
    }

}
