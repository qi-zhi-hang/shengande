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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post("login",'v1\AdminUser@login');


Route::group([
    'middleware'=>'check_login'
],function (){
    Route::post("add_admin",'v1\AdminUser@addAdmin');
    Route::post("admin_list",'v1\AdminUser@getUserList');
    Route::post("admin_info",'v1\AdminUser@viewAdminUser');
    Route::post("del_info",'v1\AdminUser@delAccount');
});

