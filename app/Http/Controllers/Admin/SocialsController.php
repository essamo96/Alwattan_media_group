<?php

namespace App\Http\Controllers\Admin;

use App\Models\Socials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SocialsController extends AdminController
{
    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const PASSWORD_SUCCESS = "نجاح، تم تغيير كلمة المرور بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";
    //////////////////////////////////////////////
    public function __construct()
    {
        parent::__construct();
        parent::$data['active_menu'] = 'socials';
    }
    //////////////////////////////////////////
    public function getIndex()
    {
        $social = new Socials();
        parent::$data['socials'] = $social->getAllSocial();
        return view('admin.socials.view', parent::$data);
    }
    //////////////////////////////////////////
    public function postIndex(Request $request)
    {
        $ids = $request->get('id', []);
        if (!is_array($ids) || count($ids) === 0) {
            $request->session()->flash('error', self::NOT_FOUND);
            return redirect(route('socials.view'));
        }

        $link = $request->get('link', []);
        $icon = $request->get('icon', []);
        $status = $request->get('status', []);
        $social = new Socials();

        foreach ($ids as $id) {
            $info = $social->getSocial($id);
            if ($info) {
                $social->updateSocial(
                    $info,
                    $link[$id] ?? $info->link,
                    $icon[$id] ?? $info->icon,
                    $status[$id] ?? $info->status
                );
            }
        }

        Cache::forget('social');
        Cache::forever('social', $social->getAllSocialActive());

        $request->session()->flash('success', self::UPDATE_SUCCESS);
        return redirect(route('socials.view'));
    }
}
