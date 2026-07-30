<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertiesCategoriesTranslation extends Model{
    protected $table = 'properties_categories_translations';
    protected $fillable = ['title','descs'];
    public $timestamps = false;
    //////////////////////////////////////////////
    public function updateStatus($id, $status){
        return $this->where('id', '=', $id)
                    ->update(['status' => $status,]);
    }
    //////////////////////////////////////////////
    public function deletePropertiesCategories($obj)
    {
        return $obj->delete();
    }
    //////////////////////////////////////////////
    public function getPropertiesCategories($id)
    {
        return $this->find($id);
    }
    //////////////////////////////////////////////
    public function getAllActivePropertiesCategories()
    {
        return $this->where('status', '=', 1)->orderBy('sort', 'asc')->get();
    }
    //////////////////////////////////////////////
    public function getSearchPropertiesCategories($title = null)
    {
        return $this->where(function ($query) use ($title) {
            if ($title != '') {
                $query->where('title', 'LIKE', '%'.$title.'%');
            }
        })->get();
    }
    //////////////////////////////////////////////
}
