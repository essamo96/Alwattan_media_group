<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use App\Models\News;
use App\Models\NewsMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Artisan;
use App\Support\MediaUpload;
use App\Support\Watermark;

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
            $data['watermark_applied'] = $row->watermark_applied;

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
        $type = $request->get('type', 'image');
        $image = $request->file('image');
        $video_source = $request->get('video_source', 'url');
        $video = $video_source == 'file' ? null : (string) $request->get('video', '');
        $video_file = $request->file('video_file');
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
                    'type' => $type,
                    'image' => $image,
                    'video' => $video_source == 'url' ? $video : 'x',
                    'video_source' => $video_source,
                    'video_file' => $video_file,
                        ], [
                    'category_id' => 'required',
                    'title' => 'required',
                    'slug' => 'required',
                    'sub' => 'required',
                    'descs' => 'required',
                    'language' => 'required',
                    'type' => 'required|in:image,video',
                    'image' => 'required_if:type,image|image',
                    'video' => 'required_if:type,video|nullable|string',
                    'video_file' => 'required_if:video_source,file|nullable|mimes:mp4,mov,webm,ogg,mkv,avi|max:102400',
        ]);
//////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('news.add'))->withInput();
        } else {
            if ($type == 'image') {
                $destinationPath = MediaUpload::ensureDir('uploads/news');
                $image_name = 'news_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $image_name);
                Watermark::applyToImage($destinationPath . DIRECTORY_SEPARATOR . $image_name);
                $image = 'uploads/news/' . $image_name;
                $video = null;
                $video_source = 'url';
            } else {
                $image = null;
                if ($video_source == 'file' && $video_file && $video_file->isValid()) {
                    $destinationPath = MediaUpload::ensureDir('uploads/news/video');
                    $video_name = 'news_video_' . strtotime(date("Y-m-d H:i:s")) . '.' . $video_file->getClientOriginalExtension();
                    $video_file->move($destinationPath, $video_name);
                    $video = 'uploads/news/video/' . $video_name;
                    $video_source = 'file';
                } else {
                    $video_source = 'url';
                }
            }

            $news = new News();
            $add = $news->addNews($title, $slug, $sub, $descs, $image, $category_id, $tags, $pub_date, $publish, $sidebar, $language, Auth::guard('admin')->user()->id, $type, $video);
            if ($add) {
                $add->video_source = $video_source;
                $add->watermark_applied = $this->watermarkEnabled();
                $add->save();
                $this->saveMediaRepeater($request, $add->id);
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
    private function saveMediaRepeater(Request $request, $news_id) {
        $delete_ids = (array) $request->get('media_delete', []);
        if (!empty($delete_ids)) {
            NewsMedia::where('news_id', $news_id)->whereIn('id', $delete_ids)->delete();
        }

        $types = (array) $request->get('media_type', []);
        $video_sources = (array) $request->get('media_video_source', []);
        $video_urls = (array) $request->get('media_video_url', []);
        $ids = (array) $request->get('media_id', []);
        $files = $request->file('media_image', []);
        $video_files = $request->file('media_video_file', []);

        foreach ($types as $index => $row_type) {
            $media_id = $ids[$index] ?? null;
            $sort_order = (int) $index;

            if ($row_type === 'image') {
                $file = $files[$index] ?? null;
                if ($file && $file->isValid()) {
                    $destinationPath = MediaUpload::ensureDir('uploads/news/gallery');
                    $file_name = 'news_media_' . strtotime(date("Y-m-d H:i:s")) . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $file_name);
                    Watermark::applyToImage($destinationPath . DIRECTORY_SEPARATOR . $file_name);
                    $path = 'uploads/news/gallery/' . $file_name;
                } elseif ($media_id) {
                    continue;
                } else {
                    continue;
                }

                if ($media_id) {
                    NewsMedia::where('id', $media_id)->where('news_id', $news_id)->update([
                        'type' => 'image',
                        'path' => $path,
                        'video_url' => null,
                        'video_source' => 'url',
                        'sort_order' => $sort_order,
                    ]);
                } else {
                    NewsMedia::create([
                        'news_id' => $news_id,
                        'type' => 'image',
                        'path' => $path,
                        'video_url' => null,
                        'video_source' => 'url',
                        'sort_order' => $sort_order,
                    ]);
                }
            } else {
                $video_source = $video_sources[$index] ?? 'url';
                $video_file = $video_files[$index] ?? null;

                if ($video_source === 'file' && $video_file && $video_file->isValid()) {
                    $destinationPath = MediaUpload::ensureDir('uploads/news/gallery');
                    $video_name = 'news_media_video_' . strtotime(date("Y-m-d H:i:s")) . '_' . $index . '.' . $video_file->getClientOriginalExtension();
                    $video_file->move($destinationPath, $video_name);
                    $video_url = 'uploads/news/gallery/' . $video_name;
                } else {
                    $video_url = $video_urls[$index] ?? null;
                    $video_source = 'url';
                    if (empty($video_url)) {
                        // ملف موجود مسبقاً ولم يُستبدل: أبقِ الصف كما هو
                        if ($media_id) {
                            continue;
                        }
                        continue;
                    }
                }

                if ($media_id) {
                    NewsMedia::where('id', $media_id)->where('news_id', $news_id)->update([
                        'type' => 'video',
                        'path' => null,
                        'video_url' => $video_url,
                        'video_source' => $video_source,
                        'sort_order' => $sort_order,
                    ]);
                } else {
                    NewsMedia::create([
                        'news_id' => $news_id,
                        'type' => 'video',
                        'path' => null,
                        'video_url' => $video_url,
                        'video_source' => $video_source,
                        'sort_order' => $sort_order,
                    ]);
                }
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
            $type = $request->get('type', 'image');
            $image = $request->file('image');
            $video_source = $request->get('video_source', 'url');
            $video = (string) $request->get('video', '');
            $video_file = $request->file('video_file');
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
                        'type' => $type,
                        'image' => $image,
                        'video' => $video_source == 'url' ? $video : 'x',
                        'video_source' => $video_source,
                        'video_file' => $video_file,
                            ], [
                        'category_id' => 'required',
                        'title' => 'required',
                        'slug' => 'required',
                        'sub' => 'required',
                        'language' => 'required',
                        'descs' => 'required',
                        'type' => 'required|in:image,video',
                        'image' => 'nullable|image',
                        'video' => 'required_if:type,video|nullable|string',
                        'video_file' => 'nullable|mimes:mp4,mov,webm,ogg,mkv,avi|max:102400',
            ]);
//////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('news.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $old_category_id = $info->category_id;
                $new_image_uploaded = false;
////////////////////////////////////////////
                if ($type == 'image') {
                    if ($request->hasFile('image') && $image->isValid()) {
                        $destinationPath = MediaUpload::ensureDir('uploads/news');
                        $image_name = 'news_' . strtotime(date("Y-m-d H:i:s")) . '.' . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $image_name);
                        Watermark::applyToImage($destinationPath . DIRECTORY_SEPARATOR . $image_name);
                        $image = 'uploads/news/' . $image_name;
                        $new_image_uploaded = true;
                    } else {
                        $image = $info->image;
                    }
                    $video = null;
                    $video_source = 'url';
                } else {
                    $image = $info->image;
                    if ($video_source == 'file' && $video_file && $video_file->isValid()) {
                        $destinationPath = MediaUpload::ensureDir('uploads/news/video');
                        $video_name = 'news_video_' . strtotime(date("Y-m-d H:i:s")) . '.' . $video_file->getClientOriginalExtension();
                        $video_file->move($destinationPath, $video_name);
                        $video = 'uploads/news/video/' . $video_name;
                        $video_source = 'file';
                    } elseif ($video_source == 'file') {
                        // ابقاء ملف الفيديو الحالي إن لم يُرفع ملف جديد
                        $video = $info->video;
                        $video_source = $info->video_source ?: 'file';
                    } else {
                        $video_source = 'url';
                    }
                }

                $update = $news->updateNews($info, $title, $slug, $sub, $descs, $image, $category_id, $tags, $pub_date, $publish, $language, $sidebar, $type, $video);
                if ($update) {
                    $update->video_source = $video_source;
                    if ($new_image_uploaded) {
                        $update->watermark_applied = $this->watermarkEnabled();
                    }
                    $update->save();
                    $this->saveMediaRepeater($request, $info->id);
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

////////////////////////////////////////////////
    // يشيل/يرجّع العلامة المائية عن صور الخبر (الرئيسية + المعرض) بأي وقت بعد النشر،
    // بدون الحاجة لإعادة رفع الصور من جديد (انظر Watermark::applyToNews/removeFromNews).
    public function postToggleWatermark(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $news = new News();
        $info = $news->getNew($id);
        if (!$info) {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }

        if ($info->watermark_applied) {
            Watermark::removeFromNews($info);
            return response()->json(['status' => 'success', 'message' => 'تم إزالة العلامة المائية', 'type' => 'no']);
        } else {
            Watermark::applyToNews($info);
            return response()->json(['status' => 'success', 'message' => 'تم تطبيق العلامة المائية', 'type' => 'yes']);
        }
    }

    private function watermarkEnabled(): bool {
        $settings = Watermark::settings();
        return (bool) ($settings && $settings->watermark_enabled && !empty($settings->watermark_logo));
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
        if (!$request->hasFile('upload')) {
            return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded']], 400);
        }

        try {
            $stored = \App\Support\MediaUpload::storeCkeditorUpload($request->file('upload'));
            $url = $stored['url'];
            $fileName = $stored['fileName'];

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            if ($CKEditorFuncNum !== null) {
                $msg = 'Image uploaded successfully';
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                @header('Content-type: text/html; charset=utf-8');
                echo $response;
                return;
            }

            return response()->json(['uploaded' => 1, 'url' => $url, 'fileName' => $fileName]);
        } catch (\Throwable $e) {
            return response()->json(['uploaded' => 0, 'error' => ['message' => $e->getMessage()]], 500);
        }
    }

}
