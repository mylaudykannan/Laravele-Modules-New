<?php
//permission
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['web', 'role:Admin']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [App\Modules\User\Controller\Admin\User\UserController::class, 'index']);
            Route::get('/view/{user}', [App\Modules\User\Controller\Admin\User\UserController::class, 'show']);
            Route::get('/assignpermission/{user}', [App\Modules\User\Controller\Admin\User\UserController::class, 'assignpermission']);
            Route::get('/assignpermissionadd/{user}/{permission}', [App\Modules\User\Controller\Admin\User\UserController::class, 'assignpermissionadd']);
            Route::get('/assignpermissionrevoke/{user}/{permission}', [App\Modules\User\Controller\Admin\User\UserController::class, 'assignpermissionrevoke']);

            Route::get('/assignrole/{user}', [App\Modules\User\Controller\Admin\User\UserController::class, 'assignrole']);
            Route::get('/assignroleadd/{user}/{role}', [App\Modules\User\Controller\Admin\User\UserController::class, 'assignroleadd']);
            Route::get('/assignrolerevoke/{user}/{role}', [App\Modules\User\Controller\Admin\User\UserController::class, 'assignrolerevoke']);
        });
        Route::prefix('role')->group(function () {
            Route::get('/', [App\Modules\User\Controller\Admin\User\RoleController::class, 'index']);
            Route::get('/create', [App\Modules\User\Controller\Admin\User\RoleController::class, 'create']);
            Route::get('/assignpermission/{role}', [App\Modules\User\Controller\Admin\User\RoleController::class, 'assignpermission']);
            Route::get('/assignpermissionadd/{role}/{permission}', [App\Modules\User\Controller\Admin\User\RoleController::class, 'assignpermissionadd']);
            Route::get('/assignpermissionrevoke/{role}/{permission}', [App\Modules\User\Controller\Admin\User\RoleController::class, 'assignpermissionrevoke']);
            Route::post('/', [App\Modules\User\Controller\Admin\User\RoleController::class, 'store']);
            Route::get('/delete/{id}', [App\Modules\User\Controller\Admin\User\RoleController::class, 'destroy']);
        });
        Route::prefix('permission')->group(function () {
            Route::get('/', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'index']);
            Route::get('/create', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'create']);
            Route::get('/assignrole/{permission}', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'assignrole']);
            Route::get('/assignroleadd/{permission}/{role}', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'assignroleadd']);
            Route::get('/assignrolerevoke/{permission}/{role}', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'assignrolerevoke']);
            Route::post('/', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'store']);
            Route::get('/delete/{id}', [App\Modules\User\Controller\Admin\User\PermissionController::class, 'destroy']);
        });
    });
});