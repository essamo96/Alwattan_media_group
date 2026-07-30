<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertiesTypes extends Model {

    use SoftDeletes;

    protected $table = 'properties_types';
    protected $fillable = ['name_ar', 'name_en', 'status'];

    //////////////////////////////////////////////
    public function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

    public function getPropertiesTypes($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    public function getAllActivePropertiesTypes() {
        return $this->where('status', '=', 1)->get();
    }

    //////////////////////////////////////////////
    public function getSearchPropertiesTypes($title = null) {
        return $this->where(function ($query) use ($title) {
                    if ($title != '') {
                        $query->where('title', 'LIKE', '%' . $title . '%');
                    }
                })->get();
    }

    //////////////////////////////////////////////
}
