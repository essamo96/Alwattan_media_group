<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriesTranslation extends Model {
    protected $table = 'categories_translations';
    protected $fillable = ['name'];
    public $timestamps = false;
    //////////////////////////////////////////////
    public function news() {
        return $this->hasMany('App\Models\News', 'category_id', 'id');
    }

    //////////////////////////////////////////////
    public function updateStatus($id, $status) {
        return $this
                        ->where('id', '=', $id)
                        ->update([
                            'status' => $status,
        ]);
    }

    //////////////////////////////////////////////
    public function deleteCategories($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getCategories($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    public function getAllActiveCategories() {
        return $this->where('status', '=', 1)->orderBy('sort', 'asc')->get();
    }

    public function getMenuActiveCategories() {
        $categories = $this->where('status', '=', 1)
                ->where('category_id', '=', 0)
                ->where('in_menu', '=', 1)
                ->orderBy('sort', 'asc')
                ->get();
        $back_data = array();
        foreach ($categories as $category) {
            $sub_categories = $this->where('status', '=', 1)
                    ->where('category_id', '=', $category->id)
                    ->where('in_menu', '=', 1)
                    ->orderBy('sort', 'asc')
                    ->get();
            $category->sub = $sub_categories;
            $back_data[] = $category;
        }

        return $back_data;
    }

    public function getAricleActiveCategories() {
        $categories = $this->where('status', '=', 1)
                ->where('category_id', '=', 0)
                ->where('in_menu', '=', 1)
                ->orderBy('sort', 'asc')
                ->get();
        $back_data = array();
        foreach ($categories as $category) {
            $sub_categories = $this->where('status', '=', 1)
                    ->where('category_id', '=', $category->id)
                    ->where('in_menu', '=', 1)
                    ->orderBy('sort', 'asc')
                    ->get();
            if (sizeof($sub_categories) > 0) {
                foreach ($sub_categories as $subcategory) {
                    $cate = new \stdClass();
                    $cate->name = $category->name . ' - ' . $subcategory->name;
                    $cate->id = $subcategory->id;
                    $back_data[] = $cate;
                }
            } else {
                $back_data[] = $category;
            }
        }

        return $back_data;
    }

    //////////////////////////////////////////////
    public function getActiveCategories($category_slug) {
        return $this->where('status', '=', 1)->where('slug', '=', $category_slug)->first();
    }

    //////////////////////////////////////////////
    public function getCategoriesWithNewsCount() {
        return $this->with('news')->where('status', '=', 1)->get();
    }

    //////////////////////////////////////////////
    public function getSearchCategories($name = null) {
        return $this->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })->get();
    }

}
