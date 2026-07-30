<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;
use App\Models\User;
use App\Models\Contact;

class DashboardController extends AdminController {

    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'dashboard';
    }

    ///////////////////////////////
    public function getIndex() {
        $categories = new Categories();
        if (auth()->user()->can('admin.contact.viewAll')) {
            parent::$data['contactTotal'] = Contact::count();
        } else {
            parent::$data['contactTotal'] = Contact::where('created_by', Auth::guard('admin')->user()->name)->count();
        }

        parent::$data['categories'] = $categories->getCategoriesWithNewsCount();
        return view('admin.dashboard.view', parent::$data);
    }

    ///////////////////////////////
    public function getProfile() {
        $id = Auth::user()->id;
        $user = new User();
        parent::$data['info'] = $user->getUser($id);
        return view('admin.dashboard.profile', parent::$data);
    }

    ///////////////////////////////
    public function getPassword() {
        $id = Auth::user()->id;
        $user = new User();
        parent::$data['info'] = $user->getUser($id);
        return view('admin.dashboard.password', parent::$data);
    }

    ///////////////////////////////
    public function postPassword(Request $request) {
        $id = Auth::user()->id;
        $user = new User();
        $info = $user->getUser($id);
        if ($info) {
            $db_password = $info->password;
            //////////////////////////////
            $old_password = $request->get('old_password');
            $password = $request->get('password');
            $password_confirmation = $request->get('password_confirmation');
            ///////////////////////////////////////////////////////////////
            $validator = Validator::make([
                        'password' => $password,
                        'password_confirmation' => $password_confirmation
                            ], [
                        'password' => 'required|between:6,16|alpha_dash|confirmed',
                        'password_confirmation' => 'required|between:6,16'
            ]);
            /////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('dashboard.password'))->withInput();
            } else {
                if (Hash::check($old_password, $db_password)) {
                    $save = $user->updatePassword($id, Hash::make($password));

                    if ($save) {
                        Session::flash('success', 'Your Password has been changed');
                        return redirect(route('dashboard.password'));
                    } else {
                        Session::flash('danger', 'Sorry, an error occurred while processing your request.');
                        return redirect(route('dashboard.password'));
                    }
                } else {
                    Session::flash('danger', 'Incorrect Password');
                    return redirect(route('dashboard.password'));
                }
            }
        } else {
            Session::flash('danger', 'Sorry, an error occurred while processing your request.');
            return redirect(route('dashboard.password'));
        }
    }

}
