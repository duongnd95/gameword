<?php

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

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function (){
    Route::resource('products', 'ProductController');
    Route::resource('reviews', 'ReviewController');
    Route::resource('group-products', 'GroupController');
    Route::resource('attribute-products', 'AttributeController');
    Route::resource('provider-products', 'ProviderController');
    Route::resource('category-products', 'CategoryProductController');
    Route::post('/gallery-product/{id}', 'AjaxProductController@deleteGallery')->name('gallery-product');
});