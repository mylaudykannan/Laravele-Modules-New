<?php
//news
Route::prefix('admin')->middleware(['web', 'can:News'])->group(function () {
    Route::prefix('news')->group(function () {
        Route::get('/', [App\Modules\News\Controller\NewsController::class, 'index']);
        Route::get('/add', [App\Modules\News\Controller\NewsController::class, 'create']);
        Route::post('/store', [App\Modules\News\Controller\NewsController::class, 'store']);
        Route::post('/update', [App\Modules\News\Controller\NewsController::class, 'update']);
        Route::post('/updateslug', [App\Modules\News\Controller\NewsController::class, 'updateSlug']);
        Route::get('/edit/{pointslug}', [App\Modules\News\Controller\NewsController::class, 'edit']);
        Route::get('/delete/{id}', [App\Modules\News\Controller\NewsController::class, 'destroy']);
        Route::get('/sectiondelete/{id}', [App\Modules\News\Controller\NewsController::class, 'destroysection']);
        Route::get('/loaddesign', [App\Modules\News\Controller\NewsController::class, 'loaddesign']);
    });
    Route::prefix('draftnews')->group(function () {
        Route::get('/', [App\Modules\News\Controller\DraftNewsController::class, 'index']);
        Route::get('/add', [App\Modules\News\Controller\DraftNewsController::class, 'create']);
        Route::post('/store', [App\Modules\News\Controller\DraftNewsController::class, 'store']);
        Route::post('/update', [App\Modules\News\Controller\DraftNewsController::class, 'update']);
        Route::get('/edit/{pointslug}', [App\Modules\News\Controller\DraftNewsController::class, 'edit']);
        Route::get('/delete/{id}', [App\Modules\News\Controller\DraftNewsController::class, 'destroy']);
        Route::get('/sectiondelete/{id}', [App\Modules\News\Controller\DraftNewsController::class, 'destroysection']);
    });
    Route::prefix('newscategory')->group(function () {
        Route::get('/', [App\Modules\News\Controller\NewsController::class, 'categoryindex']);
        Route::get('/add', [App\Modules\News\Controller\NewsController::class, 'categorycreate']);
        Route::post('/store', [App\Modules\News\Controller\NewsController::class, 'categorystore']);
        Route::post('/update', [App\Modules\News\Controller\NewsController::class, 'categoryupdate']);
        Route::get('/edit/{id}', [App\Modules\News\Controller\NewsController::class, 'categoryedit']);
        Route::get('/delete/{id}', [App\Modules\News\Controller\NewsController::class, 'categorydestroy']);
    });
});