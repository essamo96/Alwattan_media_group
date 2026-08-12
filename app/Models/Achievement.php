<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model {

    use SoftDeletes;

    protected $table = 'achievements';
    protected $fillable = [
        'title', 'short_description', 'long_description', 'tags',
        'image_ar', 'image_en', 'sort_order', 'status',
    ];

    //////////////////////////////////
    function updateStatus($id, $status) {
        return $this->where('id', '=', $id)->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    function deleteAchievement($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getAchievement($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function addAchievement($data) {
        return $this->create($data);
    }

    //////////////////////////////////////////////
    function updateAchievement($info, $data) {
        return $info->update($data);
    }

    //////////////////////////////////////////////
    function getSearchAchievements($title) {
        return $this->where(function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%')
                                      ->orWhere('tags', 'LIKE', '%' . $title . '%');
                            }
                        })
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    // متاحة للاستخدام المستقبلي بأي عرض للانجازات بالموقع الخارجي.
    function getAllActiveAchievements() {
        return $this->where('status', '=', 1)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    public function tagsArray(): array {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->tags))));
    }

}
