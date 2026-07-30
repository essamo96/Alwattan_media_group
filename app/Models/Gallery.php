<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {

    protected $table = 'images';
    protected $fillable = [
        'file_path', 'property_id'
    ];

}
