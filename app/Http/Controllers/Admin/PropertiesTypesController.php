<?php

namespace App\Http\Controllers\Admin;

use App\Models\PropertiesTypes;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class PropertiesTypesController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const PASSWORD_SUCCESS = "نجاح، تم تغيير كلمة المرور بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'properties_types';
    }

    //////////////////////////////////////////
    public function getIndex() {
        return view('admin.properties_types.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $properties_types = new PropertiesTypes();
        $title = $request->get('title');
        $info = $properties_types->getSearchPropertiesTypes($title);
        $datatable = Datatables::of($info);
        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.properties_types.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.properties_types.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        return view('admin.properties_types.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $validator = Validator::make([
                    'name_ar' => $save_data['name_ar'],
                    'name_en' => $save_data['name_en'],
                        ], [
                    'name_ar' => 'required',
                    'name_en' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('properties_types.add'))->withInput();
        } else {
            $add = PropertiesTypes::create($save_data);
            if ($add) {
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('properties_types.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('properties_types.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties_types.view'));
        }
        $info = PropertiesTypes::findOrFail($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.properties_types.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties_types.view'));
        }
    }

    //////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        $save_data = $request->all();
        $validator = Validator::make([
                    'name_ar' => $save_data['name_ar'],
                    'name_en' => $save_data['name_en'],
                        ], [
                    'name_ar' => 'required',
                    'name_en' => 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('properties_types.edit', ['id' => Crypt::encrypt($id)]))->withInput();
        } else {
            $properties_types = PropertiesTypes::findOrFail($id);
            $save_data['status'] = array_key_exists('status', $save_data) ? 1 : 0;
            $update = $properties_types->update($save_data);
            if ($update) {
                $request->session()->flash('success', self::UPDATE_SUCCESS);
                return redirect(route('properties_types.view'));
            } else {

                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('properties_types.edit', ['id' => $id]))->withInput();
            }
        }
    }

    ////////////////////////////////////////////////
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $properties_types = PropertiesTypes::findOrFail($id);
        if ($properties_types) {
            $delete = $properties_types->delete();
            if ($delete) {
                ///////////////////////////////////////////////////////////
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////////
    public function postStatus(Request $request) {
        $id = $request->get('id');

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $properties_types = new PropertiesTypes();
        $info = $properties_types->getPropertiesTypes($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $properties_types->updateStatus($id, 1);
                if ($update) {
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $properties_types->updateStatus($id, 0);
                if ($update) {
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

}
