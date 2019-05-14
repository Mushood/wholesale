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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('blog/publish/{blog}', 'BlogController@publish')->name('blog.publish');
Route::get('blog/unpublish/{blog}', 'BlogController@unpublish')->name('blog.unpublish');
Route::resource('blog', 'BlogController');

Route::get('brand/publish/{brand}', 'BrandController@publish')->name('brand.publish');
Route::get('brand/unpublish/{brand}', 'BrandController@unpublish')->name('brand.unpublish');
Route::resource('brand', 'BrandController');

Route::get('shop/publish/{shop}', 'ShopController@publish')->name('shop.publish');
Route::get('shop/unpublish/{shop}', 'ShopController@unpublish')->name('shop.unpublish');
Route::get('shop/{shop}/user/{user}', 'ShopController@assignUser')->name('shop.assign.user');
Route::resource('shop', 'ShopController');

Route::get('category/publish/{category}', 'CategoryController@publish')->name('category.publish');
Route::get('category/unpublish/{category}', 'CategoryController@unpublish')->name('category.unpublish');
Route::resource('category', 'CategoryController');

Route::resource('temporaryUpload', 'TemporaryUploadController');
