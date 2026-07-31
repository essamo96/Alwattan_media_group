<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\Permissions;
use App\Models\PermissionsGroup;

/**
 * مجموعة صلاحيات "ادارة قوائم الموقع" التي تتحكم في قوائم الناف بار في الموقع الخارجي.
 */
class MenusPermissionsSeeder extends Seeder {

    public function run() {
        $guard_name = Permissions::value('guard_name');
        if (empty($guard_name)) {
            $guard_name = 'admin';
        }

        // بعض الجداول في المشروع منشأة يدوياً بدون اعمدة التواريخ
        $group_model = new PermissionsGroup();
        $group_model->timestamps = Schema::hasColumn('permissions_group', 'created_at');

        $group = PermissionsGroup::where('name', 'ادارة قوائم الموقع')->first();
        if (!$group) {
            $group_model->name = 'ادارة قوائم الموقع';
            $group_model->save();
            $group = $group_model;
        }

        $permissions = [
            'admin.menus.view',
            'admin.menus.add',
            'admin.menus.edit',
            'admin.menus.delete',
            'admin.menus.status',
            'admin.menus.sort',
        ];

        $permission_ids = [];
        foreach ($permissions as $name) {
            $permission = Permissions::where('name', $name)->first();
            if (!$permission) {
                $permission = Permissions::create([
                            'name' => $name,
                            'group_id' => $group->id,
                            'guard_name' => $guard_name,
                ]);
            } else {
                $permission->group_id = $group->id;
                $permission->save();
            }
            $permission_ids[] = $permission->id;
        }

        // منح الصلاحيات الجديدة لادوار المدراء
        // (1) كل دور يملك صلاحية الاعدادات او صلاحية ادارة الصلاحيات
        $admin_role_ids = DB::table('role_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->whereIn('permissions.name', ['admin.settings.view', 'admin.roles.permissions'])
                ->pluck('role_has_permissions.role_id')
                ->unique()
                ->values();

        // (2) بالاضافة الى ادوار المستخدمين الحاليين الذين يملكون اغلب الصلاحيات
        //     (ضمان ظهور القائمة لمدير النظام حتى لو اختلفت اسماء الصلاحيات)
        $top_role = DB::table('role_has_permissions')
                ->select('role_id', DB::raw('COUNT(*) as total'))
                ->groupBy('role_id')
                ->orderBy('total', 'desc')
                ->first();
        if ($top_role && !$admin_role_ids->contains($top_role->role_id)) {
            $admin_role_ids->push($top_role->role_id);
        }

        if ($admin_role_ids->isEmpty()) {
            $this->command->warn('لم يتم العثور على اي دور لمنحه صلاحيات القوائم. امنحها يدوياً من: الصلاحيات > تعديل صلاحيات الدور.');
        }

        foreach ($admin_role_ids as $role_id) {
            $granted = 0;
            foreach ($permission_ids as $permission_id) {
                $exists = DB::table('role_has_permissions')
                        ->where('role_id', $role_id)
                        ->where('permission_id', $permission_id)
                        ->exists();
                if (!$exists) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $role_id,
                        'permission_id' => $permission_id,
                    ]);
                    $granted++;
                }
            }
            $role_name = DB::table('roles')->where('id', $role_id)->value('name');
            $this->command->info('تم منح صلاحيات القوائم للدور: ' . $role_name . ' (' . $granted . ' صلاحية جديدة)');
        }

        // مسح كاش الصلاحيات بنفس الطريقة المستخدمة في المشروع
        Cache::forget('spatie.permission.cache');
    }

}
