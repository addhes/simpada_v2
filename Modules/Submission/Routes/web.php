<?php

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/

// Submission
Route::group(['namespace' => '\Modules\Submission\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'submissions';
    $controller_name = 'SubmissionController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/detail", ['as' => "$module_name.detail", 'uses' => "$controller_name@detail"]);
    Route::get("$module_name/detail_list", ['as' => "$module_name.detail_list", 'uses' => "$controller_name@detail_list"]);


    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::post("$module_name/storeloadinfile", ['as' => "$module_name.storeloadinfile", 'uses' => "$controller_name@storeloadinfile"]);
    Route::resource("$module_name", "$controller_name");
});

// Accountability
Route::group(['namespace' => '\Modules\Submission\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'accountabilities';
    $controller_name = 'AccountabilityController';
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

// Approval Submission Finance
Route::group(['namespace' => '\Modules\Submission\Http\Controllers\Finance', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'approvalfinances';
    $controller_name = 'ApprovalSubmissionController';
    Route::get("$module_name/approval/{id}", ['as' => "$module_name.approval", 'uses' => "$controller_name@approval"]);
    Route::put("$module_name/approve/{id}", ['as' => "$module_name.approve", 'uses' => "$controller_name@approve"]);
    Route::get("$module_name/reject/{id}", ['as' => "$module_name.reject", 'uses' => "$controller_name@reject"]);
    Route::put("$module_name/rejecting/{id}", ['as' => "$module_name.rejecting", 'uses' => "$controller_name@rejecting"]);
    Route::get("$module_name/upload/{id}", ['as' => "$module_name.upload", 'uses' => "$controller_name@upload"]);
    Route::put("$module_name/uploading/{id}", ['as' => "$module_name.uploading", 'uses' => "$controller_name@uploading"]);

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

// Approval Submission Director
Route::group(['namespace' => '\Modules\Submission\Http\Controllers\Director', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'approvaldirectors';
    $controller_name = 'ApprovalSubmissionController';
    Route::get("$module_name/getdata/{id}", ['as' => "$module_name.getdata", 'uses' => "$controller_name@getdata"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/detail", ['as' => "$module_name.detail", 'uses' => "$controller_name@detail"]);
    Route::get("$module_name/detail_list", ['as' => "$module_name.detail_list", 'uses' => "$controller_name@detail_list"]);

    Route::get("$module_name/approval/{id}", ['as' => "$module_name.approval", 'uses' => "$controller_name@approval"]);
    Route::put("$module_name/approve/{id}", ['as' => "$module_name.approve", 'uses' => "$controller_name@approve"]);
    Route::get("$module_name/reject/{id}", ['as' => "$module_name.reject", 'uses' => "$controller_name@reject"]);
    Route::put("$module_name/rejecting/{id}", ['as' => "$module_name.rejecting", 'uses' => "$controller_name@rejecting"]);

    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::post("$module_name/storeloadinfile", ['as' => "$module_name.storeloadinfile", 'uses' => "$controller_name@storeloadinfile"]);
    Route::resource("$module_name", "$controller_name");
});

Route::group(['namespace' => '\Modules\Submission\Http\Controllers\Director', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'approvalurgents';
    $controller_name = 'UrgentSubmissionController';
    Route::get("$module_name/getdata/{id}", ['as' => "$module_name.getdata", 'uses' => "$controller_name@getdata"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/detail", ['as' => "$module_name.detail", 'uses' => "$controller_name@detail"]);
    Route::get("$module_name/detail_list", ['as' => "$module_name.detail_list", 'uses' => "$controller_name@detail_list"]);

    Route::get("$module_name/approval/{id}", ['as' => "$module_name.approval", 'uses' => "$controller_name@approval"]);
    Route::put("$module_name/approve/{id}", ['as' => "$module_name.approve", 'uses' => "$controller_name@approve"]);
    Route::get("$module_name/reject/{id}", ['as' => "$module_name.reject", 'uses' => "$controller_name@reject"]);
    Route::put("$module_name/rejecting/{id}", ['as' => "$module_name.rejecting", 'uses' => "$controller_name@rejecting"]);

    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::post("$module_name/storeloadinfile", ['as' => "$module_name.storeloadinfile", 'uses' => "$controller_name@storeloadinfile"]);
    Route::resource("$module_name", "$controller_name");
});

Route::group(['namespace' => '\Modules\Submission\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    $module_name = 'submission_operations';
    $controller_name = 'SubmissionOperationsController';
    Route::get("$module_name/getdata/{id}", ['as' => "$module_name.getdata", 'uses' => "$controller_name@getdata"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/detail", ['as' => "$module_name.detail", 'uses' => "$controller_name@detail"]);
    Route::get("$module_name/detail_list", ['as' => "$module_name.detail_list", 'uses' => "$controller_name@detail_list"]);


    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::get("$module_name/trashed_index_list", ['as' => "$module_name.trashed_index_list", 'uses' => "$controller_name@trashed_index_list"]);
    Route::delete("$module_name/forcedelete/{id}", ['as' => "$module_name.forcedelete", 'uses' => "$controller_name@forcedelete"]);
    Route::get("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::post("$module_name/storeloadinfile", ['as' => "$module_name.storeloadinfile", 'uses' => "$controller_name@storeloadinfile"]);
    Route::get("$module_name/getsubops", ['as' => "$module_name.getsubops", 'uses' => "$controller_name@getsubops"]);
    Route::resource("$module_name", "$controller_name");
});
