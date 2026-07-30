<?php

namespace App\Http\Controllers\Admin;

use App\Models\PropertiesCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use Illuminate\Contracts\Encryption\DecryptException;

class PropertiesCategoriesController extends AdminController {

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
        parent::$data['active_menu'] = 'properties_categories';
    }

    //////////////////////////////////////////
    public function getIndex() {
        $properties_categories = new PropertiesCategories();
        parent::$data['properties_categories'] = $properties_categories->getAllActivePropertiesCategories();
        return view('admin.properties_categories.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $properties_categories = new PropertiesCategories();

        $title = $request->get('title');

        $info = $properties_categories->getSearchPropertiesCategories($title);

        $datatable = Datatables::of($info);

        $datatable->editColumn('title', function ($row) {
            return (!empty($row->title) ? $row->title : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.properties_categories.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.properties_categories.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        return view('admin.properties_categories.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $validator = Validator::make([
                    'ar_title' => $save_data['ar_title'],
                        ], [
                    'ar_title' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('properties_categories.add'))->withInput();
        } else {
            $language = new Language();
            $languages = $language->getAllLanguages();
            foreach ($languages as $language) {
                $langPref = $language->prefix;
                if (!empty($request->input($langPref . '_title'))) {
                    $save_data[$langPref] = ([
                        'title' => $request->input($langPref . '_title'),
                        'descs' => $request->input($langPref . '_descs'),
                    ]);
                }
            }
            $add = PropertiesCategories::create($save_data);
            if ($add) {
                $this->clearCache();
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('properties_categories.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('properties_categories.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties_categories.view'));
        }
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        /////////////////////////////
        $properties_categories = new PropertiesCategories();
        $info = $properties_categories->getPropertiesCategories($id);
        parent::$data['properties_categories'] = $properties_categories->getAllActivePropertiesCategories();
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.properties_categories.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties_categories.view'));
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
                    'ar_title' => $save_data['ar_title'],
                        ], [
                    'ar_title' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('properties_categories.edit', ['id' => $id]))->withInput();
        } else {
            $language = new Language();
            $languages = $language->getAllLanguages();
            foreach ($languages as $language) {
                $langPref = $language->prefix;
                if (!empty($request->input($langPref . '_title'))) {
                    $save_data[$langPref] = ([
                        'title' => $request->input($langPref . '_title'),
                        'descs' => $request->input($langPref . '_descs'),
                    ]);
                }
            }

            $properties_categories = PropertiesCategories::findOrFail($id);
            $save_data['view'] = array_key_exists('view', $save_data) ? 1 : 0;
            $save_data['status'] = array_key_exists('status', $save_data) ? 1 : 0;
            $update = $properties_categories->update($save_data);
            if ($update) {
                $this->clearCache();
                $request->session()->flash('success', self::UPDATE_SUCCESS);
                return redirect(route('properties_categories.view'));
            } else {

                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('properties_categories.edit', ['id' => $id]))->withInput();
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
        $properties_categories = new PropertiesCategories();
        $info = $properties_categories->getPropertiesCategories($id);
        if ($info) {
            $delete = $properties_categories->deletePropertiesCategories($info);
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
    public function postStatus(Request $request) {
        $id = $request->get('id');

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $properties_categories = new PropertiesCategories();
        $info = $properties_categories->getPropertiesCategories($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $properties_categories->updateStatus($id, 1);
                if ($update) {
                    $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $properties_categories->updateStatus($id, 0);
                if ($update) {
                    $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////////
    public function clearCache() {
        Cache::forget('properties_categories');
        $properties_categories = new PropertiesCategories();
        $info = $properties_categories->getAllActivePropertiesCategories();
        Cache::forever('properties_categories', $info);
    }

}
