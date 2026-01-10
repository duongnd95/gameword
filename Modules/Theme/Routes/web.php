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

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    Route::resource('sliders', 'SliderController');

});

Route::get('/', 'FrontendController@index');
Route::get('/trang-chu', 'FrontendController@index');
//Sitemap.xml
Route::get('/sitemap.xml', 'SiteMapController@index');

//Tìm kiếm
Route::get('/tim-kiem', 'FrontendController@search')->name('tim-kiem');

Route::get('/{slug}.html', 'FrontendController@getPage')->name('slug.getPage');

Route::group(['prefix' => '{slugParent}'], function () {
    Route::get('/', 'FrontendController@getListParents')->name('slugParent.getListParents');
    Route::get('/{slugDetail}.html', 'FrontendController@getDetail')->name('slugDetail.getDetail');
    Route::get('/{slugChild}', 'FrontendController@getListChild')->name('slugChild.getListChild');
});
Route::post('/lien-he/ajax', 'AjaxFrontendController@postContact');
Route::post('/review/ajax', 'AjaxFrontendController@review');
Route::post('/newsletters/ajax', 'AjaxFrontendController@postNewsletter');
Route::get('/review/ajax/reviewAjax', 'AjaxFrontendController@ajaxPagination');
Route::get('/product/ajax/filter', 'AjaxFrontendController@filterProduct');
