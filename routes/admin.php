<?php
Route::get('test', 'IndexController@index');
Route::post('login', 'AuthController@login');
//Route::get('me', 'AuthController@me');
Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('me', 'AuthController@me');
});
