<?php

// use App\Http\Controllers\CategoriesController;
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'web']
    ],
    function () {
        Route::get('/', ['as' => 'homepage.view', 'uses' => 'HomepageController@getIndex']);
        Route::get('category/{slug}', ['as' => 'category.view', 'uses' => 'CategoriesController@getIndex']);
        Route::get('post/{slug}', ['as' => 'category.view', 'uses' => 'NewsController@getNews']);
        Route::post('contact', ['as' => 'contact.view', 'uses' => 'ContactController@postContact']);
        Route::post('news/info', ['as' => 'news.info', 'uses' => 'NewsController@postNews']);
    }
);
Route::get('switchTo={lang}', [
    'as' => 'ChangeLang',
    'uses' => 'PageController@switchLang',
]);

////////////////////////////////////////////////////////////////////////////////////////////////////////////
// route to open admin page
Route::get('/admin', function () {
    return redirect('admin/dashboard');
});

// Login Route
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['web', 'guest:admin']], function () {
    Route::get('login', ['as' => 'app.login', 'uses' => 'LoginController@getIndex']);
    Route::post('login', ['as' => 'app.login', 'uses' => 'LoginController@postIndex']);
});

Route::group(['prefix' => 'admin/file_manager', 'middleware' => ['web', 'auth:admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['web', 'auth:admin']], function () {
    // Route
    Route::get('dashboard', ['as' => 'dashboard.view', 'uses' => 'DashboardController@getIndex']);
    Route::get('profile', ['as' => 'dashboard.profile', 'uses' => 'DashboardController@getProfile']);
    Route::get('password', ['as' => 'dashboard.password', 'uses' => 'DashboardController@getPassword']);
    Route::post('password', ['as' => 'dashboard.password', 'uses' => 'DashboardController@postPassword']);

    //notifacation Route
    Route::get('today/notifiacation', ['as' => 'Today.notifcation', 'middleware' => ['permission:admin.contact.dalay'], 'uses' => 'ContactController@getTodayNotifications']);
    //Users Route
    Route::get('users', ['as' => 'users.view', 'middleware' => ['permission:admin.users.view|admin.users.add|admin.users.edit|admin.users.delete|admin.users.status|admin.users.password'], 'uses' => 'UsersController@getIndex']);
    Route::get('users/list', ['as' => 'users.list', 'middleware' => ['permission:admin.users.view|admin.users.add|admin.users.edit|admin.users.delete|admin.users.status|admin.users.password'], 'uses' => 'UsersController@getList']);
    Route::get('users/add', ['as' => 'users.add', 'middleware' => ['permission:admin.users.add'], 'uses' => 'UsersController@getAdd']);
    Route::post('users/add', ['as' => 'users.add', 'middleware' => ['permission:admin.users.add'], 'uses' => 'UsersController@postAdd']);
    Route::get('users/edit/{id}', ['as' => 'users.edit', 'middleware' => ['permission:admin.users.edit'], 'uses' => 'UsersController@getEdit']);
    Route::post('users/edit/{id}', ['as' => 'users.edit', 'middleware' => ['permission:admin.users.edit'], 'uses' => 'UsersController@postEdit']);
    Route::get('users/password/{id}', ['as' => 'users.password', 'middleware' => ['permission:admin.users.password'], 'uses' => 'UsersController@getPassword']);
    Route::post('users/password/{id}', ['as' => 'users.password', 'middleware' => ['permission:admin.users.password'], 'uses' => 'UsersController@postPassword']);
    Route::post('users/delete', ['as' => 'users.delete', 'middleware' => ['permission:admin.users.delete'], 'uses' => 'UsersController@postDelete']);
    Route::post('users/status', ['as' => 'users.status', 'middleware' => ['permission:admin.users.status'], 'uses' => 'UsersController@postStatus']);

    //Roles Route
    Route::get('roles', ['as' => 'roles.view', 'middleware' => ['permission:admin.roles.view|admin.roles.add|admin.roles.edit|admin.roles.delete|admin.roles.status|admin.roles.permissions'], 'uses' => 'RolesController@getIndex']);
    Route::get('roles/list', ['as' => 'roles.list', 'middleware' => ['permission:admin.roles.view|admin.roles.add|admin.roles.edit|admin.roles.delete|admin.roles.status|admin.roles.permissions'], 'uses' => 'RolesController@getList']);
    Route::get('roles/add', ['as' => 'roles.add', 'middleware' => ['permission:admin.roles.add'], 'uses' => 'RolesController@getAdd']);
    Route::post('roles/add', ['as' => 'roles.add', 'middleware' => ['permission:admin.roles.add'], 'uses' => 'RolesController@postAdd']);
    Route::get('roles/edit/{id}', ['as' => 'roles.edit', 'middleware' => ['permission:admin.roles.edit'], 'uses' => 'RolesController@getEdit']);
    Route::post('roles/edit/{id}', ['as' => 'roles.edit', 'middleware' => ['permission:admin.roles.edit'], 'uses' => 'RolesController@postEdit']);
    Route::post('roles/delete', ['as' => 'roles.delete', 'middleware' => ['permission:admin.roles.delete'], 'uses' => 'RolesController@postDelete']);
    Route::post('roles/status', ['as' => 'roles.status', 'middleware' => ['permission:admin.roles.status'], 'uses' => 'RolesController@postStatus']);
    Route::get('roles/permissions/{id}', ['as' => 'roles.permissions', 'middleware' => ['permission:admin.roles.permissions'], 'uses' => 'RolesController@getPermissions']);
    Route::post('roles/permissions/{id}', ['as' => 'roles.permissions', 'middleware' => ['permission:admin.roles.permissions'], 'uses' => 'RolesController@postPermissions']);

    //Static Page Route
    Route::get('pages', ['as' => 'pages.view', 'uses' => 'PagesController@getIndex']);
    Route::get('pages/list', ['as' => 'pages.list', 'uses' => 'PagesController@getList']);
    Route::get('pages/add', ['as' => 'pages.add', 'uses' => 'PagesController@getAdd']);
    Route::post('pages/add', ['as' => 'pages.add', 'uses' => 'PagesController@postAdd']);
    Route::get('pages/edit/{id}', ['as' => 'pages.edit', 'uses' => 'PagesController@getEdit']);
    Route::post('pages/edit/{id}', ['as' => 'pages.edit', 'uses' => 'PagesController@postEdit']);
    Route::post('pages/delete', ['as' => 'pages.delete', 'uses' => 'PagesController@postDelete']);
    Route::post('pages/status', ['as' => 'pages.status', 'uses' => 'PagesController@postStatus']);
    //Menus Route (قوائم الناف بار في الموقع الخارجي)
    Route::get('menus', ['as' => 'menus.view', 'middleware' => ['permission:admin.menus.view|admin.menus.add|admin.menus.edit|admin.menus.delete|admin.menus.status|admin.menus.sort'], 'uses' => 'MenusController@getIndex']);
    Route::get('menus/list', ['as' => 'menus.list', 'middleware' => ['permission:admin.menus.view|admin.menus.add|admin.menus.edit|admin.menus.delete|admin.menus.status|admin.menus.sort'], 'uses' => 'MenusController@getList']);
    Route::get('menus/add', ['as' => 'menus.add', 'middleware' => ['permission:admin.menus.add'], 'uses' => 'MenusController@getAdd']);
    Route::post('menus/add', ['as' => 'menus.add', 'middleware' => ['permission:admin.menus.add'], 'uses' => 'MenusController@postAdd']);
    Route::get('menus/edit/{id}', ['as' => 'menus.edit', 'middleware' => ['permission:admin.menus.edit'], 'uses' => 'MenusController@getEdit']);
    Route::post('menus/edit/{id}', ['as' => 'menus.edit', 'middleware' => ['permission:admin.menus.edit'], 'uses' => 'MenusController@postEdit']);
    Route::post('menus/delete', ['as' => 'menus.delete', 'middleware' => ['permission:admin.menus.delete'], 'uses' => 'MenusController@postDelete']);
    Route::post('menus/status', ['as' => 'menus.status', 'middleware' => ['permission:admin.menus.status'], 'uses' => 'MenusController@postStatus']);
    Route::post('menus/sort', ['as' => 'menus.sort', 'middleware' => ['permission:admin.menus.sort'], 'uses' => 'MenusController@postSort']);

    //services Route
    Route::get('services', ['as' => 'services.view', 'uses' => 'ServicesController@getIndex']);
    Route::get('services/list', ['as' => 'services.list', 'uses' => 'ServicesController@getList']);
    Route::get('services/add', ['as' => 'services.add', 'uses' => 'ServicesController@getAdd']);
    Route::post('services/add', ['as' => 'services.add', 'uses' => 'ServicesController@postAdd']);
    Route::get('services/edit/{id}', ['as' => 'services.edit', 'uses' => 'ServicesController@getEdit']);
    Route::post('services/edit/{id}', ['as' => 'services.edit', 'uses' => 'ServicesController@postEdit']);
    Route::post('services/delete', ['as' => 'services.delete', 'uses' => 'ServicesController@postDelete']);
    Route::post('services/status', ['as' => 'services.status', 'uses' => 'ServicesController@postStatus']);

    //Contacts Route
    //    Route::get('contacts', ['as' => 'contacts.view', 'middleware' => ['permission:admin.contact.view|admin.contact.delete|admin.contact.status|admin.contact.reply'], 'uses' => 'ContactsController@getIndex']);
    //    Route::get('contacts/list', ['as' => 'contacts.list', 'middleware' => ['permission:admin.contact.view|admin.contact.delete|admin.contact.status|admin.contact.reply'], 'uses' => 'ContactsController@getList']);
    //    Route::get('contacts/reply/{id}', ['as' => 'contacts.reply', 'middleware' => ['permission:admin.contact.reply'], 'uses' => 'ContactsController@getReply']);
    //    Route::post('contacts/delete', ['as' => 'contacts.delete', 'middleware' => ['permission:admin.contact.delete'], 'uses' => 'ContactsController@postDelete']);
    //    Route::post('contacts/status', ['as' => 'contacts.status', 'middleware' => ['permission:admin.contact.status'], 'uses' => 'ContactsController@postStatus']);
    //Contacts Route
    Route::get('contact', ['as' => 'contact.view', 'middleware' => ['permission:admin.contacts.view'], 'uses' => 'ContactController@getIndex']);
    Route::get('contact/list', ['as' => 'contact.list', 'middleware' => ['permission:admin.contacts.view'], 'uses' => 'ContactController@getList']);
    //   Route::get('contact/you/add', ['as' => 'your.contact.view', 'middleware' => ['permission:admin.contacts.view'], 'uses' => 'ContactController@getYourContact']);
    //   Route::get('your/contact/list', ['as' => 'auth.contact.list', 'middleware' => ['permission:admin.contacts.view'], 'uses' => 'ContactController@getAuthAddedList']);
    Route::get('contact/remember', ['as' => 'contact.remember', 'middleware' => ['permission:admin.contact.remember'], 'uses' => 'ContactController@getRememberContact']);
    Route::get('remember/contact/list', ['as' => 'contact.remember.list', 'middleware' => ['permission:admin.contact.remember'], 'uses' => 'ContactController@getRememberContactList']);
    Route::get('contact/add', ['as' => 'contact.add', 'middleware' => ['permission:admin.contact.add'], 'uses' => 'ContactController@getAdd']);
    Route::post('contact/add', ['as' => 'contact.add', 'middleware' => ['permission:admin.contact.add'], 'uses' => 'ContactController@postAdd']);
    Route::get('contact/edit/{id}', ['as' => 'contact.edit', 'middleware' => ['permission:admin.contact.edit'], 'uses' => 'ContactController@getEdit']);
    Route::get('contact/info/{id}', ['as' => 'contact.info', 'middleware' => ['permission:admin.contact.detailes'], 'uses' => 'ContactController@getContactInfo']);
    Route::post('contact/edit/{id}', ['as' => 'contact.edit', 'middleware' => ['permission:admin.contact.edit'], 'uses' => 'ContactController@postEdit']);
    Route::post('contact/delete', ['as' => 'contact.delete', 'middleware' => ['permission:admin.contacts.delete'], 'uses' => 'ContactController@postDelete']);
    Route::post('contact/status', ['as' => 'contact.status', 'middleware' => ['permission:admin.contacts.status'], 'uses' => 'ContactController@postStatus']);
    Route::get('contact/print/{id}', ['as' => 'print.get', 'middleware' => ['permission:admin.contacts.view'], 'uses' => 'ContactController@printContact']);
    Route::get('contact/printall', ['as' => 'contact.printall', 'middleware' => ['permission:admin.contacts.view'], 'uses' => 'ContactController@printAllContact']);
    // Social Route
    Route::get('socials', ['as' => 'socials.view', 'middleware' => ['permission:admin.social.view'], 'uses' => 'SocialsController@getIndex']);
    Route::post('socials', ['as' => 'socials.list', 'middleware' => ['permission:admin.social.view'], 'uses' => 'SocialsController@postIndex']);

    //Settings Route
    Route::get('settings', ['as' => 'settings.view', 'middleware' => ['permission:admin.settings.view'], 'uses' => 'SettingsController@getIndex']);
    Route::post('settings', ['as' => 'settings.list', 'middleware' => ['permission:admin.settings.view'], 'uses' => 'SettingsController@postIndex']);

    //Categories Route
    Route::get('categories', ['as' => 'categories.view', 'middleware' => ['permission:admin.categories.view|admin.categories.add|admin.categories.edit|admin.categories.delete|admin.categories.status'], 'uses' => 'CategoriesController@getIndex']);
    Route::get('categories/list', ['as' => 'categories.list', 'middleware' => ['permission:admin.categories.view|admin.categories.add|admin.categories.edit|admin.categories.delete|admin.categories.status'], 'uses' => 'CategoriesController@getList']);
    Route::get('categories/add', ['as' => 'categories.add', 'middleware' => ['permission:admin.categories.add'], 'uses' => 'CategoriesController@getAdd']);
    Route::post('categories/add', ['as' => 'categories.add', 'middleware' => ['permission:admin.categories.add'], 'uses' => 'CategoriesController@postAdd']);
    Route::get('categories/edit/{id}', ['as' => 'categories.edit', 'middleware' => ['permission:admin.categories.edit'], 'uses' => 'CategoriesController@getEdit']);
    Route::post('categories/edit/{id}', ['as' => 'categories.edit', 'middleware' => ['permission:admin.categories.edit'], 'uses' => 'CategoriesController@postEdit']);
    Route::post('categories/delete', ['as' => 'categories.delete', 'middleware' => ['permission:admin.categories.delete'], 'uses' => 'CategoriesController@postDelete']);
    Route::post('categories/status', ['as' => 'categories.status', 'middleware' => ['permission:admin.categories.status'], 'uses' => 'CategoriesController@postStatus']);

    //News Route
    Route::get('news', ['as' => 'news.view', 'middleware' => ['permission:admin.news.view|admin.news.add|admin.news.edit|admin.news.delete|admin.news.status'], 'uses' => 'NewsController@getIndex']);
    Route::get('news/list', ['as' => 'news.list', 'middleware' => ['permission:admin.news.view|admin.news.add|admin.news.edit|admin.news.delete|admin.news.status'], 'uses' => 'NewsController@getList']);
    Route::get('news/add', ['as' => 'news.add', 'middleware' => ['permission:admin.news.add'], 'uses' => 'NewsController@getAdd']);
    Route::post('news/add', ['as' => 'news.add', 'middleware' => ['permission:admin.news.add'], 'uses' => 'NewsController@postAdd']);
    Route::get('news/edit/{id}', ['as' => 'news.edit', 'middleware' => ['permission:admin.news.edit'], 'uses' => 'NewsController@getEdit']);
    Route::post('news/edit/{id}', ['as' => 'news.edit', 'middleware' => ['permission:admin.news.edit'], 'uses' => 'NewsController@postEdit']);
    Route::post('news/delete', ['as' => 'news.delete', 'middleware' => ['permission:admin.news.delete'], 'uses' => 'NewsController@postDelete']);
    Route::post('news/publish', ['as' => 'news.publish', 'middleware' => ['permission:admin.news.publish'], 'uses' => 'NewsController@postPublish']);
    Route::get('news/cleaAllCache', ['as' => 'news.cleaAllCache', 'middleware' => ['permission:admin.news.publish'], 'uses' => 'NewsController@cleaAllCache']);
    Route::get('news/twitter', ['as' => 'news.twitter', 'middleware' => ['permission:admin.news.publish'], 'uses' => 'NewsController@getTwitter']);
    Route::post('upload_image', ['as' => 'news.upload', 'uses' => 'NewsController@getImage']);

    // SLIDERS ROUTE
    Route::get('sliders', ['as' => 'sliders.view', 'middleware' => ['permission:admin.sliders.view|admin.sliders.add|admin.sliders.edit|admin.sliders.delete|admin.sliders.status'], 'uses' => 'SlidersController@getIndex']);
    Route::get('sliders/list', ['as' => 'sliders.list', 'middleware' => ['permission:admin.sliders.view|admin.sliders.add|admin.sliders.edit|admin.sliders.delete|admin.sliders.status'], 'uses' => 'SlidersController@getList']);
    Route::get('sliders/add', ['as' => 'sliders.add', 'middleware' => ['permission:admin.sliders.add'], 'uses' => 'SlidersController@getAdd']);
    Route::post('sliders/add', ['as' => 'sliders.add', 'middleware' => ['permission:admin.sliders.add'], 'uses' => 'SlidersController@postAdd']);
    Route::get('sliders/edit/{id}', ['as' => 'sliders.edit', 'middleware' => ['permission:admin.sliders.edit'], 'uses' => 'SlidersController@getEdit']);
    Route::post('sliders/edit/{id}', ['as' => 'sliders.edit', 'middleware' => ['permission:admin.sliders.edit'], 'uses' => 'SlidersController@postEdit']);
    Route::post('sliders/delete', ['as' => 'sliders.delete', 'middleware' => ['permission:admin.sliders.delete'], 'uses' => 'SlidersController@postDelete']);
    Route::post('sliders/status', ['as' => 'sliders.status', 'middleware' => ['permission:admin.sliders.status'], 'uses' => 'SlidersController@postStatus']);

    // Series ROUTE
    Route::get('properties_categories', ['as' => 'properties_categories.view', 'middleware' => ['permission:admin.properties_categories.view'], 'uses' => 'PropertiesCategoriesController@getIndex']);
    Route::get('properties_categories/list', ['as' => 'properties_categories.list', 'middleware' => ['permission:admin.properties_categories.view'], 'uses' => 'PropertiesCategoriesController@getList']);
    Route::get('properties_categories/add', ['as' => 'properties_categories.add', 'middleware' => ['permission:admin.properties_categories.add'], 'uses' => 'PropertiesCategoriesController@getAdd']);
    Route::post('properties_categories/add', ['as' => 'properties_categories.add', 'middleware' => ['permission:admin.properties_categories.add'], 'uses' => 'PropertiesCategoriesController@postAdd']);
    Route::get('properties_categories/edit/{id}', ['as' => 'properties_categories.edit', 'middleware' => ['permission:admin.properties_categories.edit'], 'uses' => 'PropertiesCategoriesController@getEdit']);
    Route::post('properties_categories/edit/{id}', ['as' => 'properties_categories.edit', 'middleware' => ['permission:admin.properties_categories.edit'], 'uses' => 'PropertiesCategoriesController@postEdit']);
    Route::post('properties_categories/delete', ['as' => 'properties_categories.delete', 'middleware' => ['permission:admin.properties_categories.delete'], 'uses' => 'PropertiesCategoriesController@postDelete']);
    Route::post('properties_categories/status', ['as' => 'properties_categories.status', 'middleware' => ['permission:admin.properties_categories.status'], 'uses' => 'PropertiesCategoriesController@postStatus']);
    // Series ROUTE
    Route::get('properties_types', ['as' => 'properties_types.view', 'middleware' => ['permission:admin.properties_types.view'], 'uses' => 'PropertiesTypesController@getIndex']);
    Route::get('properties_types/list', ['as' => 'properties_types.list', 'middleware' => ['permission:admin.properties_types.view'], 'uses' => 'PropertiesTypesController@getList']);
    Route::get('properties_types/add', ['as' => 'properties_types.add', 'middleware' => ['permission:admin.properties_types.add'], 'uses' => 'PropertiesTypesController@getAdd']);
    Route::post('properties_types/add', ['as' => 'properties_types.add', 'middleware' => ['permission:admin.properties_types.add'], 'uses' => 'PropertiesTypesController@postAdd']);
    Route::get('properties_types/edit/{id}', ['as' => 'properties_types.edit', 'middleware' => ['permission:admin.properties_types.edit'], 'uses' => 'PropertiesTypesController@getEdit']);
    Route::post('properties_types/edit/{id}', ['as' => 'properties_types.edit', 'middleware' => ['permission:admin.properties_types.edit'], 'uses' => 'PropertiesTypesController@postEdit']);
    Route::post('properties_types/delete', ['as' => 'properties_types.delete', 'middleware' => ['permission:admin.properties_types.delete'], 'uses' => 'PropertiesTypesController@postDelete']);
    Route::post('properties_types/status', ['as' => 'properties_types.status', 'middleware' => ['permission:admin.properties_types.status'], 'uses' => 'PropertiesTypesController@postStatus']);
    // Series ROUTE
    Route::get('cities', ['as' => 'cities.view', 'middleware' => ['permission:admin.cities.view'], 'uses' => 'CitiesController@getIndex']);
    Route::get('cities/list', ['as' => 'cities.list', 'middleware' => ['permission:admin.cities.view'], 'uses' => 'CitiesController@getList']);
    Route::get('cities/add', ['as' => 'cities.add', 'middleware' => ['permission:admin.cities.add'], 'uses' => 'CitiesController@getAdd']);
    Route::post('cities/add', ['as' => 'cities.add', 'middleware' => ['permission:admin.cities.add'], 'uses' => 'CitiesController@postAdd']);
    Route::get('cities/edit/{id}', ['as' => 'cities.edit', 'middleware' => ['permission:admin.cities.edit'], 'uses' => 'CitiesController@getEdit']);
    Route::post('cities/edit/{id}', ['as' => 'cities.edit', 'middleware' => ['permission:admin.cities.edit'], 'uses' => 'CitiesController@postEdit']);
    Route::post('cities/delete', ['as' => 'cities.delete', 'middleware' => ['permission:admin.cities.delete'], 'uses' => 'CitiesController@postDelete']);
    Route::post('cities/status', ['as' => 'cities.status', 'middleware' => ['permission:admin.cities.status'], 'uses' => 'CitiesController@postStatus']);

    // products ROUTE
    Route::get('properties', ['as' => 'properties.view', 'middleware' => ['permission:admin.properties.view|admin.properties.add|admin.properties.edit|admin.properties.delete|admin.properties.status'], 'uses' => 'PropertiesController@getIndex']);
    Route::get('properties/list', ['as' => 'properties.list', 'middleware' => ['permission:admin.properties.view|admin.properties.add|admin.properties.edit|admin.properties.delete|admin.properties.status'], 'uses' => 'PropertiesController@getList']);
    Route::get('properties/add', ['as' => 'properties.add', 'middleware' => ['permission:admin.properties.add'], 'uses' => 'PropertiesController@getAdd']);
    Route::post('properties/add', ['as' => 'properties.add', 'middleware' => ['permission:admin.properties.add'], 'uses' => 'PropertiesController@postAdd']);
    Route::get('properties/edit/{id}', ['as' => 'properties.edit', 'middleware' => ['permission:admin.properties.edit'], 'uses' => 'PropertiesController@getEdit']);
    Route::post('properties/edit/{id}', ['as' => 'properties.edit', 'middleware' => ['permission:admin.properties.edit'], 'uses' => 'PropertiesController@postEdit']);
    Route::post('properties/delete', ['as' => 'properties.delete', 'middleware' => ['permission:admin.properties.delete'], 'uses' => 'PropertiesController@postDelete']);
    Route::post('properties/status', ['as' => 'properties.status', 'middleware' => ['permission:admin.properties.status'], 'uses' => 'PropertiesController@postStatus']);
    Route::get('properties/images/{id}', ['as' => 'properties.gallery', 'middleware' => ['permission:admin.properties.add'], 'uses' => 'PropertiesController@getGallery']);
    Route::post('upload_image/{id}', ['as' => 'admin.properties.storeMedia', 'uses' => 'PropertiesController@getImage']);
    Route::post('delete_image/{id}', ['as' => 'admin.properties.deleteMedia', 'uses' => 'PropertiesController@deleteImage']);
    Route::post('view_image/{id}', ['as' => 'admin.properties.viewMedia', 'uses' => 'PropertiesController@getImages']);
    //Testimonials Route
    Route::get('testimonials', ['as' => 'testimonials.view', 'middleware' => ['permission:admin.testimonials.view'], 'uses' => 'TestimonialsController@getIndex']);
    Route::get('testimonials/list', ['as' => 'testimonials.list', 'middleware' => ['permission:admin.testimonials.view|admin.testimonials.add|admin.testimonials.edit|admin.testimonials.delete|admin.testimonials.status'], 'uses' => 'TestimonialsController@getList']);
    Route::get('testimonials/add', ['as' => 'testimonials.add', 'middleware' => ['permission:admin.testimonials.add'], 'uses' => 'TestimonialsController@getAdd']);
    Route::post('testimonials/add', ['as' => 'testimonials.add', 'middleware' => ['permission:admin.testimonials.add'], 'uses' => 'TestimonialsController@postAdd']);
    Route::get('testimonials/edit/{id}', ['as' => 'testimonials.edit', 'middleware' => ['permission:admin.testimonials.edit'], 'uses' => 'TestimonialsController@getEdit']);
    Route::post('testimonials/edit/{id}', ['as' => 'testimonials.edit', 'middleware' => ['permission:admin.testimonials.edit'], 'uses' => 'TestimonialsController@postEdit']);
    Route::post('testimonials/delete', ['as' => 'testimonials.delete', 'middleware' => ['permission:admin.testimonials.delete'], 'uses' => 'TestimonialsController@postDelete']);
    Route::post('testimonials/status', ['as' => 'testimonials.status', 'middleware' => ['permission:admin.testimonials.status'], 'uses' => 'TestimonialsController@postStatus']);
    //Partners Route
    Route::get('partners', ['as' => 'partners.view', 'middleware' => ['permission:admin.partners.view|admin.partners.add|admin.partners.edit|admin.partners.delete|admin.partners.status'], 'uses' => 'PartnersController@getIndex']);
    Route::get('partners/list', ['as' => 'partners.list', 'middleware' => ['permission:admin.partners.view|admin.partners.add|admin.partners.edit|admin.partners.delete|admin.partners.status'], 'uses' => 'PartnersController@getList']);
    Route::get('partners/add', ['as' => 'partners.add', 'middleware' => ['permission:admin.partners.add'], 'uses' => 'PartnersController@getAdd']);
    Route::post('partners/add', ['as' => 'partners.add', 'middleware' => ['permission:admin.partners.add'], 'uses' => 'PartnersController@postAdd']);
    Route::get('partners/edit/{id}', ['as' => 'partners.edit', 'middleware' => ['permission:admin.partners.edit'], 'uses' => 'PartnersController@getEdit']);
    Route::post('partners/edit/{id}', ['as' => 'partners.edit', 'middleware' => ['permission:admin.partners.edit'], 'uses' => 'PartnersController@postEdit']);
    Route::post('partners/delete', ['as' => 'partners.delete', 'middleware' => ['permission:admin.partners.delete'], 'uses' => 'PartnersController@postDelete']);
    Route::post('partners/status', ['as' => 'partners.status', 'middleware' => ['permission:admin.partners.status'], 'uses' => 'PartnersController@postStatus']);
    // faq ROUTE
    Route::get('faq', ['as' => 'faq.view', 'middleware' => ['permission:admin.faq.view|admin.faq.add|admin.faq.edit|admin.faq.delete|admin.faq.status'], 'uses' => 'FaqController@getIndex']);
    Route::get('faq/list', ['as' => 'faq.list', 'middleware' => ['permission:admin.faq.view|admin.faq.add|admin.faq.edit|admin.faq.delete|admin.faq.status'], 'uses' => 'FaqController@getList']);
    Route::get('faq/add', ['as' => 'faq.add', 'middleware' => ['permission:admin.faq.add'], 'uses' => 'FaqController@getAdd']);
    Route::post('faq/add', ['as' => 'faq.add', 'middleware' => ['permission:admin.faq.add'], 'uses' => 'FaqController@postAdd']);
    Route::get('faq/edit/{id}', ['as' => 'faq.edit', 'middleware' => ['permission:admin.faq.edit'], 'uses' => 'FaqController@getEdit']);
    Route::post('faq/edit/{id}', ['as' => 'faq.edit', 'middleware' => ['permission:admin.faq.edit'], 'uses' => 'FaqController@postEdit']);
    Route::post('faq/delete', ['as' => 'faq.delete', 'middleware' => ['permission:admin.faq.delete'], 'uses' => 'FaqController@postDelete']);
    Route::post('faq/status', ['as' => 'faq.status', 'middleware' => ['permission:admin.faq.status'], 'uses' => 'FaqController@postStatus']);

    // log out
    Route::get('logout', ['as' => 'app.logout', 'uses' => 'LoginController@getLogout']);
});
