<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
//use App\Models\Categories;
use App\Models\Socials;
use App\Models\Menus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

// use App\Providers\Paginator;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        view()->composer('errors::*', function ($view) {
//            $categories = new Categories();
            $social = new Socials();
            $view->with('social', $social->getAllSocialActive());
            $view->with('active_menu', '');
            // $view->with('categories', $categories->getMenuActiveCategories());
            $view->with('mysettings', Settings::findOrFail(1));
        });
        // قوائم الناف بار في الموقع الخارجي (الترتيب والحالة يتم التحكم بهما من لوحة التحكم)
        view()->composer('frontend.general.menu', function ($view) {
            $view->with('site_menus', $this->getSiteMenus());
        });
        // Paginator::defaultView('vendor.pagination.13');
        // Paginator::useBootstrap();
    }

    /**
     * ترجع القوائم المفعلة مرتبة، وتعيد مجموعة فارغة اذا لم يتم تنفيذ المايجريشن بعد.
     */
    private function getSiteMenus() {
        try {
            if (!Schema::hasTable('menus')) {
                return collect([]);
            }
            return Cache::rememberForever('menus', function () {
                        $menus = new Menus();
                        return $menus->getAllActiveMenus();
                    });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
