<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {

    use Notifiable,
        SoftDeletes,
        HasRoles;

    //////////////////////////////////////////////
    protected $table = 'users';
    protected $fillable = [
        'username', 'name', 'email', 'role', 'created_by', 'password', 'status',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $guard_name = 'admin';

    //////////////////////////////////////////////
//    public function roles()
//    {
//        return $this->belongsTo('App\Models\Roles', 'role');
//    }
    //////////////////////////////////////////////
    public function user() {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    //////////////////////////////////////////////
    function addUser($username, $name, $role, $created_by, $password, $status) {
        $this->username = $username;
        $this->name = $name;
        $this->role = $role;
        $this->created_by = $created_by;
        $this->password = $password;
        $this->status = $status;

        $this->save();
        return $this;
    }

    //////////////////////////////////////////////
    function updateUser($obj, $username, $name, $role, $created_by, $status) {
        $obj->username = $username;
        $obj->name = $name;
        $obj->role = $role;
        $obj->created_by = $created_by;
        $obj->status = $status;

        $obj->save();
        return $obj;
    }

    //////////////////////////////////////////////
    function updatePassword($id, $password) {
        return $this
                        ->where('id', '=', $id)
                        ->update([
                            'password' => $password
        ]);
    }

    //////////////////////////////////////////////
    function updateStatus($id, $status) {
        return $this
                        ->where('id', '=', $id)
                        ->update([
                            'status' => $status
        ]);
    }

    //////////////////////////////////////////////
    function deleteUser($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    function getUser($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    function getUsers($username = null, $name = null) {
        return $this->where(function ($query) use ($username, $name) {
                    if ($username != "") {
                        $query->where('username', 'LIKE', '%' . $username . '%');
                    }
                    if ($name != "") {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })->get();
    }

    //////////////////////////////////////////////
    function getUsersActive() {
        return $this->whereNull('deleted_at')->get();
    }

}
