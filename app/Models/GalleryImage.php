<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryImage extends Model {

    use SoftDeletes;

    protected $table = 'gallery_images';
    protected $fillable = ['title', 'image', 'sort_order', 'status'];

    //////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    function deleteImage($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getImage($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function addImage($title, $image_name, $sort_order, $status) {
        return $this->create([
            'title' => $title,
            'image' => $image_name,
            'sort_order' => $sort_order,
            'status' => $status,
        ]);
    }

    //////////////////////////////////////////////
    function updateImage($info, $title, $image_name, $sort_order, $status) {
        return $info->update([
            'title' => $title,
            'image' => $image_name,
            'sort_order' => $sort_order,
            'status' => $status,
        ]);
    }

    //////////////////////////////////////////////
    function getSearchImages($title) {
        return $this->where(function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                            }
                        })
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    // تُستخدم بمعرض الصور بالموقع الخارجي (frontend.general.footer عبر View Composer).
    function getAllActiveImages() {
        return $this->where('status', '=', 1)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'desc')
                        ->get();
    }

}
