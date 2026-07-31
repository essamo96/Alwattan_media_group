<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\Permissions;

/**
 * مجموعة صلاحيات "ادارة قوائم الموقع" التي تتحكم في قوائم الناف بار في الموقع الخارجي.
 */
class MenusPermissionsSeeder extends Seeder {

    public function run() {
        $guard_name = Permissions::value('guard_name');
        if (empty($guard_name)) {
            $guard_name = 'admin';
        }

        // جداول permissions و permissions_group منشأة يدوياً بدون AUTO_INCREMENT
        // لذلك نولّد المعرّف بانفسنا بدل الاعتماد على قاعدة البيانات
        $now = date('Y-m-d H:i:s');

        $group = DB::table('permissions_group')->where('name', 'ادارة قوائم الموقع')->first();
        if (!$group) {
            $group_id = (int) DB::table('permissions_group')->max('id') + 1;
            $group_row = ['id' => $group_id, 'name' => 'ادارة قوائم الموقع'];
            if (Schema::hasColumn('permissions_group', 'created_at')) {
                $group_row['created_at'] = $now;
                $group_row['updated_at'] = $now;
            }
            // العمود deleted_at معرّف NOT NULL بدون قيمة افتراضية في هذا المشروع
            if (Schema::hasColumn('permissions_group', 'deleted_at')) {
                $group_row['deleted_at'] = '1000-01-01 00:00:00';
            }
            DB::table('permissions_group')->insert($group_row);
        } else {
            $group_id = $group->id;
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
        $next_id = (int) DB::table('permissions')->max('id');
        foreach ($permissions as $name) {
            $permission = DB::table('permissions')->where('name', $name)->first();
            if (!$permission) {
                $next_id++;
                DB::table('permissions')->insert([
                    'id' => $next_id,
                    'name' => $name,
                    'group_id' => $group_id,
                    'guard_name' => $guard_name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $permission_ids[] = $next_id;
            } else {
                DB::table('permissions')->where('id', $permission->id)->update(['group_id' => $group_id]);
                $permission_ids[] = $permission->id;
            }
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
