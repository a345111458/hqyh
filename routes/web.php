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

Route::namespace('Zbsht')->prefix('zbsht')->group(function(){
    // 用户登陆
    Route::get('index','LoginController@index')->name('login.index');
    Route::post('land','LoginController@land')->name('login.land');
    Route::post('logout','LoginController@logout')->name('login.logout');

    // 后台页面
    Route::middleware('auth')->group(function(){
        // 后面首页
        Route::get('stroe','IndexController@index')->name('index.index');

        // 菜单页面
        Route::get('menu/index','MenuController@store')->name('menu.index');
        Route::post('menu/menudata','MenuController@getMenuResponse')->name('menu.menudata');
        Route::get('menu/show','MenuController@show')->name('menu.show');
        Route::post('menu/addMenu','MenuController@addMenu')->name('menu.addMenu');
        Route::get('menu/{menu}','MenuController@edit')->name('menu.edit');
        Route::patch('menu/{menu}','MenuController@update')->name('menu.update');

        // 用户管理
        Route::get('member/{param}/notExamile' , 'MemberController@isNotExamile')->name('member.param.notExamile');
        Route::post('member/{param}/notExamile' , 'MemberController@isNotExamile')->name('member.param.notExamile');
        Route::get('member/index' , 'MemberController@index')->name('member.index');
        Route::get('member/create' , 'MemberController@create')->name('member.create');
        Route::post('member/store' , 'MemberController@store')->name('member.store');
        Route::post('member/userIndex' , 'MemberController@userIndex')->name('member.userIndex');
        Route::get('member/{user}' , 'MemberController@edit')->name('member.edit');
        Route::patch('member/{user}' , 'MemberController@update')->name('member.update');
        Route::delete('member/{user}' , 'MemberController@destroy')->name('member.destroy');
        

        // 配置路由
        Route::get('config/userIndex','ConfigController@userIndex')->name('config.userIndex');
        Route::resource('config','ConfigController');

        // 提成路由
        Route::get('bonus/userIndex','BonusController@userIndex')->name('bonus.userIndex');
        Route::resource('bonus','BonusController');






    });










});

//Route::namespace('Zbsht')->prefix('zbsht')->group(function(){

//
//
//    });
//
//
//
//
//
//
//
//});