<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisements extends Model {

    use SoftDeletes;

    protected $table = 'advertisements';
    protected $fillable = ['name', 'image', 'type', 'expiry_date', 'url', 'status'];

    //////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)
                        ->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    function deleteAdvertisement($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getAdvertisement($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function addAdvertisement($name, $image_name, $type, $expiry_date, $url, $status) {
        return $this->create([
            'name' => $name,
            'image' => $image_name,
            'type' => $type,
            'expiry_date' => $expiry_date,
            'url' => $url,
            'status' => $status
        ]);
    }

    //////////////////////////////////////////////
    function updateAdvertisement($info, $name, $image_name, $type, $expiry_date, $url, $status) {
        return $info->update([
            'name' => $name,
            'image' => $image_name,
            'type' => $type,
            'expiry_date' => $expiry_date,
            'url' => $url,
            'status' => $status
        ]);
    }

    //////////////////////////////////////////////
    function getSearchAdvertisements($name) {
        return $this->where(function ($query) use ($name) {
                            if ($name != "") {
                                $query->where('name', 'LIKE', '%' . $name . '%');
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getAllActiveAdvertisements() {
        return $this->where('status', '=', 1)
                        ->where('expiry_date', '>=', date('Y-m-d'))
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    function getActiveAdvertisementsByType($type) {
        return $this->where('type', '=', $type)
                        ->where('status', '=', 1)
                        ->where('expiry_date', '>=', date('Y-m-d'))
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
}
