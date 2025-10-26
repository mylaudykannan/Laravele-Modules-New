<?php
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'can:Menu']], function () {
    Route::prefix('menu')->group(function () {
        Route::get('/', [App\Modules\Menu\Controller\MenuController::class, 'create']);
        Route::post('/store', [App\Modules\Menu\Controller\MenuController::class, 'store'])->name('menu.store');
    });
});