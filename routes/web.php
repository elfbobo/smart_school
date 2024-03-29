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

        Route::post('get-region', 'AjaxController@getRegion');

        //选择app应用列表展示
        Route::get('get-app-list', 'DesktopManageController@getApplist');

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
                //部门类别
                Route::post('dept-cate/sortable', 'DeptCategoryController@sortable')->name('dept-cate.sortable');
                Route::resource('dept-cate', 'DeptCategoryController');
                //部门办别
                Route::post('dept-bb/sortable', 'DeptBBController@sortable')->name('dept-bb.sortable');
                Route::resource('dept-bb', 'DeptBBController');

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
                Route::get('app_manage/auth', 'AppManageController@auth')->name('app_manage.auth');
                Route::post('app_manage/auth', 'AppManageController@auth')->name('app_manage.auth');
                Route::resource('app_manage', 'AppManageController');
                Route::delete('app_manage/delete', 'AppManageController@destroy')->name('app_manage.destroy');

                //服务类别
                Route::post('service_type/save-node', 'ServiceTypeController@saveNode')->name('service_type.save-node');
                Route::resource('service_type', 'ServiceTypeController');

                //桌面模板管理
                Route::post('desktop_manage/disp-order', 'DesktopManageController@dispOrder')->name('desktop_manage.disp-order');
                Route::resource('desktop_manage', 'DesktopManageController');

                //应用文件夹管理
                Route::resource('app_folder_manage', 'AppFolderManageController');
            });

            //基础数据
            Route::group(['prefix' => 'base'], function () {
                //教师基本信息
                Route::match(['get', 'post'], 'teacher/import', 'TeacherController@import')->name('teacher.import');
                Route::resource('teacher', 'TeacherController');
                Route::delete('teacher/delete', 'TeacherController@destroy')->name('teacher.destroy');

                //学生基本信息
                Route::match(['get', 'post'], 'student/import', 'StudentController@import')->name('student.import');
                Route::resource('student', 'StudentController');
                Route::delete('student/delete', 'StudentController@destroy')->name('student.destroy');

                //专业管理
                Route::match(['get', 'post'], 'professional/import', 'ProfessionalController@import')->name('professional.import');
                Route::resource('professional', 'ProfessionalController');
                Route::delete('professional/delete', 'ProfessionalController@destroy')->name('professional.destroy');

                //年度专业管理
                Route::match(['get', 'post'], 'year-professional/import', 'YearProfessionalController@import')
                    ->name('year-professional.import');
                Route::resource('year-professional', 'YearProfessionalController');
                Route::delete('year-professional/delete', 'YearProfessionalController@destroy')->name('year-professional.destroy');

                //班级管理
                Route::match(['get', 'post'], 'class/import', 'ClassController@import')->name('class.import');
                Route::resource('class', 'ClassController');

                Route::delete('class/delete', 'ClassController@destroy')->name('class.destroy');
            });
        });
    });
});