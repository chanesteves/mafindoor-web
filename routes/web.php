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

Route::post('buildings/ajaxShowBuildings', 'BuildingsController@ajaxShowBuildings');
Route::post('buildings/ajaxStore', 'BuildingsController@ajaxStore');
Route::post('buildings/{building}/ajaxUpdate', 'BuildingsController@ajaxUpdate');
Route::post('buildings/{building}/ajaxDestroy', 'BuildingsController@ajaxDestroy');
Route::post('buildings/{building}/ajaxShow', 'BuildingsController@ajaxShow');
Route::post('buildings/{building}/ajaxUploadLogo', 'BuildingsController@ajaxUploadLogo');
Route::post('buildings/ajaxImportBuildings', 'BuildingsController@ajaxImportBuildings');
Route::post('floors/ajaxStore', 'FloorsController@ajaxStore');
Route::post('floors/{floor}/ajaxUpdate', 'FloorsController@ajaxUpdate');
Route::post('floors/{floor}/ajaxDestroy', 'FloorsController@ajaxDestroy');
Route::post('floors/{floor}/ajaxShow', 'FloorsController@ajaxShow');
Route::post('floors/ajaxImportFloors', 'FloorsController@ajaxImportFloors');
Route::post('annotations/ajaxStore', 'AnnotationsController@ajaxStore');
Route::post('annotations/{annotation}/ajaxUpdate', 'AnnotationsController@ajaxUpdate');
Route::post('annotations/{annotations}/ajaxDestroy', 'AnnotationsController@ajaxDestroy');
Route::post('annotations/{annotations}/ajaxShow', 'AnnotationsController@ajaxShow');
Route::post('annotations/{annotations}/ajaxUploadLogo', 'AnnotationsController@ajaxUploadLogo');
Route::post('annotations/ajaxImportAnnotations', 'AnnotationsController@ajaxImportAnnotations');
Route::post('categories/ajaxStore', 'CategoriesController@ajaxStore');
Route::post('categories/{category}/ajaxShow', 'CategoriesController@ajaxShow');
Route::post('categories/{category}/ajaxUpdate', 'CategoriesController@ajaxUpdate');
Route::post('categories/{category}/ajaxDestroy', 'CategoriesController@ajaxDestroy');
Route::post('categories/{category}/ajaxUploadLogo', 'CategoriesController@ajaxUploadLogo');
Route::post('subCategories/ajaxStore', 'SubCategoriesController@ajaxStore');
Route::post('subCategories/{subCategory}/ajaxShow', 'SubCategoriesController@ajaxShow');
Route::post('subCategories/{subCategory}/ajaxUpdate', 'SubCategoriesController@ajaxUpdate');
Route::post('subCategories/{subCategory}/ajaxDestroy', 'SubCategoriesController@ajaxDestroy');
Route::post('subCategories/{subCategory}/ajaxUploadLogo', 'SubCategoriesController@ajaxUploadLogo');
Route::post('userSubCategorySearches/ajaxStore', 'UserSubCategorySearchesController@ajaxStore');

Route::get('buildings/exportBuildings', 'BuildingsController@exportBuildings');
Route::get('floors/exportFloors', 'FloorsController@exportFloors');
Route::get('annotations/exportAnnotations', 'AnnotationsController@exportAnnotations');

Auth::routes();

// directories
Route::get('venues', 'PagesController@venues');
Route::get('floors', 'PagesController@floors');
Route::get('annotations', 'PagesController@annotations');
Route::get('categories', 'PagesController@categories');

Route::get('auth/register', 'Auth\RegisterController@getRegister');
Route::get('auth/login', 'Auth\LoginController@getLogin');
Route::get('auth/logout', 'Auth\LoginController@getLogout');
Route::get('auth/verifyEmail', 'Auth\VerifyController@getVerifyEmail');

Route::post('auth/ajaxRegister', 'Auth\RegisterController@ajaxRegister');
Route::post('auth/login', 'Auth\LoginController@postLogin');

Route::get('auth/facebook', 'Auth\LoginController@redirectToFacebook');
Route::get('facebook/callback', 'Auth\LoginController@handleFacebookCallback');