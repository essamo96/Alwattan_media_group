<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
////////////////////////////////////
use App\Models\Testimonials;
use App\Models\Language;

class TestimonialsController extends AdminController {

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
        parent::$data['active_menu'] = 'testimonials';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.testimonials.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $title = $request->get('title', NULL);

        $testimonials = new Testimonials();
        $info = $testimonials->getSearchTestimonials($title);
        $datatable = Datatables::of($info);

        $datatable->editColumn('title', function ($row) {
            return (!empty($row->name) ? $row->name : 'N/A');
        });

        $datatable->editColumn('type', function ($row) {
            return (!empty($row->partnerType->type) ? $row->partnerType->type : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.testimonials.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.testimonials.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        return view('admin.testimonials.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $validator = Validator::make([
                    'ar_name' => $save_data['ar_name'],
                    'ar_descs' => $save_data['ar_descs'],
                        ], [
                    'ar_name' => 'required',
                    'ar_descs' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('testimonials.add'))->withInput();
        } else {
            $language = new Language();
            $languages = $language->getAllLanguages();
            foreach ($languages as $language) {
                $langPref = $language->prefix;
                if (!empty($request->input($langPref . '_name'))) {
                    $save_data[$langPref] = ([
                        'name' => $request->input($langPref . '_name'),
                        'descs' => $request->input($langPref . '_descs'),
                        'title' => $request->input($langPref . '_title'),
                    ]);
                }
            }
            $add = Testimonials::create($save_data);
            if ($add) {
                $this->clearCache();
                ///////////////////////////////////////////////////////////////////
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('testimonials.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('testimonials.add'))->withInput();
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
        $testimonials = new Testimonials();
        $info = $testimonials->getPartner($id);
        if ($info) {
            $language = new Language();
            parent::$data['languages'] = $language->getAllLanguages();
            parent::$data['info'] = $info;
            return view('admin.testimonials.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('testimonials.view'));
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
        $testimonials = new Testimonials();
        $info = $testimonials->getPartner($id);
        if ($info) {
            $save_data = $request->all();
            //print_r($save_data);die;
            $validator = Validator::make([
                        'ar_name' => $save_data['ar_name'],
                        'ar_descs' => $save_data['ar_descs'],
                            ], [
                        'ar_name' => 'required',
                        'ar_descs' => 'required',
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('testimonials.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $language = new Language();
                $languages = $language->getAllLanguages();
                foreach ($languages as $language) {
                    $langPref = $language->prefix;
                    if (!empty($request->input($langPref . '_name'))) {
                        $save_data[$langPref] = ([
                            'name' => $request->input($langPref . '_name'),
                            'title' => $request->input($langPref . '_title'),
                            'descs' => $request->input($langPref . '_descs'),
                        ]);
                    }
                }
                $partner = Testimonials::findOrFail($id);
                $update = $partner->update($save_data);

                if ($update) {
                    $this->clearCache();
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('testimonials.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('testimonials.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('testimonials.view'));
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
        $testimonials = new Testimonials();
        $info = $testimonials->getPartner($id);
        if ($info) {
            $delete = $testimonials->deletePartner($info);
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
        $testimonials = new Testimonials();
        $info = $testimonials->getPartner($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $testimonials->updateStatus($id, 1);
                if ($update) {
                    $this->clearCache();
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $testimonials->updateStatus($id, 0);
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
        Cache::forget('testimonials');
        $photo = new Testimonials();
        $info = $photo->getAllTestimonials();
        Cache::forever('testimonials', $info);
    }

}
