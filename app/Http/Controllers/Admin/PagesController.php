<?php

namespace App\Http\Controllers\Admin;

use Crypt;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
//////////////////////////////////
use App\Models\Pages;
use App\Models\Language;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;

class PagesController extends AdminController {

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
        parent::$data['active_menu'] = 'pages';
    }

    //////////////////////////////////////////
    public function getIndex() {
        return view('admin.pages.view', parent::$data);
    }

    ////////////////////////////////////////////////////
    public function getList(Request $request) {
        $page = new Pages();
        $title = $request->get('title');
        $info = $page->getPages($title);
        $datatable = Datatables::of($info);

        $datatable->editColumn('title', function ($row) {
            return (!empty($row->title) ? $row->title : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;
            return view('admin.pages.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.pages.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }
//////////////////////////////////////////////
    public function getAdd() {
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        return view('admin.pages.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $validator = Validator::make([
                    'ar_title' => $save_data['ar_title'],
                    'ar_details' => $save_data['ar_details'],
                    'slug' => $save_data['slug'],
                    'tags' => $save_data['tags'],
                        ], [
                    'ar_title' => 'required',
                    'ar_details' => 'required',
                    'slug' => 'required',
                    'tags' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('pages.add'))->withInput();
        } else {       
            $language = new Language();
            $languages = $language->getAllLanguages();
            foreach ($languages as $language) {
                $langPref = $language->prefix;
                if ( !empty( $request->input($langPref . '_title') ) ){
                    $save_data[$langPref] = ([
                        'title' => $request->input($langPref . '_title'),
                        'details' => $request->input($langPref . '_details'),
                    ]);
                }
            }
            $image = $request->file('image');
            if ($request->hasFile('image') && $image->isValid()) {
                $destinationPath = 'uploads/image/';
                $images = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $images);
                @unlink(@'uploads/image/' . $db_img);
            } else {
                $images = "-";
            }
            $save_data['image'] = $images;

            $add = Pages::create($save_data);
            if ($add) {
                $this->clearCache();
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('pages.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('pages.add'))->withInput();
            }
        }
    }
    ////////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        $pages = new Pages();
        $info = $pages->getPage($id);
        if ($info) {
            $language = new Language();
            parent::$data['languages'] = $language->getAllLanguages();
            parent::$data['info'] = $info;
            return view('admin.pages.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('pages.view'));
        }
    }
    ////////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        $pages = new Pages();
        $info = $pages->getPage($id);
        if ($info) {
            $save_data = $request->all();
            $validator = Validator::make([
                'ar_title' => $save_data['ar_title'],
                'ar_details' => $save_data['ar_details'],
                'slug' => $save_data['slug'],
                'tags' => $save_data['tags'],
                    ], [
                'ar_title' => 'required',
                'ar_details' => 'required',
                'slug' => 'required',
                'tags' => 'required',
            ]);
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('pages.edit', ['id' => $id]))->withInput();
            } else {        
                $language = new Language();
                $languages = $language->getAllLanguages();
                foreach ($languages as $language) {
                    $langPref = $language->prefix;
                    if ( !empty( $request->input($langPref . '_title') ) ){
                        $save_data[$langPref] = ([
                            'title' => $request->input($langPref . '_title'),
                            'details' => $request->input($langPref . '_details'),
                        ]);
                    }
                }
                $db_img = $info->image;
                $image = $request->file('image');
                if ($request->hasFile('image') && $image->isValid()) {
                    $destinationPath = 'uploads/image/';
                    $images = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $images);
                    @unlink(@'uploads/image/' . $db_img);
                } else {
                    $images = $db_img;
                }
                $save_data['image'] = $images;
                $page = Pages::findOrFail($id);
                $update = $page->update($save_data);
                if ($update) {
                    $this->clearCache();
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('pages.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('pages.edit', ['id' => $id ]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('pages.view'));
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
        $pages = new Pages();
        $info = $pages->getPage($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $pages->updateStatus($id, 1);
                if ($update) {
                    $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $pages->updateStatus($id, 0);
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
        $pages = new Pages();
        $info = $pages->getPage($id);
        if ($info) {
            $delete = $pages->deletePages($info);
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
        $page = new Pages();
        $news_category = $page->getPageBySlug('about');
        Cache::forget('about');
        Cache::forever('about', $news_category);
    }
    //////////////////////////////////////////////
}
