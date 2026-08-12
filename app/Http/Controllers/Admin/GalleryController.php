<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use DataTables;
/////////////////////////////////////
use App\Models\GalleryImage;
use App\Support\MediaUpload;

class GalleryController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'gallery';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.gallery.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $gallery = new GalleryImage();
        $title = $request->get('title');
        $info = $gallery->getSearchImages($title);

        $datatable = Datatables::of($info);

        $datatable->editColumn('title', function ($row) {
            return (!empty($row->title) ? $row->title : '-');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;
            return view('admin.gallery.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];
            return view('admin.gallery.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        return view('admin.gallery.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $title = $request->get('title');
        $image = $request->file('image');
        $sort_order = (int) $request->get('sort_order', 0);
        $status = (int) $request->get('status');

        $validator = Validator::make([
            'title' => $title,
            'image' => $image,
            'sort_order' => $sort_order,
            'status' => $status,
        ], [
            'title' => 'nullable|string|max:150',
            'image' => 'required|image',
            'sort_order' => 'nullable|integer',
            'status' => 'required|numeric|in:0,1',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('gallery.add'))->withInput();
        }

        if ($request->hasFile('image') && $image->isValid()) {
            $destinationPath = MediaUpload::ensureDir('uploads/gallery');
            $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $image_name);
        } else {
            $request->session()->flash('danger', self::EXECUTION_ERROR);
            return redirect(route('gallery.add'))->withInput();
        }

        $gallery = new GalleryImage();
        $add = $gallery->addImage($title, $image_name, $sort_order, $status);
        if ($add) {
            $this->clearCache();
            $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
            return redirect(route('gallery.view'));
        }

        $request->session()->flash('danger', self::EXECUTION_ERROR);
        return redirect(route('gallery.add'))->withInput();
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('gallery.view'));
        }

        $gallery = new GalleryImage();
        $info = $gallery->getImage($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.gallery.edit', parent::$data);
        }

        $request->session()->flash('danger', self::NOT_FOUND);
        return redirect(route('gallery.view'));
    }

    //////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('gallery.view'));
        }

        $gallery = new GalleryImage();
        $info = $gallery->getImage($id);
        if (!$info) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('gallery.view'));
        }

        $db_image = $info->image;
        $title = $request->get('title');
        $image = $request->file('image');
        $sort_order = (int) $request->get('sort_order', 0);
        $status = (int) $request->get('status');

        $validator = Validator::make([
            'title' => $title,
            'image' => $image,
            'sort_order' => $sort_order,
            'status' => $status,
        ], [
            'title' => 'nullable|string|max:150',
            'image' => 'nullable|image',
            'sort_order' => 'nullable|integer',
            'status' => 'required|numeric|in:0,1',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('gallery.edit', ['id' => $encrypted_id]))->withInput();
        }

        $destinationPath = MediaUpload::ensureDir('uploads/gallery');
        if ($request->hasFile('image') && $image->isValid()) {
            $image_name = 'image_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $image_name);
            @unlink($destinationPath . DIRECTORY_SEPARATOR . $db_image);
        } else {
            $image_name = $db_image;
        }

        $update = $gallery->updateImage($info, $title, $image_name, $sort_order, $status);
        if ($update) {
            $this->clearCache();
            $request->session()->flash('success', self::UPDATE_SUCCESS);
            return redirect(route('gallery.view'));
        }

        $request->session()->flash('danger', self::EXECUTION_ERROR);
        return redirect(route('gallery.edit', ['id' => $encrypted_id]))->withInput();
    }

    //////////////////////////////////////////////
    public function postStatus(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $gallery = new GalleryImage();
        $info = $gallery->getImage($id);
        if (!$info) {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }

        $status = $info->status;
        if ($status == 0) {
            $update = $gallery->updateStatus($id, 1);
            if ($update) {
                $this->clearCache();
                return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
            }
        } else {
            $update = $gallery->updateStatus($id, 0);
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

        $gallery = new GalleryImage();
        $info = $gallery->getImage($id);
        if ($info) {
            $delete = $gallery->deleteImage($info);
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
        Cache::forget('gallery_images_active');
    }

}
