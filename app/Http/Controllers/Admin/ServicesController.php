<?php

namespace App\Http\Controllers\Admin;

use Crypt;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
//////////////////////////////////
use App\Models\Services;
use App\Models\Language;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;

class ServicesController extends AdminController {

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
        parent::$data['active_menu'] = 'services';
    }

    //////////////////////////////////////////
    public function getIndex() {
        return view('admin.services.view', parent::$data);
    }

    ////////////////////////////////////////////////////
    public function getList(Request $request) {
        $page = new Services();
        $title = $request->get('title');
        $info = $page->getAdvancedSearchServices($title);
        $datatable = Datatables::of($info);

        $datatable->editColumn('title', function ($row) {
            return (!empty($row->title) ? $row->title : 'N/A');
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.services.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

//////////////////////////////////////////////
    public function getAdd() {
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        return view('admin.services.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $validator = Validator::make([
                    'ar_title' => $save_data['ar_title'],
                    'ar_details' => $save_data['ar_details'],
                    'image' => $save_data['image'],
                        ], [
                    'ar_title' => 'required',
                    'ar_details' => 'required',
                    'image' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('services.add'))->withInput();
        } else {
            $language = new Language();
            $languages = $language->getAllLanguages();
            foreach ($languages as $language) {
                $langPref = $language->prefix;
                if (!empty($request->input($langPref . '_title'))) {
                    $save_data[$langPref] = ([
                        'title' => $request->input($langPref . '_title'),
                        'details' => $request->input($langPref . '_details'),
                    ]);
                }
            }
            $add = Services::create($save_data);
            if ($add) {
                $this->clearCache();
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('services.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('services.add'))->withInput();
            }
        }
    }

    ////////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('services.view'));
        }
        $info = Services::findOrFail($id);
        if ($info) {
            $language = new Language();
            parent::$data['languages'] = $language->getAllLanguages();
            parent::$data['info'] = $info;
            return view('admin.services.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('services.view'));
        }
    }

    ////////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('services.view'));
        }
        $info = Services::FindOrFail($id);
        if ($info) {
            $save_data = $request->all();
            $validator = Validator::make([
                        'ar_title' => $save_data['ar_title'],
                        'ar_details' => $save_data['ar_details'],
                        'image' => $save_data['image'],
                            ], [
                        'ar_title' => 'required',
                        'ar_details' => 'required',
                        'image' => 'required',
            ]);
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('services.edit', ['id' => $id]))->withInput();
            } else {
                $language = new Language();
                $languages = $language->getAllLanguages();
                foreach ($languages as $language) {
                    $langPref = $language->prefix;
                    if (!empty($request->input($langPref . '_title'))) {
                        $save_data[$langPref] = ([
                            'title' => $request->input($langPref . '_title'),
                            'details' => $request->input($langPref . '_details'),
                        ]);
                    }
                }
                $update = $info->update($save_data);
                if ($update) {
                    $this->clearCache();
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('services.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('services.edit', ['id' => $id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('services.view'));
        }
    }

    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $info = Services::FindOrFail($id);
        if ($info) {
            $delete = $info->delete();
            if ($delete) {
                $this->clearCache();
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
    public function clearCache() {
        $news_category = Services::all();
        Cache::forget('services');
        Cache::forever('services', $news_category);
    }

    //////////////////////////////////////////////
}
