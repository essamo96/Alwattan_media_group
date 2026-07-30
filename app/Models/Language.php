<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;
    protected $table = 'languages';
    protected $fillable = [
        'name', 'prefix'
    ];
    
    //////////////////////////////////
    function getAllLanguages()
    {
       return $this->get();
    }
     //////////////////////////////////////////////
    function getLanguage($id) {
        return $this->find($id);
    }
    //////////////////////////////////
}
