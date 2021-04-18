<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\WorksController;
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
    Route::post('/get-districts', [DistrictController::class, 'getByCityId'])->name('company.district');
});
// End admin


Route::get('/', [HomeController::class, 'index'])->name('homes.index');
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/{id}/{slug?}', [HomeController::class, 'show'])->name('work.show');
    Route::post('/apply-work', [HomeController::class, 'applyWork'])->name('work.apply');

});
Route::get('/my-cv/{id?}', [MyProfileController::class, 'myProfile'])->name('mycv.add');