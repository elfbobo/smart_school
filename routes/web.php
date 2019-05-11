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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::group(['namespace' => 'Admin'], function() {
    Route::get('login', 'LoginController@index')->name('admin.login');
    Route::post('login', 'LoginController@index');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'checklogin'], function () {
        Route::get('cache-clear', function() {
           \Illuminate\Support\Facades\Cache::flush();
           return 1;
        })->name('cache-clear');
        Route::get('download-file', function ($file = null) {
            if ($file == null) {
                $file = request()->get('file');
            }
            return response()->download(public_path('download') . '/' . $file);
        })->name('download.file');
        Route::post('save-dept-node', 'AjaxController@saveDeptNodes')->name('save.dept.node');
        Route::get('menu-node', 'AjaxController@getMenus')->name('menu.node');
        Route::get('data-statistics', 'AjaxController@getDataStatistics');
        Route::get('select-icons', function () {
            return view('admin.common.icons');
        });
        Route::get('get-team-group-option', 'AjaxController@getTeamGroupOption')->name('get-team-group-option');
        Route::match(['get', 'post'],'profile', 'ProfileController@index')->name('user.profile');

        //检查权限
        Route::group(['middleware' => 'checkauth'], function () {
            Route::get('/', 'IndexController@index')->name('home.index');
            //管理员管理
            Route::group(['prefix' => 'admin'], function () {
                //菜单管理
                Route::resource('menu', 'MenuController');

                //用户管理
                Route::match(['get', 'post'], 'user/import', 'UserController@import')->name('user.import');
                Route::resource('user', 'UserController');
                Route::delete('user/delete', 'UserController@destroy')->name('user.destroy');


                //部门管理
                Route::resource('department', 'DepartmentController');

                //角色管理
                Route::resource('role', 'RoleController');
                Route::delete('role/delete', 'RoleController@destroy')->name('role.destroy');
                Route::get('role-auth', 'RoleController@auth')->name('role.auth');
                Route::post('role-auth', 'RoleController@auth')->name('role.auth');
            });

            //系统管理
            Route::group(['prefix' => 'sys'], function () {
                //系统设置
                Route::resource('setting', 'SettingController');

                //日志管理
                Route::resource('log', 'LogController');
            });

            //应用管理
            Route::group(['prefix' => 'app'], function () {
                //应用管理
                Route::resource('app_manage', 'AppManageController');
                Route::delete('app_manage/delete', 'AppManageController@destroy')->name('app_manage.destroy');

                //服务类别
                Route::post('service_type/save-node', 'ServiceTypeController@saveNode')->name('service_type.save-node');
                Route::resource('service_type', 'ServiceTypeController');
            });
        });
    });
});