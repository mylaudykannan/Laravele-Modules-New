<?php
//design
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'can:Design']], function () {
    Route::prefix('design')->group(function () {
        Route::get('/', [App\Modules\Design\Controller\DesignController::class, 'index']);
        Route::get('/add', [App\Modules\Design\Controller\DesignController::class, 'create']);
        Route::post('/store', [App\Modules\Design\Controller\DesignController::class, 'store']);
        Route::post('/update', [App\Modules\Design\Controller\DesignController::class, 'update']);
        Route::get('/edit/{pointslug}', [App\Modules\Design\Controller\DesignController::class, 'edit']);
        Route::get('/delete/{id}', [App\Modules\Design\Controller\DesignController::class, 'destroy']);
        Route::get('/load', [App\Modules\Design\Controller\DesignController::class, 'loaddesign']);
    });
    Route::prefix('draftdesign')->group(function () {
        Route::get('/', [App\Modules\Design\Controller\DraftDesignController::class, 'index']);
        Route::get('/add', [App\Modules\Design\Controller\DraftDesignController::class, 'create']);
        Route::post('/store', [App\Modules\Design\Controller\DraftDesignController::class, 'store']);
        Route::post('/update', [App\Modules\Design\Controller\DraftDesignController::class, 'update']);
        Route::get('/edit/{pointslug}', [App\Modules\Design\Controller\DraftDesignController::class, 'edit']);
        Route::get('/delete/{id}', [App\Modules\Design\Controller\DraftDesignController::class, 'destroy']);
        Route::get('/sectiondelete/{id}', [App\Modules\Design\Controller\DraftDesignController::class, 'destroysection']);
    });
    Route::prefix('designcategory')->group(function () {
        Route::get('/', [App\Modules\Design\Controller\DesignController::class, 'categoryindex']);
        Route::get('/add', [App\Modules\Design\Controller\DesignController::class, 'categorycreate']);
        Route::post('/store', [App\Modules\Design\Controller\DesignController::class, 'categorystore']);
        Route::post('/update', [App\Modules\Design\Controller\DesignController::class, 'categoryupdate']);
        Route::get('/edit/{id}', [App\Modules\Design\Controller\DesignController::class, 'categoryedit']);
        Route::get('/delete/{id}', [App\Modules\Design\Controller\DesignController::class, 'categorydestroy']);
    });
});