<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
//use App\Models\Categories;
use App\Models\Socials;

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
        // Paginator::defaultView('vendor.pagination.13');
        // Paginator::useBootstrap();
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
