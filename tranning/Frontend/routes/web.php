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

Route::get('/', 'UserSideController@index')->name('index');
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register')->name('register');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::get('/users', 'UserController@getUser')->name('user-profile');
Route::put('/users/{id}', 'UserController@update')->name('update-user');
Route::get('/posts/search', 'PostController@search')->name('search-post');
Route::get('/categories/{id}', 'CategoryController@show')->name('get-posts-by-category-id');
Route::get('/posts/{id}', 'PostController@show')->name('show-post');
