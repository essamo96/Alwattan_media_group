<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use DataTables;

class CategoriesController extends AdminController {

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
        parent::$data['active_menu'] = 'categories';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.categories.view', parent::$data);
    }

    //////////////////////////////////////////////
    //////////////////////////////////////////////
    public function getBooks() {
        return view('frontend.categories.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $user = new Categories();

        $name = $request->get('name');

        $info = $user->getSearchCategories($name);

        $datatable = Datatables::of($info);

        $datatable->editColumn('name', function ($row) {
            return (!empty($row->name) ? $row->name : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.categories.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.categories.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        $categories = new Categories();
        parent::$data['categories'] = $categories->getAllActiveCategories();
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        return view('admin.categories.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $save_data['status'] = array_key_exists('status', $save_data) ? $save_data['status'] : 0;
        $save_data['in_menu'] = array_key_exists('in_menu', $save_data) ? $save_data['in_menu'] : 0;

        $validator = Validator::make([
                    'ar_name' => $save_data['ar_name'],
                    'sort' => $save_data['sort'],
                    'tags' => $save_data['tags'],
                    'slug' => $save_data['slug'],
                    'col_no' => $save_data['col_no'],
                    'in_menu' => $save_data['in_menu'],
                        ], [
                    'ar_name' => 'required',
                    'sort' => 'required|numeric',
                    'tags' => 'required',
                    'slug' => 'required',
                    'col_no' => 'required|numeric',
                    'in_menu' => 'required|numeric|in:0,1'
        ]);
        ////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('categories.add'))->withInput();
        } else {
            $categories = new Categories();
            $language = new Language();
            $languages = $language->getAllLanguages();
            foreach ($languages as $language) {
                $langPref = $language->prefix;
                if (!empty($request->input($langPref . '_name'))) {
                    $save_data[$langPref] = ([
                        'name' => $request->input($langPref . '_name'),
                    ]);
                }
            }



            $add = Categories::create($save_data);
            if ($add) {
                $this->clearCache();
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('categories.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('categories.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('categories.view'));
        }
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        $categories = new Categories();
        $info = $categories->getCategories($id);
        parent::$data['categories'] = $categories->getAllActiveCategories();
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.categories.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('categories.view'));
        }
    }

    //////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('categories.view'));
        }
        /////////////////////////////
        $categories = new Categories();
        $info = $categories->getCategories($id);
        if ($info) {
            $save_data = $request->all();
            $save_data['status'] = array_key_exists('status', $save_data) ? $save_data['status'] : 0;
            $save_data['in_menu'] = array_key_exists('in_menu', $save_data) ? $save_data['in_menu'] : 0;
            $validator = Validator::make([
                        'ar_name' => $save_data['ar_name'],
                        'sort' => $save_data['sort'],
                        'tags' => $save_data['tags'],
                        'slug' => $save_data['slug'],
                        'col_no' => $save_data['col_no'],
                        'in_menu' => $save_data['in_menu'],
                            ], [
                        'ar_name' => 'required',
                        'sort' => 'required|numeric',
                        'tags' => 'required',
                        'slug' => 'required',
                        'col_no' => 'required|numeric',
                        'in_menu' => 'required|numeric|in:0,1'
            ]);
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('categories.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $language = new Language();
                $languages = $language->getAllLanguages();
                foreach ($languages as $language) {
                    $langPref = $language->prefix;
                    if (!empty($request->input($langPref . '_name'))) {
                        $save_data[$langPref] = ([
                            'name' => $request->input($langPref . '_name'),
                        ]);
                    }
                }

                $Category = Categories::findOrFail($id);
                $update = $Category->update($save_data);
                if ($update) {
                    $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('categories.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('categories.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('categories.view'));
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
        $categories = new Categories();
        $info = $categories->getCategories($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $categories->updateStatus($id, 1);
                if ($update) {
                    $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $categories->updateStatus($id, 0);
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
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $categories = new Categories();
        $info = $categories->getCategories($id);
        if ($info) {
            $delete = $categories->deleteCategories($info);
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

    /////////////////////////////////////////
    public function clearCache() {
        Cache::forget('categories');
        $categories = new Categories();
        $info = $categories->getAllActiveCategories();
        Cache::forever('categories', $info);
        foreach ($info as $row) {
            Cache::forget('category_info_' . $row->id);
            ////////////////////////////////////////
            $category_info = $categories->getActiveCategories($row->id);
            Cache::forever('category_info_' . $row->id, $category_info);
        }
    }

}
