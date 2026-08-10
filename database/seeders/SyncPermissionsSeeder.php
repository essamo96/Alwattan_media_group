<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * يقرأ كل الصلاحيات المطلوبة فعليا من middleware الراوتات، ينشئ الناقص منها،
 * ثم يعمل سينك لكل الصلاحيات الى دور "مدير النظام".
 * تشغيله آمن ومتكرر: لا ينشئ نسخا مكررة ولا يمس صلاحيات الادوار الاخرى.
 */
class SyncPermissionsSeeder extends Seeder
{
    /** اسم مجموعة الصلاحيات لكل قسم */
    private array $groupNames = [
        'settings' => 'الإعدادات',
        'users' => 'إدارة المستخدمين',
        'roles' => 'الصلاحيات',
        'categories' => 'التصنيفات',
        'news' => 'الأخبار',
        'sliders' => 'السلايدات الرئيسية',
        'social' => 'الشبكات الإجتماعية',
        'pages' => 'الصفحات الثابته',
        'services' => 'الخدمات',
        'testimonials' => 'قالوا عنا',
        'partners' => 'الشركاء',
        'advertisements' => 'الإعلانات',
        'faq' => 'الاسئلة الشائعة',
        'contact' => 'ادارة جهات الاتصال',
        'contacts' => 'ادارة جهات الاتصال',
        'menus' => 'ادارة قوائم الموقع',
        'cities' => 'المدن',
        'properties' => 'العقارات',
        'properties_categories' => 'اقسام العقارات',
        'properties_types' => 'انواع العقارات',
        'registrations' => 'تسجيلات الدورة',
        'courses' => 'الدورات',
        'course_candidates' => 'مرشحين الدورات',
        'sms' => 'الرسائل النصية',
    ];

    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $needed = $this->permissionsUsedInRoutes();
        $existing = Permission::where('guard_name', 'admin')->pluck('name')->all();
        $missing = array_values(array_diff($needed, $existing));

        foreach ($missing as $name) {
            Permission::create([
                'name' => $name,
                'guard_name' => 'admin',
                'group_id' => $this->groupIdFor($name),
            ]);
        }

        $this->command->info('  صلاحيات مطلوبة في الراوتات : ' . count($needed));
        $this->command->info('  صلاحيات جديدة أُنشئت      : ' . count($missing));

        // سينك كل الصلاحيات الى دور مدير النظام
        $role = Role::where('guard_name', 'admin')->where('name', 'مدير النظام')->first();

        if ($role) {
            $all = Permission::where('guard_name', 'admin')->get();
            $role->syncPermissions($all);
            $this->command->info('  سينك الى "' . $role->name . '"      : ' . $all->count() . ' صلاحية');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * يستخرج اسماء الصلاحيات من middleware المسجلة على الراوتات: permission:a|b
     */
    private function permissionsUsedInRoutes(): array
    {
        $names = [];

        foreach (Route::getRoutes() as $route) {
            foreach ($route->gatherMiddleware() as $middleware) {
                if (! is_string($middleware) || ! str_starts_with($middleware, 'permission:')) {
                    continue;
                }

                foreach (explode('|', substr($middleware, strlen('permission:'))) as $name) {
                    $name = trim($name);
                    if ($name !== '') {
                        $names[$name] = true;
                    }
                }
            }
        }

        $names = array_keys($names);
        sort($names);

        return $names;
    }

    /**
     * يستنتج مجموعة الصلاحية من اسمها (admin.<section>.<action>) وينشئ المجموعة ان لزم.
     */
    private function groupIdFor(string $permission): int
    {
        $parts = explode('.', $permission);
        $section = $parts[1] ?? 'other';
        $label = $this->groupNames[$section] ?? $section;

        $group = DB::table('permissions_group')->where('name', $label)->first();

        if ($group) {
            return (int) $group->id;
        }

        return (int) DB::table('permissions_group')->insertGetId([
            'name' => $label,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
