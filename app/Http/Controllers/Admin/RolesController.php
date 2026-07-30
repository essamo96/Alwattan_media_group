<?php

namespace App\Http\Controllers\Admin;

use App\Models\Roles;
use App\Models\PermissionsGroup;
use App\Models\RoleHasPermissions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

class RolesController extends AdminController
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
        parent::$data['active_menu'] = 'roles';
    }
    //////////////////////////////////////////////
    public function getIndex()
    {
        return view('admin.roles.view', parent::$data);
    }
    //////////////////////////////////////////////
    public function getList(Request $request)
    {
        $role = new Roles();

        $length = $request->get('length');
        $start = $request->get('start');
        $name = $request->get('name');

        $info = $role->getRoles($name);

        $datatable = Datatables::of($info);

        $datatable->editColumn('email', function ($row)
        {
            return (!empty($row->name) ? $row->name : 'N/A');
        });

        $datatable->editColumn('status', function ($row)
        {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.roles.parts.status', $data)->render();
        });

        $datatable->addColumn('actions', function ($row)
        {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.roles.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }
    //////////////////////////////////////////////
    public function getAdd()
    {
        return view('admin.roles.add', parent::$data);
    }
    //////////////////////////////////////////////
    public function postAdd(Request $request)
    {
        $name = $request->get('name');
        $status = (int)$request->get('status');

        $validator = Validator::make([
            'name' => $name,
            'status' => $status
        ], [
            'name' => 'required',
            'status' => 'required|numeric|in:0,1'
        ]);

        if ($validator->fails())
        {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('roles.add'))->withInput();
        }
        else
        {
            $role = new Roles();
            $add = $role->addRole($name, $status);
            if ($add)
            {
                Cache::forget('spatie.permission.cache');
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('roles.view'));
            }
            else
            {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('roles.add'))->withInput();
            }
        }
    }
    //////////////////////////////////////////////
    public function getEdit(Request $request, $id)
    {
        try
        {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }
        if ($id == 1)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }

        $role = new Roles();
        $info = $role->getRole($id);
        if ($info)
        {
            parent::$data['info'] = $info;
            return view('admin.roles.edit', parent::$data);
        }
        else
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }
    }
    //////////////////////////////////////////////
    public function postEdit(Request $request, $id)
    {
        try
        {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }
        if ($id == 1)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }

        $role = new Roles();
        $info = $role->getRole($id);
        if ($info)
        {
            $name = $request->get('name');
            $status = (int)$request->get('status');

            $validator = Validator::make([
                'name' => $name,
                'status' => $status
            ], [
                'name' => 'required',
                'status' => 'required|numeric|in:0,1'
            ]);

            if ($validator->fails())
            {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('riles.edit', ['id' => $encrypted_id]))->withInput();
            }
            else
            {
                $update = $role->updateRole($info, $name, $status);
                if ($update)
                {
                    Cache::forget('spatie.permission.cache');
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('roles.view'));
                }
                else
                {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('roles.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        }
        else
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }
    }
    //////////////////////////////////////////////
    public function getPermissions(Request $request, $id)
    {
        try
        {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e)
        {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        if ($id == 1)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }
        //////////////////////////////////////////
        $roles = new Roles();
        $info = $roles->getRole($id);
        if($info)
        {
            $permission_group = new PermissionsGroup();
            parent::$data['permission_group'] = $permission_group->getAllPermissionGroup();
            $role_has_permissions = new RoleHasPermissions();
            parent::$data['role_permissions'] = $role_has_permissions->getRoleHasPermissionsByRoleId($id);
            parent::$data['info'] = $info;
            return view('admin.roles.permissions', parent::$data);
        }
        else
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('roles.view'));
        }
    }
    //////////////////////////////////////////////
    public function postPermissions(Request $request, $id)
    {
        try
        {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e)
        {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $permissions = $request->get('permissions');
        
        if(sizeof($permissions) > 0)
        {
            $role_has_permissions = new RoleHasPermissions();
            $role_has_permissions->deleteRoleHasPermissionsByRoleId($id);

            foreach ($permissions as $permission_id)
            {
                $role_has_permissions = new RoleHasPermissions();
                $add = $role_has_permissions->addRoleHasPermissions($permission_id,$id);

            }
            Cache::forget('spatie.permission.cache');

            $request->session()->flash('success', self::UPDATE_SUCCESS);
            return redirect(route('roles.permissions', ['id' => Crypt::encrypt($id)]));
        }
        else
        {
            $role_has_permissions = new RoleHasPermissions();
            $role_has_permissions->deleteRoleHasPermissionsByRoleId($id);
            $request->session()->flash('success', self::UPDATE_SUCCESS);
            return redirect(route('roles.permissions', ['id' => Crypt::encrypt($id)]));
        }

    }
    //////////////////////////////////////////////
    public function postStatus(Request $request)
    {
        $id = $request->get('id');
        try
        {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e)
        {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        if ($id == 1)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return response()->json(['status' => 'error', 'message' => 'Error, Data not found']);
        }

        $roles = new Roles();
        $info = $roles->getRole($id);
        if ($info)
        {
            $status = $info->status;
            if($status == 0)
            {
                $delete = $roles->updateStatus($id,1);
                if($delete)
                {
                    Cache::forget('spatie.permission.cache');
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                }
                else
                {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
            else
            {
                $delete = $roles->updateStatus($id,0);
                if($delete)
                {
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                }
                else
                {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        }
        else
        {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }
    //////////////////////////////////////////////
    public function postDelete(Request $request)
    {
        $id = $request->get('id');
        try
        {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e)
        {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        if ($id == 1)
        {
            $request->session()->flash('danger', self::NOT_FOUND);
            return response()->json(['status' => 'error', 'message' => 'Error, Data not found']);
        }
        $roles = new Roles();
        $info = $roles->getRole($id);
        if ($info)
        {
            $delete = $roles->deleteRole($info);
            if ($delete)
            {
                Cache::forget('spatie.permission.cache');
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            }
            else
            {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        }
        else
        {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }
}
