<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menus extends Model {

    use SoftDeletes;

    //////////////////////////////////////////////
    protected $table = 'menus';
    protected $fillable = [
        'parent_id', 'name_ar', 'name_en', 'url', 'icon', 'image', 'target', 'type', 'sort', 'status',
    ];

    //////////////////////////////////////////////
    // القوائم الفرعية التابعة لهذه القائمة (0 = قائمة رئيسية بلا اب)
    public function children() {
        return $this->hasMany(Menus::class, 'parent_id', 'id')->orderBy('sort', 'asc')->orderBy('id', 'asc');
    }

    //////////////////////////////////////////////
    public function parentMenu() {
        return $this->belongsTo(Menus::class, 'parent_id', 'id');
    }

    //////////////////////////////////////////////
    function addMenu($parent_id, $name_ar, $name_en, $url, $icon, $image, $target, $sort, $status) {
        $this->parent_id = $parent_id;
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
    function updateMenu($obj, $parent_id, $name_ar, $name_en, $url, $icon, $image, $target, $sort, $status) {
        $obj->parent_id = $parent_id;
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
                        ->orderBy('parent_id', 'asc')
                        ->orderBy('sort', 'asc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getAllMenus() {
        return $this->orderBy('sort', 'asc')->get();
    }

    //////////////////////////////////////////////
    // القوائم الرئيسية فقط (parent_id = 0)، تصلح كخيارات "قائمة الاب" في نموذج الاضافة/التعديل
    function getTopLevelMenus() {
        return $this->where('parent_id', 0)->orderBy('sort', 'asc')->get();
    }

    //////////////////////////////////////////////
    // القوائم الرئيسية المفعلة مع قوائمها الفرعية المفعلة، لعرض الناف بار في الموقع الخارجي
    function getAllActiveMenus() {
        return $this->where('status', '=', 1)
                        ->where('parent_id', 0)
                        ->orderBy('sort', 'asc')
                        ->orderBy('id', 'asc')
                        ->with(['children' => function ($query) {
                            $query->where('status', 1);
                        }])
                        ->get();
    }

    //////////////////////////////////////////////
    // الترتيب التالي داخل نطاق قائمة الاب نفسها (0 = القوائم الرئيسية)
    function getNextSort($parent_id = 0) {
        return (int) $this->where('parent_id', $parent_id)->max('sort') + 1;
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
