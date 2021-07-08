<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CheckinController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\WorksReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1'
], function ($router) {

    //checkout
    Route::apiResource('/checkout', CheckoutController::class)->only([
        'index',
    ]);

    //checkin
    Route::apiResource('/checkin', CheckinController::class)->only([
        'index',
    ]);

    //Reviews
    Route::apiResource('/reviews', WorksReviewController::class)->only([
        'index', 'show', 'store'
    ]);
});