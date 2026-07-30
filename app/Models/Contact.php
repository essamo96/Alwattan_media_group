<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Contact extends Model {

    use SoftDeletes, Notifiable;

    //////////////////////////////////////////////
    protected $table = 'contact';

    protected $fillable = [
        'name','contact_type','master','mobile','another_mobile','email', 'created_by' ,'add_date','remember_date','notes', 'status'
    ];
    //////////////////////////////////////////////
    public function updateStatus($id, $status) {
        return $this
                        ->where('id', '=', $id)
                        ->update([
                            'status' => $status,
        ]);
    }

    //////////////////////////////////////////////
    public function deleteContact($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getContact($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    public function getAllActiveContact() {
        return $this->where('status', '=', 1)->orderBy('id', 'asc')->get();
    }
    //////////////////////////////////////////////
    public function getSearchContactByAuthNameRemember($name, $typesC ,$byUser,$auth_name) {
        return $this
        ->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })
        ->where(function ($query) use ($byUser) {
                    if ($byUser != '') {
                        $query->where('created_by',  $byUser );
                    }
                })
        ->where(function ($query) use ($typesC) {
                    if ($typesC != '') {
                        $query->where('contact_type',  $typesC );
                    }
                })->where('created_by', $auth_name)->whereMonth('remember_date', date('m'))
                  ->whereDay('remember_date', date('d'))
                  ->whereNull('deleted_at')
                  ->get();
                }
                // ->where('remember_date', '==', Carbon::now())
    //////////////////////////////////////////////
    public function getSearchContactByAuthName($name, $typesC ,$byUser,$auth_name) {
        return $this
        ->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })
        ->where(function ($query) use ($byUser) {
                    if ($byUser != '') {
                        $query->where('created_by',  $byUser );
                    }
                })
        ->where(function ($query) use ($typesC) {
                    if ($typesC != '') {
                        $query->where('contact_type',  $typesC );
                    }
                })->where('created_by', $auth_name)
                ->get();
    }
    //////////////////////////////////////////////
    public function getSearchContact($name, $typesC ,$byUser) {
        return $this
        ->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })
        ->where(function ($query) use ($byUser) {
                    if ($byUser != '') {
                        $query->where('created_by',  $byUser );
                    }
                })
        ->where(function ($query) use ($typesC) {
                    if ($typesC != '') {
                        $query->where('contact_type',  $typesC );
                    }
                })
                ->get();
    }

    ////////////////////////////////////
    function addContact($name, $email,$master,$created_by, $mobile, $another_mobile, $contact_type, $add_date)
    {
        $this->name = $name;
        $this->email = $email;
        $this->master = $master;
        $this->mobile = $mobile;
        $this->created_by = $created_by;
        $this->another_mobile = $another_mobile;
        $this->contact_type = $contact_type;
        $this->add_date = $add_date;

        $this->save();
        return $this;
    }
    //////////////////////////////////
    function updateContactUs($obj,$master, $name, $email, $mobile, $another_mobile, $contact_type, $add_date)
    {
        $obj->name = $name;
        $obj->email = $email;
        $obj->master = $master;
        $obj->mobile = $mobile;
        $obj->another_mobile = $another_mobile;
        $obj->contact_type = $contact_type;
        $obj->add_date = $add_date;

        $obj->save();
        return $obj;
    }
    //////////////////////////////////

}
