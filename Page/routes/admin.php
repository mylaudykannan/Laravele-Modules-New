<?php
//page
Route::prefix('admin')->middleware(['web', 'can:Page'])->group(function () {
    Route::prefix('page')->group(function () {
        Route::get('/', [App\Modules\Page\Controller\PageController::class, 'index']);
        Route::get('/add', [App\Modules\Page\Controller\PageController::class, 'create']);
        Route::post('/store', [App\Modules\Page\Controller\PageController::class, 'store']);
        Route::post('/update', [App\Modules\Page\Controller\PageController::class, 'update']);
        Route::post('/updateslug', [App\Modules\Page\Controller\PageController::class, 'updateSlug']);
        Route::get('/edit/{pointslug}', [App\Modules\Page\Controller\PageController::class, 'edit']);
        Route::get('/delete/{id}', [App\Modules\Page\Controller\PageController::class, 'destroy']);
        Route::get('/sectiondelete/{id}', [App\Modules\Page\Controller\PageController::class, 'destroysection']);
        Route::get('/loaddesign', [App\Modules\Page\Controller\PageController::class, 'loaddesign']);
    });
    Route::prefix('draftpage')->group(function () {
        Route::get('/', [App\Modules\Page\Controller\DraftPageController::class, 'index']);
        Route::get('/add', [App\Modules\Page\Controller\DraftPageController::class, 'create']);
        Route::post('/store', [App\Modules\Page\Controller\DraftPageController::class, 'store']);
        Route::post('/update', [App\Modules\Page\Controller\DraftPageController::class, 'update']);
        Route::get('/edit/{pointslug}', [App\Modules\Page\Controller\DraftPageController::class, 'edit']);
        Route::get('/delete/{id}', [App\Modules\Page\Controller\DraftPageController::class, 'destroy']);
        Route::get('/sectiondelete/{id}', [App\Modules\Page\Controller\DraftPageController::class, 'destroysection']);
    });
    Route::prefix('pagecategory')->group(function () {
        Route::get('/', [App\Modules\Page\Controller\PageController::class, 'categoryindex']);
        Route::get('/add', [App\Modules\Page\Controller\PageController::class, 'categorycreate']);
        Route::post('/store', [App\Modules\Page\Controller\PageController::class, 'categorystore']);
        Route::post('/update', [App\Modules\Page\Controller\PageController::class, 'categoryupdate']);
        Route::get('/edit/{id}', [App\Modules\Page\Controller\PageController::class, 'categoryedit']);
        Route::get('/delete/{id}', [App\Modules\Page\Controller\PageController::class, 'categorydestroy']);
    });
});