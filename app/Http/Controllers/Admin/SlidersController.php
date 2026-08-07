<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sliders;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Support\MediaUpload;

class SlidersController extends AdminController {

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
        parent::$data['active_menu'] = 'sliders';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.sliders.view', parent::$data);
    }

    //////////////////////////////////////////
    public function getList(Request $request) {
        $slider = new Sliders();

        $length = $request->get('length');
        $start = $request->get('start');
        $name = $request->get('name');

        $info = $slider->getSliders($name);

        $datatable = Datatables::of($info);

        $datatable->editColumn('name', function ($row) {
            return (!empty($row->name) ? $row->name : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.sliders.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.sliders.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////
    public function getAdd() {
        return view('admin.sliders.add', parent::$data);
    }

    //////////////////////////////////////////
    public function postAdd(Request $request) {
        $name = $request->get('name');
        $name1 = $request->get('name1');
        $name2 = $request->get('name2');
        $language = $request->get('language');
        $image = $request->file('image');
        $link = $request->get('link');
        $txt_status = (int) $request->get('txt_status');
        $status = (int) $request->get('status');

        $validator = Validator::make([
                    'name' => $name,
                    'image' => $image,
                    'link' => $link,
                    'status' => $status
                        ], [
                    'name' => 'required',
                    'image' => 'required|image',
                    'link' => 'nullable',
                    'status' => 'nullable|numeric|in:0,1'
        ]);
        //////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('sliders.add'))->withInput();
        } else {
            if ($request->hasFile('image') && $image->isValid()) {
                $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                $image->move(MediaUpload::ensureDir('uploads/sliders'), $image_name);
            } else {
                $request->session()->flash('danger', self::IMAGE_ERROR);
                return redirect(route('sliders.add'))->withInput();
            }
            //////////////////////////////////////////
            $slider = new Sliders();
            $add = $slider->addSlider($name, $name1, $name2, $language, $image_name, $link, $txt_status, $status);
            if ($add) {
                $this->clearCache($language);
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('sliders.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('sliders.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('sliders.view'));
        }

        $slider = new Sliders();
        $info = $slider->getSlider($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.sliders.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('sliders.view'));
        }
    }

    //////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('sliders.view'));
        }
        //////////////////////////////////////////
        $slide = new Sliders();
        $info = $slide->getSlider($id);
        if ($info) {
            $db_img = $info->image;

            $name = $request->get('name');
            $name1 = $request->get('name1');
            $name2 = $request->get('name2');
            $language = $request->get('language');
            $image = $request->file('image');
            $link = $request->get('link');
            $txt_status = (int) $request->get('txt_status');
            $status = (int) $request->get('status');

            $validator = Validator::make([
                        'name' => $name,
                        'image' => $image,
                        'link' => $link,
                        'status' => $status
                            ], [
                        'name' => 'required',
                        'image' => 'nullable|image',
                        'link' => 'nullable',
                        'status' => 'nullable|numeric|in:0,1'
            ]);

            //////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('sliders.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                if ($request->hasFile('image') && $image->isValid()) {
                    @unlink(public_path('uploads/sliders/' . $db_img));
                    $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                    $image->move(MediaUpload::ensureDir('uploads/sliders'), $image_name);
                } else {
                    $image_name = $db_img;
                }
                //////////////////////////////////////////
                $update = $slide->updateSlider($info, $name, $name1, $name2, $language, $image_name, $link, $txt_status, $status);
                if ($update) {
                    $this->clearCache($language);
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('sliders.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('sliders.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('sliders.view'));
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
        $sliders = new Sliders();
        $info = $sliders->getSlider($id);
        if ($info) {
            $db_ground = $info->background;
            $db_img = $info->image;
            $delete = $sliders->deleteSlider($info);
            if ($delete) {
                $this->clearCache($info->language);
                @unlink(public_path('uploads/sliders/' . $db_ground));
                @unlink(public_path('uploads/sliders/' . $db_img));
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

        $sliders = new Sliders();
        $info = $sliders->getSlider($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $delete = $sliders->updateStatus($id, 1);
                if ($delete) {
                    $this->clearCache($info->language);
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $delete = $sliders->updateStatus($id, 0);
                if ($delete) {
                    $this->clearCache($info->language);
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    public function clearCache($lang) {
        $slides = new Sliders();
///////////// Inner Category Page///////////////
        Cache::forget('slides_' . $lang);
        $slides_new = $slides->getAllActiveSliders($lang);
        Cache::forever('slides_' . $lang, $slides_new);
    }

}
