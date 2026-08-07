<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
////////////////////////////////////
use App\Models\Partners;

class PartnersController extends AdminController {

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
        parent::$data['active_menu'] = 'partners';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.partners.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $title = $request->get('title', NULL);
        $partners = new Partners();
        $info = $partners->getSearchPartners($title);
        $datatable = Datatables::of($info);

        $datatable->editColumn('title', function ($row) {
            return (!empty($row->name) ? $row->name : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.partners.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.partners.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        return view('admin.partners.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->except('image');
        $image = $request->file('image');

        $validator = Validator::make([
                    'name' => $save_data['name'] ?? null,
                    'image' => $image,
                        ], [
                    'name' => 'required',
                    'image' => 'required|image',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('partners.add'))->withInput();
        } else {
            $destinationPath = 'uploads/partners/';
            $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $image_name);
            $save_data['image'] = $destinationPath . $image_name;

            $add = Partners::create($save_data);
            if ($add) {
                $this->clearCache();
                ///////////////////////////////////////////////////////////////////
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('partners.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('partners.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('news.view'));
        }
        //////////////////////////////////////////////
        $partners = new Partners();
        $info = $partners->getPartner($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.partners.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('partners.view'));
        }
    }

    ////////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('pages.view'));
        }
        /////////////////////////////
        $info = Partners::findOrFail($id);

        if ($info) {
            $save_data = $request->except('image');
            $image = $request->file('image');

            $validator = Validator::make([
                        'name' => $save_data['name'] ?? null,
                        'image' => $image,
                            ], [
                        'name' => 'required',
                        'image' => 'nullable|image',
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('partners.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                if ($request->hasFile('image') && $image->isValid()) {
                    $destinationPath = 'uploads/partners/';
                    $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $image_name);
                    @unlink($info->image);
                    $save_data['image'] = $destinationPath . $image_name;
                }

                $update = $info->update($save_data);
                if ($update) {
                    $this->clearCache();
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('partners.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('partners.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('partners.view'));
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
        /////////////////////////////////////
        $partners = new Partners();
        $info = $partners->getPartner($id);
        if ($info) {
            $delete = $partners->deletePartner($info);
            if ($delete) {
                $this->clearCache();
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
        /////////////////////////////////////
        $partners = new Partners();
        $info = $partners->getPartner($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $partners->updateStatus($id, 1);
                if ($update) {
                    $this->clearCache();
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $partners->updateStatus($id, 0);
                if ($update) {
                    $this->clearCache();
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    /////////////////////////////////////////
    public function clearCache() {
        // Partners are no longer cached on the public homepage (see
        // HomepageController) - this just clears out any leftover "forever"
        // cache entry from before that change so it can't serve stale data.
        Cache::forget('partners');
    }

}
