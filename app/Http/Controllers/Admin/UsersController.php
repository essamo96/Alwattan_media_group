<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use App\Models\Roles;
use App\Models\User;

class UsersController extends AdminController {

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
        parent::$data['active_menu'] = 'users';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        return view('admin.users.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $user = new User();

        $length = $request->get('length');
        $start = $request->get('start');
        $username = $request->get('username');
        $name = $request->get('name');
        $info = $user->getUsers($username, $name);
        $datatable = Datatables::of($info);
        $datatable->editColumn('created_by', function ($row) {
            if (!is_null($row->user)) {
                return $row->user->username;
            }
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.users.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.users.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        $roles = new Roles();
        parent::$data['roles'] = $roles->getAllRolesActive();

        return view('admin.users.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $username = $request->get('username');
        $name = $request->get('name');
        $role = $request->get('role');
        $password = $request->get('password');
        $password_confirmation = $request->get('password_confirmation');
        $status = (int) $request->get('status');

        $validator = Validator::make([
                    'username' => $username,
                    'name' => $name,
                    'role' => $role,
                    'password' => $password,
                    'password_confirmation' => $password_confirmation,
                    'status' => $status
                        ], [
                    'username' => 'required|unique:users,username',
                    'name' => 'required',
                    'role' => 'required|numeric',
                    'password' => 'required|between:6,16|confirmed',
                    'password_confirmation' => 'required|between:6,16',
                    'status' => 'required|numeric|in:0,1'
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('users.add'))->withInput();
        } else {
            $user = new User();
            $add = $user->addUser($username, $name, $role, Auth::guard('admin')->user()->id, Hash::make($password), $status);
            if ($add) {
                $roles = new Roles();
                /////////////////////////////////////
                $new_info = $roles->getRole($role);
                if ($new_info) {
                    $new_role_name = $new_info->name;
                    $add->syncRoles([$new_role_name]);
                }
                Cache::forget('spatie.permission.cache');
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('users.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('users.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
        if ($id == 1) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }

        $user = new User();
        $roles = new Roles();

        $info = $user->getUser($id);
        if ($info) {
            //$info->assignRole('مدير النظام');
            //$permissions = $info->permissions;
            //$permissions = $info->getAllPermissions();
            //$roles = $info->getRoleNames();
            //$role = Role::create(['guard_name' => 'admin', 'name' => 'test']);
            //print_r($roles);
            //exit();
            parent::$data['roles'] = $roles->getAllRolesActive();
            parent::$data['info'] = $info;
            return view('admin.users.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
    }

    //////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
        if ($id == 1) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }

        $user = new User();
        $info = $user->getUser($id);
        if ($info) {
            $db_role = $info->role;
            $username = $request->get('username');
            $name = $request->get('name');
            $role = $request->get('role');
            $status = (int) $request->get('status');

            $validator = Validator::make([
                        'username' => $username,
                        'name' => $name,
                        'role' => $role,
                        'status' => $status
                            ], [
                        'username' => 'required|unique:users,username,' . $id,
                        'name' => 'required',
                        'role' => 'required',
                        'status' => 'required|numeric|in:0,1'
            ]);

            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('users.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $update = $user->updateUser($info, $username, $name, $role, Auth::guard('admin')->user()->id, $status);
                if ($update) {
                    $roles = new Roles();
                    /////////////////////////////////////
                    $new_info = $roles->getRole($role);
                    if ($new_info) {
                        $new_role_name = $new_info->name;
                        $info->syncRoles([$new_role_name]);
                    }
                    ////////////////////////////////////
                    Cache::forget('spatie.permission.cache');
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('users.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('users.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
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
        if ($id == 1) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return response()->json(['status' => 'error', 'message' => 'Error, Data not found']);
        }

        $users = new User();
        $info = $users->getUser($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $delete = $users->updateStatus($id, 1);
                if ($delete) {
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $delete = $users->updateStatus($id, 0);
                if ($delete) {
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////////
    public function getPassword(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
        if ($id == 1) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
        /////////////////////////////
        $user = new User();
        $info = $user->getUser($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.users.password', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
    }

    //////////////////////////////////////////////
    public function postPassword(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
        if ($id == 1) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }

        $user = new User();
        $info = $user->getUser($id);
        if ($info) {
            $password = $request->get('password');
            $password_confirmation = $request->get('password_confirmation');

            $validator = Validator::make([
                        'password' => $password,
                        'password_confirmation' => $password_confirmation
                            ], [
                        'password' => 'required|between:6,16|confirmed',
                        'password_confirmation' => 'required|between:6,16'
            ]);

            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('users.password', ['id' => $encrypted_id]))->withInput();
            } else {
                $update = $user->updatePassword($id, Hash::make($password));
                if ($update) {
                    $request->session()->flash('success', self::PASSWORD_SUCCESS);
                    return redirect(route('users.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('users.password', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('users.view'));
        }
    }

    //////////////////////////////////////////////
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        if ($id == 1) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return response()->json(['status' => 'error', 'message' => 'Error, Data not found']);
        }

        $users = new User();
        $info = $users->getUser($id);
        if ($info) {
            $delete = $users->deleteUser($info);
            if ($delete) {
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

}
