<?php

Route::get('/clear', function () {
   \Artisan::call('storage:link');
//   \Artisan::call('optimize');

   return "Cache cleared successfully";
});

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function (){
	Route::resource('news', 'Admin\NewsController');
    Route::resource('categories', 'Admin\CategoryController');
    Route::resource('pages', 'Admin\PageController');
	Route::resource('menus', 'Admin\SysMenuController');
	Route::resource('newsletters', 'Admin\NewsletterController');
	Route::resource('contacts', 'Admin\ContactController');
});



Route::group(['middleware' => 'auth'], function () {

	Route::get('/', 'HomeController@index')->name('admin');
	Route::get('/admin', 'HomeController@index')->name('admin');

	Route::resource('admin/roles', 'Admin\RolesController');
	Route::resource('admin/permissions', 'Admin\PermissionsController');

	Route::resource('admin/users', 'Admin\UsersController');

	Route::get('profile', 'Admin\UsersController@getProfile');
	Route::post('profile', 'Admin\UsersController@postProfile');


	Route::get('settings/modules', 'ModulesController@index');
	Route::put('settings/modules/{module}', 'ModulesController@active');

	Route::resource('cities', 'CitiesController');
	Route::resource('agents', 'AgentsController');

	Route::get('company-settings', 'CompanySettingsController@edit');
	Route::patch('company-settings', 'CompanySettingsController@update');

    Route::get('companies-profile','CompanySettingsController@getProfile');
    Route::post('companies-profile','CompanySettingsController@postProfile');
    Route::get('agents-profile','AgentsController@getProfile');
    Route::post('agents-profile','AgentsController@postProfile');

	Route::get('ajax/{action}', 'AjaxController@index');


    Route::get('admin/settings', 'SettingController@index');
    Route::patch('admin/settings', 'SettingController@update');

});