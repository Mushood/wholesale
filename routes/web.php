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

Route::get('category/publish/{id}', 'CategoryController@publish')->name('category.publish');
Route::get('category/unpublish/{id}', 'CategoryController@unpublish')->name('category.unpublish');
Route::resource('category', 'CategoryController');

Route::resource('temporaryUpload', 'TemporaryUploadController');


//For testing purposes only
Route::get('unknown/publish/{id}', 'BlogController@publish')->name('unknown.publish');
