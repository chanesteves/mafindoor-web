<?php

use Illuminate\Http\Request;

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

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

Route::match(['post', 'get', 'options'], 'auth/ajaxLogin', 'Auth\LoginController@ajaxLogin')->middleware('auth.api','cors');
Route::match(['post', 'get', 'options'], 'auth/ajaxRegister', 'Auth\RegisterController@ajaxRegister')->middleware('auth.api','cors');

Route::match(['post', 'get', 'options'], 'buildings/{building}/ajaxShow', 'BuildingsController@ajaxShow')->middleware('auth.api','cors');
Route::match(['post', 'get', 'options'], 'buildings/ajaxShowBuildings', 'BuildingsController@ajaxShowBuildings')->middleware('auth.api','cors');

Route::match(['post', 'get', 'options'], 'userSubCategorySearches/ajaxStore', 'UserSubCategorySearchesController@ajaxStore')->middleware('auth.api','cors');

Route::match(['post', 'get', 'options'], 'users/ajaxShowUser', 'UsersController@ajaxShowUser')->middleware('auth.api','cors');
Route::match(['post', 'get', 'options'], 'users/{user}/ajaxUpdate', 'UsersController@ajaxUpdate')->middleware('auth.api','cors');
Route::match(['post', 'get', 'options'], 'users/{user}/ajaxDestroy', 'UsersController@ajaxDestroy')->middleware('auth.api','cors');
Route::match(['post', 'get', 'options'], 'users/{user}/ajaxUnlockBuilding', 'UsersController@ajaxUnlockBuilding')->middleware('auth.api','cors');

Route::match(['post', 'get', 'options'], 'people/{person}/ajaxUploadImage', 'PeopleController@ajaxUploadImage')->middleware('auth.api','cors');
Route::match(['post', 'get', 'options'], 'people/{person}/ajaxUpdate', 'PeopleController@ajaxUpdate')->middleware('auth.api','cors');

Route::match(['post', 'get', 'options'], 'activities/ajaxStore', 'ActivitiesController@ajaxStore')->middleware('auth.api','cors');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
