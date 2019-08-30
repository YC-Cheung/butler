<?php

Route::post('auth/login', 'AuthController@login');
Route::post('auth/logout', 'AuthController@logout');
Route::middleware([
    'auth:admin'
])->group(function () {
    Route::apiResource('users', 'UserController')->names('admin.users');
    Route::apiResource('roles', 'RoleController')->names('admin.roles');
    Route::apiResource('permissions', 'PermissionController')->names('admin.permissions');
    Route::apiResource('menu', 'MenuController')->names('admin.menu');
    Route::get('auth/info', 'AuthController@userInfo')->name('admin.user.info');
    Route::get('role-options', 'RoleController@getOption')->name('admin.roles.option');
});
