<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cities extends Model {

    use SoftDeletes;

    protected $table = 'cities';
    protected $fillable = ['name_ar', 'name_en', 'status'];

    public function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

    public function getCities($id) {
        return $this->find($id);
    }

    public function getAllActiveCities() {
        return $this->where('status', '=', 1)->get();
    }

    public function getSearchCities($title = null) {
        return $this->where(function ($query) use ($title) {
                    if ($title != '') {
                        $query->where('title', 'LIKE', '%' . $title . '%');
                    }
                })->get();
    }

    //////////////////////////////////////////////
}
