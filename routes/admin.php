<?php
Route::get('test', 'IndexController@test');
Route::post('login', 'AuthController@login');

Route::middleware([
])->group(function () {
    Route::resource('users', 'UserController')->names('admin.users');
    Route::resource('roles', 'RoleController')->names('admin.roles');
    Route::resource('permissions', 'PermissionController')->names('admin.permissions');
});
