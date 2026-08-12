<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
//use App\Models\Categories;
use App\Models\Socials;
use App\Models\Menus;
use App\Models\GalleryImage;
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
        // معرض الصور بالموقع الخارجي (يُدار بالكامل من لوحة التحكم: admin/gallery)
        view()->composer('frontend.general.footer', function ($view) {
            $view->with('gallery_images', $this->getGalleryImages());
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
     * ترجع صور معرض الصور المفعّلة مرتبة، وتعيد مجموعة فارغة اذا لم يتم تنفيذ المايجريشن بعد.
     */
    private function getGalleryImages() {
        try {
            if (!Schema::hasTable('gallery_images')) {
                return collect([]);
            }
            return Cache::rememberForever('gallery_images_active', function () {
                        $gallery = new GalleryImage();
                        return $gallery->getAllActiveImages();
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
