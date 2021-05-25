<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\Admin\WorksController;
use App\Http\Controllers\Admin\WorkerReviewController;
use App\Http\Controllers\Admin\FavoriteWorkersController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::post('/upload-files/{folder?}/{size?}', [Controller::class, 'uploadFiles'])->name('dropzone.upload');

    // Work
    Route::post('/work/remove-files', [WorksController::class, 'removeFiles'])->name('work.remove.file');
    
    // City And District
    Route::get('/worker-reviews/detail/{work_id}', [WorkerReviewController::class, 'detail'])->name('worker-reviews.detail');
    Route::post('/favorite-worker/add-favorite', [FavoriteWorkersController::class, 'addFavorite'])->name('favorite.add');
});
// End admin


Route::get('/', [HomeController::class, 'index'])->name('homes.index');
Route::post('/get-districts', [DistrictController::class, 'getByCityId'])->name('company.district');



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::post('/apply-work', [HomeController::class, 'applyWork'])->name('work.apply');
    Route::post('/unapply-work', [HomeController::class, 'unapplyWork'])->name('work.unapply');
    Route::post('/favorite', [HomeController::class, 'favorite'])->name('work.favorite');
    Route::post('/unfavorite', [HomeController::class, 'unFavorite'])->name('work.unfavorite');
    Route::post('/worker-review', [HomeController::class, 'workerReviewJob'])->name('work.review');
    Route::post('/worker-request', [HomeController::class, 'workerRequestJob'])->name('work.review');

    Route::get('/user/my-cv', [MyProfileController::class, 'myProfile'])->name('mycv.add');
    Route::post('/user/my-cv', [MyProfileController::class, 'saveProfile'])->name('mycv.store');
    Route::get('/favourite-works', [HomeController::class, 'getFavoriteWork'])->name('work.favorite_work');
    Route::get('/evaluating-works', [HomeController::class, 'getEvaluatingWork'])->name('work.evaluating_work');
    Route::get('/application-works', [HomeController::class, 'workerApplication'])->name('work.application');
});

Route::get('/{id}/{slug?}', [HomeController::class, 'show'])->name('work.show');
