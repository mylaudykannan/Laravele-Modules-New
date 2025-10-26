<?php
//gallery
Route::prefix('admin')->group(function () {
    Route::prefix('gallery')->middleware(['web', 'can:Gallery'])->group(function () {
        Route::get('/', [App\Modules\Gallery\Controller\GalleryController::class, 'index']);
        Route::post('/categorysuggestion', [App\Modules\Gallery\Controller\GalleryController::class, 'categorysuggestion']);
        Route::get('/categorysuggestion', [App\Modules\Gallery\Controller\GalleryController::class, 'categorysuggestion']);
        Route::get('/ajaxload', [App\Modules\Gallery\Controller\GalleryController::class, 'ajaxload']);
        Route::get('/create', [App\Modules\Gallery\Controller\GalleryController::class, 'create']);
        Route::get('/popup', [App\Modules\Gallery\Controller\GalleryController::class, 'popup']);
        Route::post('/add', [App\Modules\Gallery\Controller\GalleryController::class, 'store']);
        Route::post('/addcategory', [App\Modules\Gallery\Controller\GalleryController::class, 'storecategory']);
        Route::get('/ajaxloadcategory', [App\Modules\Gallery\Controller\GalleryController::class, 'ajaxloadcategory']);
        Route::get('/category', [App\Modules\Gallery\Controller\GalleryController::class, 'category']);
        Route::get('/category/add', [App\Modules\Gallery\Controller\GalleryController::class, 'addcategory']);
        Route::get('/category/delete/{id}', [App\Modules\Gallery\Controller\GalleryController::class, 'deletecategory']);
        Route::get('/delete/{id}', [App\Modules\Gallery\Controller\GalleryController::class, 'destroy']);
        Route::get('/ajaxinput', [App\Modules\Gallery\Controller\GalleryController::class, 'ajaxinput']);
    });
});