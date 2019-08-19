<?php

Route::post('login', 'AuthController@login');
Route::middleware([
    'auth:admin'
])->group(function () {
    Route::apiResource('users', 'UserController')->names('admin.users');
    Route::apiResource('roles', 'RoleController')->names('admin.roles');
    Route::apiResource('permissions', 'PermissionController')->names('admin.permissions');
});
