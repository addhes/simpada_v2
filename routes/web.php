<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

// Autho Routes
require __DIR__.'/auth.php';

// Atom/ RSS Feed Routes
// Route::feeds();

// Language Switch
Route::get('language/{language}', 'LanguageController@switch')->name('language.switch');
/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    //Route cache:
    Route::get('/route-cache', function() {
        $exitCode = Artisan::call('route:cache');
        return '<h1>Routes cached</h1>';
    });
    
    //Storage Link:
    Route::get('/storage-link', function() {
        $exitCode = Artisan::call('storage:link');
        return '<h1>Storage Link Sukses</h1>';
    });

    //Up:
    Route::get('/up-system', function() {
        $exitCode = Artisan::call('up');
        return '<h1>Maintenance Mode Off</h1>';
    });
    
    //Down:
    Route::get('/down', function() {
        $exitCode = Artisan::call('down');
        return '<h1>Maintenance Mode On</h1>';
    });
    
    Route::get('/', [AuthenticatedSessionController::class, 'create'])
            ->middleware('guest')
            ->name('index');
});

/*
*
* Finance Routes
* These routes need finance-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'Backend', 'prefix' => 'finance', 'as' => 'finance.', 'middleware' => ['auth', 'can:view_finance']], function () {
    Route::get('/', 'FinanceController@index')->name('home');
    Route::get('dashboard', 'FinanceController@index')->name('dashboard');
    Route::get('index_list', 'FinanceController@index_list')->name('index_list');
    Route::get('index_list_needconfirm', 'FinanceController@index_list_needconfirm')->name('index_list_needconfirm');

    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UserController';
    Route::get("$module_name/profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
    Route::get("$module_name/profile/{id}/edit", ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
    Route::patch("$module_name/profile/{id}/edit", ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);
    Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
    Route::delete("$module_name/userProviderDestroy", ['as' => "$module_name.userProviderDestroy", 'uses' => "$controller_name@userProviderDestroy"]);
    Route::get("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePassword", 'uses' => "$controller_name@changeProfilePassword"]);
    Route::patch("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePasswordUpdate", 'uses' => "$controller_name@changeProfilePasswordUpdate"]);
    Route::get("$module_name/changePassword/{id}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
    Route::patch("$module_name/changePassword/{id}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
});

/*
*
* Director Routes
* These routes need director-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'Backend', 'prefix' => 'director', 'as' => 'director.', 'middleware' => ['auth', 'can:view_director']], function () {
    Route::get('/', 'DirectorController@index')->name('home');
    Route::get('dashboard', 'DirectorController@index')->name('dashboard');
    Route::get('index_list', 'DirectorController@index_list')->name('index_list');
    Route::get('index_list_needconfirm', 'DirectorController@index_list_needconfirm')->name('index_list_needconfirm');

    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UserController';
    Route::get("$module_name/profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
    Route::get("$module_name/profile/{id}/edit", ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
    Route::patch("$module_name/profile/{id}/edit", ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);
    Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
    Route::delete("$module_name/userProviderDestroy", ['as' => "$module_name.userProviderDestroy", 'uses' => "$controller_name@userProviderDestroy"]);
    Route::get("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePassword", 'uses' => "$controller_name@changeProfilePassword"]);
    Route::patch("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePasswordUpdate", 'uses' => "$controller_name@changeProfilePasswordUpdate"]);
    Route::get("$module_name/changePassword/{id}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
    Route::patch("$module_name/changePassword/{id}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
});

/*
*
* Employee Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'Backend', 'prefix' => 'employee', 'as' => 'employee.', 'middleware' => ['auth', 'can:view_employee']], function () {
    Route::get('/', 'EmployeeController@index')->name('home');
    Route::get('dashboard', 'EmployeeController@index')->name('dashboard');
    Route::get('index_list', 'EmployeeController@index_list')->name('index_list');
    Route::get('index_list_needconfirm', 'EmployeeController@index_list_needconfirm')->name('index_list_needconfirm');

    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UserController';
    Route::get("$module_name/profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
    Route::get("$module_name/profile/{id}/edit", ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
    Route::patch("$module_name/profile/{id}/edit", ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);
    Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
    Route::delete("$module_name/userProviderDestroy", ['as' => "$module_name.userProviderDestroy", 'uses' => "$controller_name@userProviderDestroy"]);
    Route::get("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePassword", 'uses' => "$controller_name@changeProfilePassword"]);
    Route::patch("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePasswordUpdate", 'uses' => "$controller_name@changeProfilePasswordUpdate"]);
    Route::get("$module_name/changePassword/{id}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
    Route::patch("$module_name/changePassword/{id}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
});

/*
*
* Label Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'Backend', 'prefix' => 'label', 'as' => 'label.', 'middleware' => ['auth', 'can:view_label']], function () {
    Route::get('/', 'ClientController@index')->name('home');
    Route::get('dashboard', 'ClientController@index')->name('dashboard');
    Route::get('cartdata', 'ClientController@cartdata')->name('cartdata');
   


    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UserController';
    Route::get("$module_name/profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
    Route::get("$module_name/profile/{id}/edit", ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
    Route::patch("$module_name/profile/{id}/edit", ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);
    Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
    Route::delete("$module_name/userProviderDestroy", ['as' => "$module_name.userProviderDestroy", 'uses' => "$controller_name@userProviderDestroy"]);
    Route::get("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePassword", 'uses' => "$controller_name@changeProfilePassword"]);
    Route::patch("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePasswordUpdate", 'uses' => "$controller_name@changeProfilePasswordUpdate"]);
    Route::get("$module_name/changePassword/{id}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
    Route::patch("$module_name/changePassword/{id}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
});
/*
*
* Backend Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['auth', 'can:view_backend']], function () {

    /**
     * Backend Dashboard
     * Namespaces indicate folder structure.
     */
    Route::get('/', 'BackendController@index')->name('home');
    Route::get('dashboard', 'BackendController@index')->name('dashboard');
    Route::get('cartdata', 'BackendController@cartdata')->name('cartdata');
    Route::get('index_list', 'BackendController@index_list')->name('index_list');
    Route::get('index_list_needconfirm', 'BackendController@index_list_needconfirm')->name('index_list_needconfirm');



    // *  Data Users Routes
    $module_name = 'data_user';
    $controller_name = 'DataUserController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/markAllAsRead", ['as' => "$module_name.markAllAsRead", 'uses' => "$controller_name@markAllAsRead"]);
    Route::delete("$module_name/deleteAll", ['as' => "$module_name.deleteAll", 'uses' => "$controller_name@deleteAll"]);
    Route::get("$module_name/{id}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);

    /*
     *
     *  Settings Routes
     *
     * ---------------------------------------------------------------------
     */
    Route::group(['middleware' => ['permission:edit_settings']], function () {
        $module_name = 'settings';
        $controller_name = 'SettingController';
        Route::get("$module_name", "$controller_name@index")->name("$module_name");
        Route::post("$module_name", "$controller_name@store")->name("$module_name.store");
    });

        /*
     *
     *  Parameters Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'parameters';
    $controller_name = 'ParameterController';
    Route::get("$module_name", "$controller_name@index")->name("$module_name");
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::post("$module_name", "$controller_name@store")->name("$module_name.store");
    Route::get("$module_name/create", ['as' => "$module_name.create", 'uses' => "$controller_name@create"]);
    Route::get("$module_name/edit/{id}", ['as' => "$module_name.edit", 'uses' => "$controller_name@edit"]);
    Route::get("$module_name/show/{id}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
    Route::patch("$module_name/update/{id}", ['as' => "$module_name.update", 'uses' => "$controller_name@update"]);
Route::delete("$module_name/destroy/{id}", ['as' => "$module_name.destroy", 'uses' => "$controller_name@destroy"]);

    /*
     *
     *  Notification Phone Number Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'notification_phones';
    $controller_name = 'NotificationPhoneController';
    Route::get("$module_name", "$controller_name@index")->name("$module_name");
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::post("$module_name", "$controller_name@store")->name("$module_name.store");
    Route::get("$module_name/create", ['as' => "$module_name.create", 'uses' => "$controller_name@create"]);
    Route::get("$module_name/edit/{id}", ['as' => "$module_name.edit", 'uses' => "$controller_name@edit"]);
    Route::get("$module_name/show/{id}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
    Route::delete("$module_name/destroy/{id}", ['as' => "$module_name.destroy", 'uses' => "$controller_name@destroy"]);
    Route::patch("$module_name/update/{id}", ['as' => "$module_name.update", 'uses' => "$controller_name@update"]);
    
    /*
    *
    *  Notification Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'notifications';
    $controller_name = 'NotificationsController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/markAllAsRead", ['as' => "$module_name.markAllAsRead", 'uses' => "$controller_name@markAllAsRead"]);
    Route::delete("$module_name/deleteAll", ['as' => "$module_name.deleteAll", 'uses' => "$controller_name@deleteAll"]);
    Route::get("$module_name/{id}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);

    /*
    *
    *  Backup Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'backups';
    $controller_name = 'BackupController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/create", ['as' => "$module_name.create", 'uses' => "$controller_name@create"]);
    Route::get("$module_name/download/{file_name}", ['as' => "$module_name.download", 'uses' => "$controller_name@download"]);
    Route::get("$module_name/delete/{file_name}", ['as' => "$module_name.delete", 'uses' => "$controller_name@delete"]);

    /*
    *
    *  Roles Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'roles';
    $controller_name = 'RolesController';
    Route::resource("$module_name", "$controller_name");

    /*
    *
    *  Labels Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'labels';
    $controller_name = 'LabelController';
    Route::get("$module_name/{id}", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/{id}/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/{id}/create", ['as' => "$module_name.create", 'uses' => "$controller_name@create"]);
    Route::patch("$module_name/{id}/store", ['as' => "$module_name.store", 'uses' => "$controller_name@store"]);
    Route::get("$module_name/{id}/edit", ['as' => "$module_name.edit", 'uses' => "$controller_name@edit"]);
    Route::patch("$module_name/{id}/update", ['as' => "$module_name.update", 'uses' => "$controller_name@update"]);
    Route::get("$module_name/{id}/destroy", ['as' => "$module_name.destroy", 'uses' => "$controller_name@destroy"]);
    
    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UserController';
    Route::get("$module_name/sendNotificationWA", ['as' => "$module_name.sendNotificationWA", 'uses' => "$controller_name@sendNotificationWA"]);

    Route::get("$module_name/profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
    Route::get("$module_name/profile/{id}/edit", ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
    Route::patch("$module_name/profile/{id}/edit", ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);

    Route::get("$module_name/profile/label/{id}", ['as' => "$module_name.label", 'uses' => "$controller_name@label"]);
    Route::patch("$module_name/profile/label/{id}", ['as' => "$module_name.labelUpdate", 'uses' => "$controller_name@labelUpdate"]);
    Route::get("$module_name/data_label", ['as' => "$module_name.data_label", 'uses' => "$controller_name@data_label"]);

    Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
    Route::delete("$module_name/userProviderDestroy", ['as' => "$module_name.userProviderDestroy", 'uses' => "$controller_name@userProviderDestroy"]);
    Route::get("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePassword", 'uses' => "$controller_name@changeProfilePassword"]);
    Route::patch("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePasswordUpdate", 'uses' => "$controller_name@changeProfilePasswordUpdate"]);
    Route::get("$module_name/changePassword/{id}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
    Route::patch("$module_name/changePassword/{id}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::resource("$module_name", "$controller_name");
    Route::patch("$module_name/{id}/block", ['as' => "$module_name.block", 'uses' => "$controller_name@block", 'middleware' => ['permission:block_users']]);
    Route::patch("$module_name/{id}/unblock", ['as' => "$module_name.unblock", 'uses' => "$controller_name@unblock", 'middleware' => ['permission:block_users']]);
});