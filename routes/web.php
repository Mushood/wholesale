<?php

Route::get('/', 'PageController@welcome')->name('welcome');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('blog/category/{slug}', 'BlogController@category')->name('blog.category');
Route::get('blog/publish/{blog}', 'BlogController@publish')->name('blog.publish');
Route::get('blog/unpublish/{blog}', 'BlogController@unpublish')->name('blog.unpublish');
Route::get('blog/title/{slug}', 'BlogController@showSlug')->name('blog.show.slug');
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

Route::get('product/publish/{product}', 'ProductController@publish')->name('product.publish');
Route::get('product/unpublish/{product}', 'ProductController@unpublish')->name('product.unpublish');
Route::resource('product', 'ProductController');

Route::resource('temporaryUpload', 'TemporaryUploadController');

Route::get('cart', 'ShoppingController@index')->name('cart.index');
Route::get('cart/add/{product}', 'ShoppingController@add')->name('cart.add');
Route::get('cart/set/{product}/{quantity}', 'ShoppingController@set')->name('cart.set');
Route::get('cart/update/{product}/{quantity}', 'ShoppingController@update')->name('cart.update');
Route::get('cart/save', 'ShoppingController@save')->name('cart.save')->middleware('auth');