<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partners extends Model {

    use SoftDeletes;

    protected $table = 'partner';
    protected $fillable = ['image', 'name', 'status', 'p_order'];

    //////////////////////////////////
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
    function getPartners($start, $limit) {
        return $this
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->paginate($limit);
    }

    //////////////////////////////////////////////
    function getLastPartners($start, $limit) {
        return $this
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->get();
    }

//////////////////////////////////////////////
    function getPartnersByType($type) {
        return $this->where('type', '=', $type)
                        ->orderBy('id', 'desc')
                        ->get();
    }

    function getPartnersByType1($type1, $type2) {
        return $this->whereIn('type', [$type1, $type2])
                        ->orderBy('p_order', 'asc')
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
    function getLastPartnerByType($type) {
        return $this->where('type', '=', $type)
                        ->where('status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    //////////////////////////////////
    function getAllPartners() {
        return $this->where('status', '=', 1)->orderBy('p_order', 'asc')->get();
    }

    //////////////////////////////////////////////
    function getSearchPartners($title) {
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
