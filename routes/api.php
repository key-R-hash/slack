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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("Registration","RegistrationController@Registration");
Route::post("login","SessionController@login");
Route::put("renew/{token}","SessionController@renew");
Route::post("logout/{token}","SessionController@logout");

Route::get("posts/{token}","PostController@posts");
Route::get("posts/{token}/{topic_post}","PostController@post");
Route::get("deletepost/{token}","PostController@posts");
// Route::middleware('myauth')->delete('posts/{id}', 'PostController@delete');

Route::post('posts', 'PostController@create');

