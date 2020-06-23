<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::get('/images/{id}/uploadPath', 'ImageController@getUploadPath')->name('get-upload-path-by-id')->middleware('isUser');
Route::get('/release_numbers/list', 'ReleaseNumberController@getAll')->name('get-list-release-number')->middleware('isUser');
Route::put('/categories/parent/{id}', 'CategoryController@updateParent')->name('update-category-parent');
Route::put('/categories/childrent/{id}', 'CategoryController@updateChildrent')->name('update-category-child')->middleware('isUser');
Route::get('/categories/parent', 'CategoryController@getParentCategory')->name('get-list-category-parent')->middleware('isUser');
Route::get('/categories/childrent/{parent_id}', 'CategoryController@getChildCategory')->name('get-list-category-child')->middleware('isUser');
Route::get('/users/role', 'UserAdminController@getRoles')->name('get-list-role')->middleware('isUser');
Route::get('/userAdmins/search', 'UserAdminController@search')->name('search-user')->middleware('isUser');
Route::get('/posts/search', 'PostController@search')->name('search-post')->middleware('isUser');
Route::get('/members/search', 'MemberController@search')->name('search-member')->middleware('isUser');
Route::get('/images/search', 'ImageController@search')->name('search-image')->middleware('isUser');
Route::post('/images/deleteImage','ImageController@deleteLastImage')->name('delete-last-image-by-name')->middleware('isUser');;
Route::get('/categories/parents/childs','CategoryController@getCatParentHaveChild')->name('get-cat-parents-have-child')->middleware('isUser');
Route::get('/users/getCurrentRole','UserAdminController@getRoleId')->name('get-current-role');
Route::resource('images', 'ImageController');
Route::resource('/settings', 'SettingController')->middleware('isUser');
Route::resource('categories', 'CategoryController')->middleware('isUser');
Route::resource('release_numbers', 'ReleaseNumberController')->middleware('isUser');
Route::resource('posts', 'PostController')->middleware('isUser');
Route::resource('useradmins', 'UserAdminController')->middleware('isUser');
Route::resource('/members', 'MemberController')->middleware('isUser');
