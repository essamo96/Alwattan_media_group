<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Settings;

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
            $save_data = $request->all();

            $title_ar = $request->get('title_ar');
            $title_en = $request->get('title_en');
            $contact_email = $request->get('contact_email');

            $validator = Validator::make([
                        'title_ar' => $title_ar,
                        'title_en' => $title_en,
                        'contact_email' => $contact_email,
                            ], [
                        'title_ar' => 'required',
                        'title_en' => 'required',
                        'contact_email' => 'nullable|email',
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('settings.view'))->withInput();
            } else {
                $update = $info->update($save_data);
                if ($update) {
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
