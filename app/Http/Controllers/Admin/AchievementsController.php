<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use DataTables;
/////////////////////////////////////
use App\Models\Achievement;
use App\Support\MediaUpload;

class AchievementsController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";

    const UPLOAD_DIR = 'uploads/achievements';

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'achievements';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.achievements.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $achievement = new Achievement();
        $title = $request->get('title');
        $info = $achievement->getSearchAchievements($title);

        $datatable = Datatables::of($info);

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;
            return view('admin.achievements.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];
            return view('admin.achievements.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    private function validationRules($id = null) {
        return [
            'title' => 'required|string|max:200',
            'short_description' => 'required|string|max:500',
            'long_description' => 'required|string',
            'tags' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'required|numeric|in:0,1',
        ];
    }

    //////////////////////////////////////////////
    public function getAdd() {
        return view('admin.achievements.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $data = $request->only(['title', 'short_description', 'long_description', 'tags', 'sort_order']);
        $data['status'] = (int) $request->get('status');
        $data['sort_order'] = (int) $request->get('sort_order', 0);

        $image_ar = $request->file('image_ar');
        $image_en = $request->file('image_en');

        $validator = Validator::make(array_merge($data, [
            'image_ar' => $image_ar,
            'image_en' => $image_en,
        ]), array_merge($this->validationRules(), [
            'image_ar' => 'required|image',
            'image_en' => 'required|image',
        ]));

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('achievements.add'))->withInput();
        }

        $destinationPath = MediaUpload::ensureDir(self::UPLOAD_DIR);

        $ar_name = 'achievement_ar_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image_ar->getClientOriginalExtension();
        $image_ar->move($destinationPath, $ar_name);

        $en_name = 'achievement_en_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image_en->getClientOriginalExtension();
        $image_en->move($destinationPath, $en_name);

        $data['image_ar'] = $ar_name;
        $data['image_en'] = $en_name;

        $achievement = new Achievement();
        $add = $achievement->addAchievement($data);
        if ($add) {
            $this->clearCache();
            $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
            return redirect(route('achievements.view'));
        }

        $request->session()->flash('danger', self::EXECUTION_ERROR);
        return redirect(route('achievements.add'))->withInput();
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('achievements.view'));
        }

        $achievement = new Achievement();
        $info = $achievement->getAchievement($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.achievements.edit', parent::$data);
        }

        $request->session()->flash('danger', self::NOT_FOUND);
        return redirect(route('achievements.view'));
    }

    //////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('achievements.view'));
        }

        $achievement = new Achievement();
        $info = $achievement->getAchievement($id);
        if (!$info) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('achievements.view'));
        }

        $data = $request->only(['title', 'short_description', 'long_description', 'tags']);
        $data['status'] = (int) $request->get('status');
        $data['sort_order'] = (int) $request->get('sort_order', 0);

        $image_ar = $request->file('image_ar');
        $image_en = $request->file('image_en');

        $validator = Validator::make(array_merge($data, [
            'image_ar' => $image_ar,
            'image_en' => $image_en,
        ]), array_merge($this->validationRules(), [
            'image_ar' => 'nullable|image',
            'image_en' => 'nullable|image',
        ]));

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('achievements.edit', ['id' => $encrypted_id]))->withInput();
        }

        $destinationPath = MediaUpload::ensureDir(self::UPLOAD_DIR);

        if ($request->hasFile('image_ar') && $image_ar->isValid()) {
            $ar_name = 'achievement_ar_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image_ar->getClientOriginalExtension();
            $image_ar->move($destinationPath, $ar_name);
            @unlink($destinationPath . DIRECTORY_SEPARATOR . $info->image_ar);
            $data['image_ar'] = $ar_name;
        } else {
            $data['image_ar'] = $info->image_ar;
        }

        if ($request->hasFile('image_en') && $image_en->isValid()) {
            $en_name = 'achievement_en_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image_en->getClientOriginalExtension();
            $image_en->move($destinationPath, $en_name);
            @unlink($destinationPath . DIRECTORY_SEPARATOR . $info->image_en);
            $data['image_en'] = $en_name;
        } else {
            $data['image_en'] = $info->image_en;
        }

        $update = $achievement->updateAchievement($info, $data);
        if ($update) {
            $this->clearCache();
            $request->session()->flash('success', self::UPDATE_SUCCESS);
            return redirect(route('achievements.view'));
        }

        $request->session()->flash('danger', self::EXECUTION_ERROR);
        return redirect(route('achievements.edit', ['id' => $encrypted_id]))->withInput();
    }

    //////////////////////////////////////////////
    public function postStatus(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $achievement = new Achievement();
        $info = $achievement->getAchievement($id);
        if (!$info) {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }

        $status = $info->status;
        if ($status == 0) {
            $update = $achievement->updateStatus($id, 1);
            if ($update) {
                $this->clearCache();
                return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
            }
        } else {
            $update = $achievement->updateStatus($id, 0);
            if ($update) {
                $this->clearCache();
                return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
            }
        }

        return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
    }

    //////////////////////////////////////////////
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $achievement = new Achievement();
        $info = $achievement->getAchievement($id);
        if ($info) {
            $delete = $achievement->deleteAchievement($info);
            if ($delete) {
                $this->clearCache();
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            }
            return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
        }

        return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
    }

    /////////////////////////////////////////
    public function clearCache() {
        Cache::forget('achievements_active');
    }

}
