<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use App\Models\ServicesCategories;

class Services extends Model {

    use SoftDeletes,
        Translatable;

    //////////////////////////////////////////////
    protected $table = 'services';
    protected $fillable = ['image', 'url'];
    public $translatedAttributes = ['title', 'details', 'shortcut'];

    function getAdvancedSearchServices($title = '') {
        return $this->whereHas('translations', function ($query) use ($title) {
                            if ($title != "") {
                                $query->where('title', 'LIKE', '%' . $title . '%');
                                $query->orWhere('details', 'LIKE', '%' . $title . '%');
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->get();
    }

}
