<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\News;
use App\Models\Settings;
use App\Support\MediaUpload;

//////////////////////////////////

class SettingsController extends AdminController {

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
        parent::$data['active_menu'] = 'settings';
    }

    //////////////////////////////////////////
    public function getIndex(Request $request) {
        $info = Settings::findOrFail(1);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.settings.view', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('settings.view'));
        }
    }

    ////////////////////////////////////////////////
    public function postIndex(Request $request) {
        $info = Settings::findOrFail(1);
        if ($info) {
            // النشر عبر FTP لا يشغّل migrate، فعمود default_views قد لا يكون موجوداً بعد على السيرفر؛
            // نتجاهل الحقل حينها بدل أن يفشل حفظ كل الإعدادات بخطأ SQL.
            $has_default_views = Schema::hasColumn('settings', 'default_views');
            $save_data = $request->except(['watermark_logo', 'apply_default_views', '_token']);
            if ($has_default_views) {
                $save_data['default_views'] = (int) $request->get('default_views', News::DEFAULT_VIEWS);
            } else {
                unset($save_data['default_views']);
            }
            $save_data['watermark_enabled'] = $request->has('watermark_enabled') ? 1 : 0;
            $save_data['watermark_position'] = $request->get('watermark_position', 'bottom-right');
            $save_data['watermark_opacity'] = (int) $request->get('watermark_opacity', 70);
            $save_data['watermark_size'] = (int) $request->get('watermark_size', 15);

            $title_ar = $request->get('title_ar');
            $title_en = $request->get('title_en');
            $contact_email = $request->get('contact_email');
            $default_views = $request->get('default_views');

            $validator = Validator::make([
                        'title_ar' => $title_ar,
                        'title_en' => $title_en,
                        'contact_email' => $contact_email,
                        'default_views' => $default_views,
                            ], [
                        'title_ar' => 'required',
                        'title_en' => 'required',
                        'contact_email' => 'nullable|email',
                        'default_views' => $has_default_views ? 'required|integer|min:0' : 'nullable',
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('settings.view'))->withInput();
            } else {
                $save_data['watermark_logo'] = MediaUpload::importFromRequest(
                    $request, 'watermark_logo', 'uploads/settings', 'path', $info->watermark_logo
                );

                $update = $info->update($save_data);
                if ($update) {
                    // خيار صريح: ضبط مشاهدات كل الأخبار الحالية على الرقم الافتراضي الجديد.
                    if ($has_default_views && $request->has('apply_default_views')) {
                        DB::table('news')->update(['views' => $save_data['default_views']]);
                        News::forgetListingCache();
                    }
                    Cache::forget('settings');
                    $info = Settings::findOrFail(1);
                    Cache::forever('mysettings', $info);
                    ////////////////////////////////////////////
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('settings.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('settings.view'));
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('settings.view'));
        }
    }

}
