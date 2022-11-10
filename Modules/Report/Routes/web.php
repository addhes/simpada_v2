<?php

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/

// Report
Route::group(['namespace' => '\Modules\Report\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'reports';
    $controller_name = 'ReportController';
    Route::get("$module_name/download/{id}", ['as' => "$module_name.download", 'uses' => "$controller_name@download"]);
    Route::get("$module_name/accountability/{id}", ['as' => "$module_name.accountability", 'uses' => "$controller_name@accountability"]);
    Route::get("$module_name/export_excel", ['as' => "$module_name.export_excel", 'uses' => "$controller_name@export_excel"]);

    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::post("$module_name/storeloadinfile", ['as' => "$module_name.storeloadinfile", 'uses' => "$controller_name@storeloadinfile"]);
    Route::resource("$module_name", "$controller_name");
});