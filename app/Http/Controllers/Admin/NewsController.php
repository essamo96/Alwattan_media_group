<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Artisan;

class NewsController extends AdminController {

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
        parent::$data['active_menu'] = 'news';
    }

//////////////////////////////////////////////
    public function getIndex() {
        $categories = new Categories();
        parent::$data['categories'] = $categories->getAllActiveCategories();
        return view('admin.news.view', parent::$data);
    }

//////////////////////////////////////////////
    public function getList(Request $request) {
        $length = $request->get('length');
        $start = $request->get('start');
        $title = $request->get('title');
        $publish = $request->get('publish');
        $category = $request->get('category');

        $news = new News();
        $info = $news->searchNews($title, $publish, $category, $start, $length);
        $count = $news->searchNewsCount($title, $publish, $category);
        $datatable = Datatables::of($info);
        $datatable = Datatables::of($info)->setTotalRecords($count);
        $datatable->editColumn('title', function ($row) {
            return (!empty($row->title) ? $row->title : 'N/A');
        });
        $datatable->editColumn('category_name', function ($row) {

            return (!empty($row->category->name) ? $row->category->name : 'N/A');
        });

        $datatable->editColumn('language', function ($row) {

            return $row->language == 'ar' ? 'عربي' : 'English';
        });

        $datatable->editColumn('publish', function ($row) {
            $data['id'] = $row->id;
            $data['publish'] = $row->publish;

            return view('admin.news.parts.publish', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.news.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

//////////////////////////////////////////////
    public function getAdd() {
        $categories = new Categories();
        parent::$data['info'] = Categories::all();
        return view('admin.news.add', parent::$data);
    }

//////////////////////////////////////////////
    public function postAdd(Request $request) {
        $category_id = $request->get('category_id');
        $title = $request->get('title');
        $slug = $request->get('slug');
        $sub = $request->get('sub');
        $descs = $request->get('descs');
        $language = $request->get('language');
        $image = $request->get('image');
        $tags = $request->get('tags');
        $pub_date = $request->get('pub_date');
        $publish = (int) $request->get('publish');
        $sidebar = (int) $request->get('main');

        $validator = Validator::make([
                    'category_id' => $category_id,
                    'title' => $title,
                    'slug' => $slug,
                    'sub' => $sub,
                    'descs' => $descs,
                    'language' => $language,
                    'image' => $image,
                        ], [
                    'category_id' => 'required',
                    'title' => 'required',
                    'slug' => 'required',
                    'sub' => 'required',
                    'descs' => 'required',
                    'language' => 'required',
                    'image' => 'required',
        ]);
//////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('news.add'))->withInput();
        } else {
            $news = new News();
            $add = $news->addNews($title, $slug, $sub, $descs, $image, $category_id, $tags, $pub_date, $publish, $sidebar, $language, Auth::guard('admin')->user()->id);
            if ($add) {
                if ($publish == 1) {
                    $this->clearCache($category_id, $language);
                }
///////////////////////////////////////////////////////////////////
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('news.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('news.add'))->withInput();
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
        $news = new News();
        $categories = new Categories();
        $info = $news->getNew($id);
        if ($info) {
            parent::$data['categories'] = $categories->getAllActiveCategories();
            parent::$data['info'] = $info;
            return view('admin.news.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('news.view'));
        }
    }

//////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('news.view'));
        }
/////////////////////////////////////////
        $news = new News();
        $info = $news->getNew($id);
        if ($info) {
            $category_id = $request->get('category_id');
            $title = $request->get('title');
            $slug = $request->get('slug');
            $sub = $request->get('sub');
            $descs = $request->get('descs');
            $language = $request->get('language');
            $image = $request->get('image');
            $tags = $request->get('tags');
            $pub_date = $request->get('pub_date');
            $publish = (int) $request->get('publish');
            $sidebar = (int) $request->get('main');

            $validator = Validator::make([
                        'category_id' => $category_id,
                        'title' => $title,
                        'slug' => $slug,
                        'sub' => $sub,
                        'language' => $language,
                        'descs' => $descs,
                            ], [
                        'category_id' => 'required',
                        'title' => 'required',
                        'slug' => 'required',
                        'sub' => 'required',
                        'language' => 'required',
                        'descs' => 'required',
            ]);
//////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('news.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $old_category_id = $info->category_id;
////////////////////////////////////////////
                $update = $news->updateNews($info, $title, $slug, $sub, $descs, $image, $category_id, $tags, $pub_date, $publish, $language, $sidebar);
                if ($update) {
                    if ($info->publish == 1) {
                        if ($old_category_id != $category_id) {
                            $this->clearCache($old_category_id, $language);
                        }
                        $this->clearCache($category_id, $language);
                        //   $this->getMedium($title, $descs, $image);
                        //$this->getTwitter($update);
                    }
///////////////////////////////////////////////////////////
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('news.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('news.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('news.view'));
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
        $news = new News();
        $info = $news->getNew($id);
        if ($info) {
            $delete = $news->deleteNews($info);
            if ($delete) {
                $this->clearCache($info->category_id, $info->language);
////////////////////////////////////
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    public function postPublish(Request $request) {

        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $news = new News();
        $info = $news->getNew($id);
        if ($info) {
            $publish = $info->publish;
            if ($publish == 0) {
                $update = $news->updatePublish($id, 1);
                if ($update) {
                    $this->clearCache($info->category_id, $info->language);
////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $news->updatePublish($id, 0);
                if ($update) {

                    $this->clearCache($info->category_id, $info->language);
////////////////////////////////////
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
    public function cleaAllCache() {

        Artisan::call('cache:clear');
        return redirect(route('news.view'));
    }

    public function clearCache($category_id, $locale) {
        $news = new News();
///////////// Inner Category Page///////////////
        Cache::forget('category_news_' . $category_id);
        $news_category = $news->getNewsByCategory($category_id, 0, 5, $locale);
        Cache::forever('category_news_' . $category_id, $news_category);

        $last_news = $news->getLastNews(4, $locale);
        Cache::forget('lastnews_' . $locale);
        Cache::forever('lastnews_' . $locale, $last_news);
    }

    public function getImage(Request $request) {
// Allowed extentions.
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('images'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

}
