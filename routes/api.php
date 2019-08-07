<?php

Route::post('login', 'AuthController@login');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me', 'AuthController@me');
});
