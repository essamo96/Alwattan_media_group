<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
////////////////////////////////////
use App\Models\Properties;
use App\Models\PropertiesTypes;
use App\Models\Cities;
use App\Models\Gallery;
use App\Models\Language;
use App\Models\PropertiesCategories;

class PropertiesController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const PASSWORD_SUCCESS = "نجاح، تم تغيير كلمة المرور بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم النشر بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم الحجب بنجاح";

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'properties';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        $series = new PropertiesCategories();
        parent::$data['series'] = $series->getAllActivePropertiesCategories();
        return view('admin.properties.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $title = $request->get('title', NULL);
        $series_id = $request->get('series_id');

        $properties = new Properties();
        $info = $properties->getAdminSearchProperties($title, $series_id);

        $datatable = Datatables::of($info);

        $datatable->editColumn('series_id', function ($row) {
            return $row->category ? $row->category->title : '-';
        });
        $datatable->editColumn('gallery', function ($row) {
            $data['id'] = $row->id;
            $data['total'] = $row->gallary ? sizeof($row->gallary) : 0;
            return view('admin.properties.parts.images', $data)->render();
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.properties.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.properties.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        $types = new PropertiesTypes();
        parent::$data['types'] = $types->getAllActivePropertiesTypes();
        $cities = new Cities();
        parent::$data['cities'] = $cities->getAllActiveCities();
        $series = new PropertiesCategories();
        parent::$data['series'] = $series->getAllActivePropertiesCategories();
        return view('admin.properties.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->all();
        $validator = Validator::make([
                    'ar_title' => $save_data['ar_title'],
                    'property_type' => $save_data['property_type'],
                    'city' => $save_data['city'],
                    'image' => $save_data['image'],
                    'price' => $save_data['price'],
                    'category_id' => $save_data['category_id'],
                        ], [
                    'ar_title' => 'required',
                    'city' => 'required',
                    'property_type' => 'required',
                    'image' => 'required',
                    'price' => 'required',
                    'category_id' => 'required',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('properties.add'))->withInput();
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
            $save_data['status'] = array_key_exists('status', $save_data) ? 1 : 0;
            $add = Properties::create($save_data);
            if ($add) {
                $this->clearCache();
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('properties.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('properties.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties.view'));
        }
        //////////////////////////////////////////////
        $properties = new Properties();
        $info = $properties->getProduct($id);
        if ($info) {
            $series = new PropertiesCategories();
            parent::$data['series'] = $series->getAllActivePropertiesCategories();
            $types = new PropertiesTypes();
            parent::$data['types'] = $types->getAllActivePropertiesTypes();
            $cities = new Cities();
            parent::$data['cities'] = $cities->getAllActiveCities();
            $language = new Language();
            parent::$data['languages'] = $language->getAllLanguages();
            parent::$data['info'] = $info;
            return view('admin.properties.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties.view'));
        }
    }

    ////////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties.view'));
        }
        /////////////////////////////
        $properties = new Properties();
        $info = $properties->getProduct($id);
        if ($info) {
            $save_data = $request->all();
            $validator = Validator::make([
                        'ar_title' => $save_data['ar_title'],
                        'city' => $save_data['city'],
                        'property_type' => $save_data['property_type'],
                        'image' => $save_data['image'],
                        'price' => $save_data['price'],
                        'category_id' => $save_data['category_id'],
                            ], [
                        'ar_title' => 'required',
                        'city' => 'required',
                        'property_type' => 'required',
                        'image' => 'required',
                        'price' => 'required',
                        'category_id' => 'required',
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('properties.edit', ['id' => $encrypted_id]))->withInput();
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
                $save_data['is_new'] = array_key_exists('is_new', $save_data) ? 1 : 0;
                $save_data['status'] = array_key_exists('status', $save_data) ? 1 : 0;
                $product = Properties::findOrFail($id);
                $update = $product->update($save_data);

                if ($update) {
                    $this->clearCache();
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('properties.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('properties.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('properties.view'));
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
        $properties = new Properties();
        $info = $properties->getProduct($id);
        if ($info) {
            $delete = $properties->deleteProduct($info);
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
        $properties = new Properties();
        $info = $properties->getProduct($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $properties->updateStatus($id, 1);
                if ($update) {
                    $this->clearCache();
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $properties->updateStatus($id, 0);
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

    public function getGallery($id) {
        $properties = new Properties();
        $info = $properties->getProduct($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.properties.gallary', parent::$data);
        }
    }

    public function getImages($property_id) {
        $images = Gallery::where('property_id', $property_id)->get()->toArray();
        foreach ($images as $image) {
            $tableImages[] = $image['file_path'];
        }
        $storeFolder = public_path('uploads/gallery');
        $file_path = public_path('uploads/gallery/');
        $files = scandir($storeFolder);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && in_array($file, $tableImages)) {
                $obj['name'] = $file;
                $file_path = public_path('uploads/gallery/') . $file;
                $obj['size'] = filesize($file_path);
                $obj['path'] = url('uploads/gallery/' . $file);
                $data[] = $obj;
            }
        }
        //dd($data);
        return response()->json($data);
    }

    public function getImage(Request $request, $property_id) {
        $image = $request->file('file');
        $fileInfo = $image->getClientOriginalName();
        $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
        $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
        $file_name = $filename . '-' . time() . '.' . $extension;
        $image->move(public_path('uploads/gallery'), $file_name);

        $imageUpload = new Gallery;
        $imageUpload->file_path = $file_name;
        $imageUpload->property_id = $property_id;
        $imageUpload->save();
        return response()->json(['success' => $file_name]);
    }

    public function deleteImage(Request $request, $property_id) {
        $filename = $request->get('filename');
        Gallery:: where('property_id', $property_id)
                ->where('file_path', $filename)
                ->delete();
        $path = public_path('uploads/gallery/') . $filename;
        if (file_exists($path)) {
            unlink($path);
        }
        return response()->json(['success' => $filename]);
    }

    /////////////////////////////////////////
    public function clearCache() {
//        Cache::forget('properties');
//        $photo = new Properties();
//        $info = $photo->getAllProperties();
//        Cache::forever('properties', $info);
    }

}
