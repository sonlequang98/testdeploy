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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/posts/search','PostController@getSearchResult')->name('get-result-search');
Route::get('/posts/{id}/show','PostController@getPostById')->name('get-post-by-id');
Route::get('/posts/{categoryid}','PostController@getPostsByCategoryId')->name('get-posts-by-category-id');
Route::get('/posts','PostController@index')->name('get-list-post');
