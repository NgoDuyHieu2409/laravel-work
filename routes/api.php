<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CheckinController;
use App\Http\Controllers\Api\CheckoutController;

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

    // Route::group([
    //     'middleware' => ['auth.firebase', 'auto-register']
    // ], function () {
        // register
        Route::post('/regist', 'Api\RegistController@store');

        // get list rooms user can chat
        Route::get('/chat-rooms', 'Api\ChatController@listRoomsCanChat');

        // Apply Job
        Route::post('/apply-work', 'Api\ApplyWorkController@applyWork');

        //past_work_list
        Route::apiResource('/past_work_list', 'Api\PastWorkListController')->only([
            'index',
        ]);

        //total_wage
        Route::apiResource('/total_wage', 'Api\TotalWageController')->only([
            'index',
        ]);

        //profile
        Route::apiResource('/profile', 'Api\ProfileController')->only([
            'index',
        ]);

        //assigned_works
        Route::apiResource('/assigned_works', 'Api\AssignedWorksController')->only([
            'index',
        ]);

        //modifiable_works
        Route::apiResource('/modifiable_works', 'Api\ModifiableWorksController')->only([
            'index',
        ]);

        //modify_requests
        Route::apiResource('/modify_requests', 'Api\ModifyRequestController')->only([
            'index', 'store'
        ]);
        // Get preview modify request
        Route::get('/modify_requests/preview', 'Api\ModifyRequestController@getPreview');


        //checkout
        Route::apiResource('/checkout', CheckoutController::class)->only([
            'index',
        ]);

        //checkin
        Route::apiResource('/checkin', CheckinController::class)->only([
            'index',
        ]);

        //checkout_works
        Route::apiResource('/checkout_works', 'Api\CheckoutWorksController')->only([
            'index',
        ]);

        //checkin_works
        Route::apiResource('/checkin_works', 'Api\CheckinWorksController')->only([
            'index',
        ]);

        //favorite_works
        Route::apiResource('/favorite_works', 'Api\FavoriteWorksController')->only([
            'index',
        ]);

        //identification
        Route::post('/worker/identification', 'Api\WorkerController@postIdentification');

        //worker regist
        Route::apiResource('/worker', 'Api\WorkerController')->except([
            'create', 'index', 'destroy'
        ]);
        // Confirm work
        Route::post('/confirm-work', 'Api\ConfirmWorkController@confirm');

        // Cancel work
        Route::post('/cancel-work', 'Api\CancelWorkController@cancel');

        //Upload avatar
        Route::post('/upload-avatar', 'Api\ProfileController@uploadAvatar');

        //Check WorkerIdentification
        Route::get('/check-identification', 'Api\WorkerController@checkIdentification');

        //list job admin approved - worker can confirm to work
        Route::get('/works/approved', 'Api\AssignedWorksController@getWorksAdminApproved');
        // view checkin info
        Route::get('/view_checkin_info', 'Api\ViewCheckController@viewCheckinInfo');

        //Reviews
        Route::apiResource('/reviews', 'Api\WorksReviewController')->only([
            'index', 'show', 'store'
        ]);
        // view checkin info
        Route::get('/view_checkin_info', 'Api\ViewCheckController@viewCheckinInfo');

        // view checkout info
        Route::get('/view_checkout_info', 'Api\ViewCheckController@viewCheckoutInfo');

        //FavoriteWork
        Route::apiResource('/favorite-works', 'Api\FavoriteWorkController')->only([
            'index','store'
        ]);
        Route::post('/favorite-works/delete', 'Api\FavoriteWorkController@destroy');
        // Past Work By Month
        Route::apiResource('/past_work_by_month', 'Api\PastWorkByMonthController');

        // Past Work Detail
        Route::apiResource('/past_work_detail', 'Api\PastWorkDetailController');

        // View Bank Info
        Route::apiResource('/view_bank_info', 'Api\ViewBankInfoController');

        // Edit Bank Account
        Route::post('/edit_bank_account', 'Api\EditBankAccountController@update');

        //worker qualification
        Route::apiResource('/worker-qualifications', 'Api\WorkerQualificationController')->only([
            'index','store'
        ]);

        //Skills
        Route::apiResource('/skills', 'Api\SkillsController');

        // worker_reviews
        Route::apiResource('/worker_reviews', 'Api\WorkerReviewsController');

        // get_option_bank_info
        Route::apiResource('/get_option_bank_info', 'Api\GetOptionBankInfoController');
        //get_branch_name
        Route::get('/get_branch_name', 'Api\GetOptionBankInfoController@getBranchName');
        // get_option_bank_info
        Route::apiResource('/turn_off_app', 'Api\TurnOffAppController');
    // });

    Route::apiResource('/test', 'Api\TestController');


    //news
    Route::apiResource('/news', 'Api\NewsController')->only([
        'index',
    ]);

    //works
    Route::get('/prefectures', 'Api\PrefectureController@index');
    Route::get('/works/sorts', 'Api\WorksController@getListSort');

    Route::apiResource('/works', 'Api\WorksController')->only([
        'index', 'show'
    ]);


    // get skills by work id
    Route::get('/works/{id}/skills', 'Api\WorksController@getSkillsByWorkId');

    //policy
    Route::get('/policy', 'Api\PolicyController@getPolicy');

    //worker qualification
    Route::get('/qualification/types', 'Api\WorkerQualificationController@get_types');

    // Route::get('/get-token', 'Api\TestTokenController@index');

});