<?php

Route::post('auth/login', 'AuthController@login');
Route::post('auth/logout', 'AuthController@logout');
Route::middleware([
    'auth:admin'
])->group(function () {
    Route::apiResource('users', 'UserController')->names('admin.users');
    Route::apiResource('roles', 'RoleController')->names('admin.roles');
    Route::apiResource('perms', 'PermissionController')->names('admin.perms');
    Route::apiResource('menu', 'MenuController')->names('admin.menu');
    Route::get('auth/info', 'AuthController@userInfo')->name('admin.user.info');
    Route::get('role-options', 'RoleController@getOption')->name('admin.roles.option');
    Route::get('perm-options', 'PermissionController@getOption')->name('admin.perms.option');
});
