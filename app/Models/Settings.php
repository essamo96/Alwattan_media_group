<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model {

    use SoftDeletes;

    protected $table = 'settings';
    protected $fillable = ['title_ar', 'title_en', 'description_ar', 'description_en', 'tags_ar', 'tags_en', 'contact_email', 'address', 'phone1', 'phone2', 'award', 'experinace', 'idea', 'projects', 'watermark_enabled', 'watermark_logo', 'watermark_position', 'watermark_opacity', 'watermark_size'];

}
