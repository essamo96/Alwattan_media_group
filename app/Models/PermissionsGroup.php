<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionsGroup extends Model
{
    protected $table = 'permissions_group';
    protected $fillable = [
        'name',
    ];
    protected $hidden = [
        '',
    ];
    //////////////////////////////////////////////
    public function permissions()
    {
        return $this->hasMany('App\Models\Permissions','group_id','id');
    }
    //////////////////////////////////////////////
    function addPermissionGroup($name)
    {
        $this->name = $name;

        $this->save();
        return $this;
    }
    //////////////////////////////////////////////
    function updatePermissionGroup($obj, $name)
    {
        $obj->name = $name;

        $obj->save();
        return $obj;
    }
    //////////////////////////////////////////////
    function deletePermissionGroup($obj)
    {
        return $obj->delete();
    }
    //////////////////////////////////////////////
    function getPermissionGroup($id)
    {
        return $this->find($id);
    }
    //////////////////////////////////////////////
    function getAllPermissionGroup()
    {
        return $this->all();
    }
    //////////////////////////////////////////////
    function getAllPermissionGroupSearch($name = null)
    {
        return $this->where(function($query) use ($name) {
            if($name != "")
            {
                $query->where('name','LIKE','%'.$name.'%');
            }
        })->get();
    }
}
