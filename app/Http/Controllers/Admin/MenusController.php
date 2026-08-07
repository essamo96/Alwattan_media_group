<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Support\MediaUpload;

class MenusController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بنجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً، لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";
    const IMAGE_ERROR = "عذراً، حدث خطأ أثناء رفع الصورة";
    const HAS_CHILDREN_ERROR = "عذراً، هذه القائمة تحتوي على قوائم فرعية. احذف أو انقل القوائم الفرعية اولاً";

    //////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'menus';
    }

    //////////////////////////////////////////
    public function getIndex() {
        return view('admin.menus.view', parent::$data);
    }

    //////////////////////////////////////////
    public function getList(Request $request) {
        $menu = new Menus();
        $name = $request->get('name');
        $info = $menu->getMenus($name);
        // خريطة القوائم الرئيسية لعرض اسم الاب دون استعلام لكل صف
        $parentNames = $menu->getTopLevelMenus()->pluck('name_ar', 'id');

        $datatable = Datatables::of($info);

        $datatable->editColumn('name_ar', function ($row) use ($parentNames) {
            $name = !empty($row->name_ar) ? $row->name_ar : 'N/A';
            return $row->parent_id != 0 ? '↳ ' . $name : $name;
        });

        $datatable->addColumn('parent', function ($row) use ($parentNames) {
            return $row->parent_id != 0 ? ($parentNames[$row->parent_id] ?? 'N/A') : '—';
        });

        $datatable->editColumn('name_en', function ($row) {
            return (!empty($row->name_en) ? $row->name_en : 'N/A');
        });

        $datatable->editColumn('url', function ($row) {
            return (!empty($row->url) ? $row->url : 'N/A');
        });

        $datatable->editColumn('icon', function ($row) {
            $data['icon'] = $row->icon;
            $data['image'] = $row->image;

            return view('admin.menus.parts.icon', $data)->render();
        });

        $datatable->editColumn('sort', function ($row) {
            $data['id'] = $row->id;
            $data['sort'] = $row->sort;

            return view('admin.menus.parts.sort', $data)->render();
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.menus.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.menus.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////
    public function getAdd() {
        $menu = new Menus();
        parent::$data['next_sort'] = $menu->getNextSort(0);
        parent::$data['parent_menus'] = $menu->getTopLevelMenus();
        return view('admin.menus.add', parent::$data);
    }

    //////////////////////////////////////////
    public function postAdd(Request $request) {
        $menu = new Menus();
        $parent_id = (int) $request->get('parent_id');
        $name_ar = $request->get('name_ar');
        $name_en = $request->get('name_en');
        $url = $request->get('url');
        $icon = $request->get('icon');
        $target = $request->get('target') == '_blank' ? '_blank' : '_self';
        $sort = (int) $request->get('sort');
        $status = (int) $request->get('status');

        $validator = Validator::make([
                    'parent_id' => $parent_id,
                    'name_ar' => $name_ar,
                    'url' => $url,
                    'sort' => $sort,
                    'status' => $status,
                        ], [
                    'parent_id' => 'nullable|numeric',
                    'name_ar' => 'required',
                    'url' => 'required',
                    'sort' => 'nullable|numeric',
                    'status' => 'nullable|numeric|in:0,1',
        ]);
        //////////////////////////////////////////
        // fails() ينفذ التحقق ويثبت رسائل الاخطاء، لتبقى محفوظة عند اضافة رسالة يدوية بعده
        $failed = $validator->fails();
        // القائمة الاب يجب ان تكون قائمة رئيسية موجودة فعلاً (لا يوجد اكثر من مستويين)
        if ($parent_id != 0 && !$menu->where('id', $parent_id)->where('parent_id', 0)->exists()) {
            $validator->errors()->add('parent_id', 'قائمة الاب المختارة غير صالحة');
            $failed = true;
        }
        //////////////////////////////////////////
        if ($failed) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('menus.add'))->withInput();
        }
        //////////////////////////////////////////
        $image_name = $this->uploadImage($request);
        if ($image_name === false) {
            $request->session()->flash('danger', self::IMAGE_ERROR);
            return redirect(route('menus.add'))->withInput();
        }
        //////////////////////////////////////////
        $add = $menu->addMenu($parent_id, $name_ar, $name_en, $url, $icon, $image_name, $target, $sort, $status);
        if ($add) {
            $this->clearCache();
            $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
            return redirect(route('menus.view'));
        } else {
            $request->session()->flash('danger', self::EXECUTION_ERROR);
            return redirect(route('menus.add'))->withInput();
        }
    }

    //////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('menus.view'));
        }

        $menu = new Menus();
        $info = $menu->getMenu($id);
        if ($info) {
            parent::$data['info'] = $info;
            // القائمة نفسها لا تظهر كخيار لقائمتها الاب، ولا اي قائمة فرعية اخرى (تفادياً لاكثر من مستويين)
            parent::$data['parent_menus'] = $menu->getTopLevelMenus()->where('id', '!=', $info->id);
            parent::$data['has_children'] = $info->children()->count() > 0;
            return view('admin.menus.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('menus.view'));
        }
    }

    //////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('menus.view'));
        }
        //////////////////////////////////////////
        $menu = new Menus();
        $info = $menu->getMenu($id);
        if (!$info) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('menus.view'));
        }

        $parent_id = (int) $request->get('parent_id');
        $name_ar = $request->get('name_ar');
        $name_en = $request->get('name_en');
        $url = $request->get('url');
        $icon = $request->get('icon');
        $target = $request->get('target') == '_blank' ? '_blank' : '_self';
        $sort = (int) $request->get('sort');
        $status = (int) $request->get('status');

        $validator = Validator::make([
                    'parent_id' => $parent_id,
                    'name_ar' => $name_ar,
                    'url' => $url,
                    'sort' => $sort,
                    'status' => $status,
                        ], [
                    'parent_id' => 'nullable|numeric',
                    'name_ar' => 'required',
                    'url' => 'required',
                    'sort' => 'nullable|numeric',
                    'status' => 'nullable|numeric|in:0,1',
        ]);
        //////////////////////////////////////////
        $failed = $validator->fails();
        if ($parent_id != 0) {
            if ($parent_id == $info->id) {
                $validator->errors()->add('parent_id', 'لا يمكن ان تكون القائمة اباً لنفسها');
                $failed = true;
            } elseif (!$menu->where('id', $parent_id)->where('parent_id', 0)->exists()) {
                $validator->errors()->add('parent_id', 'قائمة الاب المختارة غير صالحة');
                $failed = true;
            } elseif ($info->children()->count() > 0) {
                // منع اكثر من مستويين: قائمة لها قوائم فرعية لا يمكن ان تصبح فرعية بدورها
                $validator->errors()->add('parent_id', self::HAS_CHILDREN_ERROR);
                $failed = true;
            }
        }
        //////////////////////////////////////////
        if ($failed) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('menus.edit', ['id' => $encrypted_id]))->withInput();
        }
        //////////////////////////////////////////
        $image_name = $this->uploadImage($request);
        if ($image_name === false) {
            $request->session()->flash('danger', self::IMAGE_ERROR);
            return redirect(route('menus.edit', ['id' => $encrypted_id]))->withInput();
        }
        if ($image_name === null) {
            $image_name = $info->image;
        } elseif (!empty($info->image)) {
            @unlink(public_path('uploads/menus/' . $info->image));
        }
        //////////////////////////////////////////
        $update = $menu->updateMenu($info, $parent_id, $name_ar, $name_en, $url, $icon, $image_name, $target, $sort, $status);
        if ($update) {
            $this->clearCache();
            $request->session()->flash('success', self::UPDATE_SUCCESS);
            return redirect(route('menus.view'));
        } else {
            $request->session()->flash('danger', self::EXECUTION_ERROR);
            return redirect(route('menus.edit', ['id' => $encrypted_id]))->withInput();
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
        $menu = new Menus();
        $info = $menu->getMenu($id);
        if ($info) {
            if ($info->children()->count() > 0) {
                return response()->json(['status' => 'error', 'message' => self::HAS_CHILDREN_ERROR]);
            }
            $delete = $menu->deleteMenu($info);
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

    //////////////////////////////////////////
    public function postStatus(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $menu = new Menus();
        $info = $menu->getMenu($id);
        if ($info) {
            $new_status = $info->status == 0 ? 1 : 0;
            $update = $menu->updateStatus($id, $new_status);
            if ($update) {
                $this->clearCache();
                return response()->json([
                            'status' => 'success',
                            'message' => $new_status == 1 ? self::ACTIVATION_SUCCESS : self::DISABLE_SUCCESS,
                            'type' => $new_status == 1 ? 'yes' : 'no',
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////
    // تعديل ترتيب القائمة مباشرة من الجدول
    public function postSort(Request $request) {
        $id = $request->get('id');
        $sort = (int) $request->get('sort');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $menu = new Menus();
        $info = $menu->getMenu($id);
        if ($info) {
            $info->sort = $sort;
            if ($info->save()) {
                $this->clearCache();
                return response()->json(['status' => 'success', 'message' => self::UPDATE_SUCCESS]);
            }
            return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
        }
        return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
    }

    //////////////////////////////////////////
    // null => لا يوجد ملف مرفوع , false => خطأ , string => اسم الملف
    private function uploadImage(Request $request) {
        if (!$request->hasFile('image')) {
            return null;
        }
        $image = $request->file('image');
        if (!$image->isValid()) {
            return false;
        }
        $destinationPath = MediaUpload::ensureDir('uploads/menus');
        $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $image_name);
        return $image_name;
    }

    //////////////////////////////////////////
    public function clearCache() {
        Cache::forget('menus');
    }

}
