<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Support\MediaUpload;

class FaqController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "Success, Successfully Added";
    const UPDATE_SUCCESS = "Success, Successfully Update";
    const DELETE_SUCCESS = "Success, Successfully Deleted";
    const EXECUTION_ERROR = "Error, Error executing the operation";
    const NOT_FOUND = "Error, Data not found";
    const ACTIVATION_SUCCESS = "Success, Successfully Enabled";
    const DISABLE_SUCCESS = "Success, Successfully Disabled";
    const IMAGE_ERROR = "Error, Error in upload image";

    //////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'faq';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.faq.view', parent::$data);
    }

    //////////////////////////////////////////
    public function getList(Request $request) {
        $slider = new Faq();

        $length = $request->get('length');
        $start = $request->get('start');
        $name = $request->get('name');

        $info = $slider->getFaqs($name);

        $datatable = Datatables::of($info);

        $datatable->editColumn('name', function ($row) {
            return (!empty($row->name_ar) ? $row->name_ar : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.faq.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.faq.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////
    public function getAdd() {
        return view('admin.faq.add', parent::$data);
    }

    //////////////////////////////////////////
    public function postAdd(Request $request) {
        $name_ar = $request->get('name_ar');
        $name1_ar = $request->get('name1_ar');
        $name_en = $request->get('name_en');
        $name1_en = $request->get('name1_en');
        $status = (int) $request->get('status');

        $validator = Validator::make([
                    'name_ar' => $name_ar,
                    'status' => $status
                        ], [
                    'name_ar' => 'required',
                    'status' => 'nullable|numeric|in:0,1'
        ]);
        //////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('faq.add'))->withInput();
        } else {
            //////////////////////////////////////////
            $slider = new Faq();
            $add = $slider->addFaq($name_ar, $name1_ar, $name_en, $name1_en, $status);
            if ($add) {
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('faq.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('faq.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('faq.view'));
        }

        $slider = new Faq();
        $info = $slider->getFaq($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.faq.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('faq.view'));
        }
    }

    //////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('faq.view'));
        }
        //////////////////////////////////////////
        $slide = new Faq();
        $info = $slide->getFaq($id);
        if ($info) {
            $db_img = $info->image;

            $name_ar = $request->get('name_ar');
            $name1_ar = $request->get('name1_ar');
            $name_en = $request->get('name_en');
            $name1_en = $request->get('name1_en');
            $status = (int) $request->get('status');

            $validator = Validator::make([
                        'name_ar' => $name_ar,
                        'status' => $status
                            ], [
                        'name_ar' => 'required',
                        'status' => 'nullable|numeric|in:0,1'
            ]);


            //////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('faq.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                if ($request->hasFile('image') && $image->isValid()) {
                    @unlink(public_path('uploads/faq/' . $db_img));
                    $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                    $image->move(MediaUpload::ensureDir('uploads/faq'), $image_name);
                } else {
                    $image_name = $db_img;
                }
                //////////////////////////////////////////
                $update = $slide->updateFaq($info, $name_ar, $name1_ar, $name_en, $name1_en, $status);
                if ($update) {
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('faq.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('faq.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('faq.view'));
        }
    }

    //////////////////////////////////////////
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        //////////////////////////////////////////
        $faq = new Faq();
        $info = $faq->getFaq($id);
        if ($info) {
            $delete = $faq->deleteFaq($info);
            if ($delete) {
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////
    public function postStatus(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $faq = new Faq();
        $info = $faq->getFaq($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $delete = $faq->updateStatus($id, 1);
                if ($delete) {
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $delete = $faq->updateStatus($id, 0);
                if ($delete) {
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
