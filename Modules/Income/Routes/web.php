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

// Income
Route::group(['namespace' => '\Modules\Income\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'incomes';
    $controller_name = 'IncomeController';
    Route::get("$module_name/getdata/{id}", ['as' => "$module_name.getdata", 'uses' => "$controller_name@getdata"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/detail", ['as' => "$module_name.detail", 'uses' => "$controller_name@detail"]);
    Route::get("$module_name/detail_list", ['as' => "$module_name.detail_list", 'uses' => "$controller_name@detail_list"]);

    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::post("$module_name/storeloadinfile", ['as' => "$module_name.storeloadinfile", 'uses' => "$controller_name@storeloadinfile"]);
    Route::resource("$module_name", "$controller_name");
});