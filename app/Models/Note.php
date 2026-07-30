<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model {

    use SoftDeletes;

    //////////////////////////////////////////////
    protected $table = 'notes';

    protected $fillable = [
        'note', 'contact_id'
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
    public function deleteNote($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getNote($id) {
        return $this->where('contact_id',$id)->whereNull('deleted_at')->get();
    }
    //////////////////////////////////////////////
    //////////////////////////////////////////////
    public function getdeleteNotesID($id) {
        return $this->where('contact_id',$id)->delete();
    
          
    }
    //////////////////////////////////////////////
    public function getNoteId($id) {
        return $this->where('id',$id)->whereNull('deleted_at')->get();
    }

    //////////////////////////////////////////////
    public function getAllActiveNote() {
        return $this->where('status', '=', 1)->orderBy('id', 'asc')->get();
    }
    //////////////////////////////////////////////
    public function getSearchNote($name = null) {
        return $this->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })->get();
    }

    ////////////////////////////////////
    function addNote($note, $contact_id)
    {
        $this->note = $note;
        $this->contact_id = $contact_id;
        $this->save();
        return $this;
    }
    //////////////////////////////////
    function updateNoteUs($obj,$master, $name, $email, $mobile, $another_mobile, $note_type, $add_date)
    {
        $obj->name = $name;
        $obj->email = $email;
        $obj->master = $master;
        $obj->mobile = $mobile;
        $obj->another_mobile = $another_mobile;
        $obj->note_type = $note_type;
        $obj->add_date = $add_date;

        $obj->save();
        return $obj;
    }
    //////////////////////////////////

}
