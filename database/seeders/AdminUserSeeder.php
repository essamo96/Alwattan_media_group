<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * ينشئ حساب مدير النظام ويربطه بدور يملك كل الصلاحيات (guard: admin).
 * يمكن تغيير البيانات عبر متغيرات البيئة:
 * ADMIN_USERNAME / ADMIN_PASSWORD / ADMIN_EMAIL / ADMIN_NAME
 */
class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $username = env('ADMIN_USERNAME', 'admin');
        $password = env('ADMIN_PASSWORD', 'Admin@2026');
        $email = env('ADMIN_EMAIL', 'admin@alwattan.ps');
        $name = env('ADMIN_NAME', 'مدير النظام');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1) دور مدير النظام على حارس admin
        $role = Role::where('guard_name', 'admin')
            ->where('name', 'مدير النظام')
            ->first();

        if (! $role) {
            $role = Role::create(['name' => 'مدير النظام', 'guard_name' => 'admin']);
        }

        // 2) سينك كل الصلاحيات الى هذا الدور
        $permissions = Permission::where('guard_name', 'admin')->get();
        $role->syncPermissions($permissions);

        // 3) المستخدم
        $user = User::withTrashed()->where('username', $username)->first();

        if ($user) {
            if ($user->trashed()) {
                $user->restore();
            }
        } else {
            $user = new User();
            $user->username = $username;
        }

        $user->name = $name;
        $user->email = $email;
        $user->role = $role->id;
        $user->created_by = 0;
        $user->status = 1;
        $user->password = Hash::make($password);
        $user->save();

        // 4) ربط المستخدم بالدور
        $user->syncRoles([$role]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('');
        $this->command->info('  تم انشاء / تحديث حساب المدير:');
        $this->command->info('  username : ' . $username);
        $this->command->info('  password : ' . $password);
        $this->command->info('  role     : ' . $role->name . ' (' . $permissions->count() . ' صلاحية)');
        $this->command->info('');
    }
}
