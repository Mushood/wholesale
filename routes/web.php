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

Route::get('blog/publish/{id}', 'BlogController@publish')->name('blog.publish');
Route::get('blog/unpublish/{id}', 'BlogController@unpublish')->name('blog.unpublish');
Route::resource('blog', 'BlogController');


//For testing purposes only
Route::get('unknown/publish/{id}', 'BlogController@publish')->name('unknown.publish');