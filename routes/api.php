<?php

use Illuminate\Http\Request;

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
//V3
Route::prefix('v3')->namespace('Api\V3')->group(function () {
    Route::get('/slideshow', 'GeneralController@getSlideShow');
    Route::get('/advertising', 'GeneralController@getAdvertising');
    Route::post('/contact/create', 'GeneralController@createContact');
    Route::get('/areas', 'GeneralController@getAreas');
    Route::get('/area', 'GeneralController@getArea');
    Route::get('/delivery/types', 'GeneralController@getDeliveryType');
    Route::get('/provinces', 'GeneralController@getProvinces');
    Route::get('/province', 'GeneralController@getProvince');
    Route::get('/temp', 'UserController@temp');
    Route::get('/getSettings', 'GeneralController@getSettings');
    Route::get('/getTermsAndConditions', 'GeneralController@getTermsAndConditions');

    Route::post('apply_coupon', 'GeneralController@apply_coupon');

    Route::get('/packages', 'GeneralController@getPackageList');
    Route::get('/package', 'GeneralController@getPackage');
    Route::get('/gifts', 'GeneralController@getGiftList');

   Route::get('/packages-new','GeneralController@getNewPackageList');

    Route::get('/getGuestMeals', 'UserController@getGuestMeals');
    Route::post('/getGuestMeals', 'UserController@getGuestMeals');


    Route::middleware(['auth:api'])->group(function () {
        //Route::get('/packages','GeneralController@getPackageList');
        //Route::get('/package','GeneralController@getPackage');
    });
    Route::prefix('/user')->group(function () {
        Route::post('/login', 'UserController@login');
        Route::get('/login', 'GeneralController@loginApi')->name('login');
        Route::get('/checkValidMobileNumber', 'UserController@checkValidMobileNumber');
        Route::post('/register', 'UserController@register');
        Route::post('/restPassword', 'UserController@restPassword');
        Route::get('/temp', 'UserController@temp');

        Route::get('/helpMeChoosePackage', 'UserController@helpMeChoosePackage');

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/verify', 'UserController@verify');
            Route::post('/updatePersonalinfo', 'UserController@update');
            Route::post('/changePassword', 'UserController@changePassword');
            Route::post('/updateMeta', 'UserController@updateMeta');
            Route::post('/updatePhoto', 'UserController@updatePhoto');
            Route::post('/setRating', 'UserController@setRating');
            Route::post('/setReferralUser', 'UserController@setReferralUser');
            Route::post('/setProgress', 'UserController@setProgress');
            Route::post('/saveItem', 'UserController@saveItem');
            Route::post('/chooseRandomFood', 'UserController@chooseRandomFood');
            Route::post('/freezeDay', 'UserController@freezeDay');
            Route::post('/cancelFreezeDay ', 'UserController@cancelFreezeDay');
            Route::post('/requestCashBack', 'UserController@requestCashBack');

            Route::get('/getDayItem', 'UserController@getDayItem');
            Route::get('/getTransactionList', 'UserController@getTransactionList');

            Route::get('/getReferralList', 'UserController@getReferralList');
            Route::get('/getReferralPointHistory', 'UserController@getReferralPointHistory');
            Route::get('/meals', 'UserController@getMeals');
            Route::get('/getListValidFoodForRating', 'UserController@getListValidFoodForRating');
            Route::get('/listNotifications', 'UserController@getNotificationList');
            Route::get('/getProgressList', 'UserController@getProgressList');

            Route::get('/packageUser', 'UserController@getPackageUser');
            Route::get('/getPoints', 'UserController@getPoints');

            Route::get('/getUserDays', 'UserController@getUserDays');
            Route::get('/getUserDetail', 'UserController@getUserDetail');
            Route::get('/getOrderUser', 'UserController@getOrderUser');

            //Route::get('/helpMeChoosePackage','UserController@helpMeChoosePackage');
            Route::post('/setDay', 'UserController@setDay');
            Route::post('/setDays', 'UserController@setDays');
            //imtiaz start
            Route::post('/chooseRandomFoodByDate', 'UserController@chooseRandomFoodByDate');
            ///
            Route::get('/mealsTemp', 'UserTempController@getMeals');
            Route::post('/saveItemTemp', 'UserTempController@saveItem');
            Route::get('/getUserDaysTemp', 'UserTempController@getUserDays');
            Route::get('/getOrderUserTemp', 'UserTempController@getOrderUser');
            Route::post('/setDayTemp', 'UserTempController@setDay');
            Route::post('/chooseRandomFoodByDateTemp', 'UserTempController@chooseRandomFoodByDate');
            //end imtiaz
            Route::post('/saveGift', 'UserController@saveGift')->name('saveGift');
        });
    });
});


//V2
Route::prefix('v2')->namespace('Api\V2')->group(function () {
    Route::get('/slideshow', 'GeneralController@getSlideShow');
    Route::get('/advertising', 'GeneralController@getAdvertising');
    Route::post('/contact/create', 'GeneralController@createContact');
    Route::get('/areas', 'GeneralController@getAreas');
    Route::get('/area', 'GeneralController@getArea');
    Route::get('/delivery/types', 'GeneralController@getDeliveryType');
    Route::get('/provinces', 'GeneralController@getProvinces');
    Route::get('/province', 'GeneralController@getProvince');
    Route::get('/temp', 'UserController@temp');
    Route::get('/getSettings', 'GeneralController@getSettings');
    Route::get('/getTermsAndConditions', 'GeneralController@getTermsAndConditions');

    Route::post('apply_coupon', 'GeneralController@apply_coupon');

    Route::get('/packages', 'GeneralController@getPackageList');
    Route::get('/package', 'GeneralController@getPackage');
    Route::get('/getGuestMeals', 'UserController@getGuestMeals');
    Route::post('/getGuestMeals', 'UserController@getGuestMeals');


    Route::middleware(['auth:api'])->group(function () {
        //Route::get('/packages','GeneralController@getPackageList');
        //Route::get('/package','GeneralController@getPackage');
    });
    Route::prefix('/user')->group(function () {
        Route::post('/login', 'UserController@login');
        Route::get('/login', 'GeneralController@loginApi')->name('login');
        Route::get('/checkValidMobileNumber', 'UserController@checkValidMobileNumber');
        Route::post('/register', 'UserController@register');
        Route::post('/restPassword', 'UserController@restPassword');
        Route::get('/temp', 'UserController@temp');

        Route::get('/helpMeChoosePackage', 'UserController@helpMeChoosePackage');

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/verify', 'UserController@verify');
            Route::post('/updatePersonalinfo', 'UserController@update');
            Route::post('/changePassword', 'UserController@changePassword');
            Route::post('/updateMeta', 'UserController@updateMeta');
            Route::post('/updatePhoto', 'UserController@updatePhoto');
            Route::post('/setRating', 'UserController@setRating');
            Route::post('/setReferralUser', 'UserController@setReferralUser');
            Route::post('/setProgress', 'UserController@setProgress');
            Route::post('/saveItem', 'UserController@saveItem');
            Route::post('/chooseRandomFood', 'UserController@chooseRandomFood');
            Route::post('/freezeDay', 'UserController@freezeDay');
            Route::post('/cancelFreezeDay ', 'UserController@cancelFreezeDay');
            Route::post('/requestCashBack', 'UserController@requestCashBack');

            Route::get('/getDayItem', 'UserController@getDayItem');
            Route::get('/getTransactionList', 'UserController@getTransactionList');

            Route::get('/getReferralList', 'UserController@getReferralList');
            Route::get('/getReferralPointHistory', 'UserController@getReferralPointHistory');
            Route::get('/meals', 'UserController@getMeals');
            Route::get('/getListValidFoodForRating', 'UserController@getListValidFoodForRating');
            Route::get('/listNotifications', 'UserController@getNotificationList');
            Route::get('/getProgressList', 'UserController@getProgressList');

            Route::get('/packageUser', 'UserController@getPackageUser');
            Route::get('/getPoints', 'UserController@getPoints');

            Route::get('/getUserDays', 'UserController@getUserDays');
            Route::get('/getUserDetail', 'UserController@getUserDetail');
            Route::get('/getOrderUser', 'UserController@getOrderUser');

            //Route::get('/helpMeChoosePackage','UserController@helpMeChoosePackage');
            Route::post('/setDay', 'UserController@setDay');
            Route::post('/setDays', 'UserController@setDays');
            //imtiaz start
            Route::post('/chooseRandomFoodByDate', 'UserController@chooseRandomFoodByDate');
            ///
            Route::get('/mealsTemp', 'UserTempController@getMeals');
            Route::post('/saveItemTemp', 'UserTempController@saveItem');
            Route::get('/getUserDaysTemp', 'UserTempController@getUserDays');
            Route::get('/getOrderUserTemp', 'UserTempController@getOrderUser');
            Route::post('/setDayTemp', 'UserTempController@setDay');
            Route::post('/chooseRandomFoodByDateTemp', 'UserTempController@chooseRandomFoodByDate');
            //end imtiaz


        });
    });
});
//V1
Route::prefix('v1')->namespace('Api\V1')->group(function () {
    Route::get('/slideshow', 'GeneralController@getSlideShow');
    Route::get('/advertising', 'GeneralController@getAdvertising');
    Route::post('/contact/create', 'GeneralController@createContact');
    Route::get('/areas', 'GeneralController@getAreas');
    Route::get('/area', 'GeneralController@getArea');
    Route::get('/delivery/types', 'GeneralController@getDeliveryType');
    Route::get('/provinces', 'GeneralController@getProvinces');
    Route::get('/province', 'GeneralController@getProvince');
    Route::get('/temp', 'UserController@temp');
    Route::get('/getSettings', 'GeneralController@getSettings');
    Route::get('/getTermsAndConditions', 'GeneralController@getTermsAndConditions');

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/packages', 'GeneralController@getPackageList');
        Route::get('/package', 'GeneralController@getPackage');
    });
    Route::prefix('/user')->group(function () {
        Route::post('/login', 'UserController@login');
        Route::get('/login', 'GeneralController@loginApi')->name('login');
        Route::get('/checkValidMobileNumber', 'UserController@checkValidMobileNumber');
        Route::post('/register', 'UserController@register');
        Route::post('/restPassword', 'UserController@restPassword');
        Route::get('/temp', 'UserController@temp');

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/verify', 'UserController@verify');
            Route::post('/updatePersonalinfo', 'UserController@update');
            Route::post('/changePassword', 'UserController@changePassword');
            Route::post('/updateMeta', 'UserController@updateMeta');
            Route::post('/updatePhoto', 'UserController@updatePhoto');
            Route::post('/setRating', 'UserController@setRating');
            Route::post('/setReferralUser', 'UserController@setReferralUser');
            Route::post('/setProgress', 'UserController@setProgress');
            Route::post('/saveItem', 'UserController@saveItem');
            Route::post('/chooseRandomFood', 'UserController@chooseRandomFood');
            Route::post('/freezeDay', 'UserController@freezeDay');
            Route::post('/cancelFreezeDay ', 'UserController@cancelFreezeDay');
            Route::post('/requestCashBack', 'UserController@requestCashBack');

            Route::get('/getDayItem', 'UserController@getDayItem');
            Route::get('/getTransactionList', 'UserController@getTransactionList');

            Route::get('/getReferralList', 'UserController@getReferralList');
            Route::get('/getReferralPointHistory', 'UserController@getReferralPointHistory');
            Route::get('/meals', 'UserController@getMeals');
            Route::get('/getListValidFoodForRating', 'UserController@getListValidFoodForRating');
            Route::get('/listNotifications', 'UserController@getNotificationList');
            Route::get('/getProgressList', 'UserController@getProgressList');

            Route::get('/packageUser', 'UserController@getPackageUser');
            Route::get('/getPoints', 'UserController@getPoints');

            Route::get('/getUserDays', 'UserController@getUserDays');
            Route::get('/getUserDetail', 'UserController@getUserDetail');
            Route::get('/getOrderUser', 'UserController@getOrderUser');

            Route::get('/helpMeChoosePackage', 'UserController@helpMeChoosePackage');
            Route::post('/setDay', 'UserController@setDay');
            Route::post('/setDays', 'UserController@setDays');
            //imtiaz start
            Route::post('/chooseRandomFoodByDate', 'UserController@chooseRandomFoodByDate');
            ///
            Route::get('/mealsTemp', 'UserTempController@getMeals');
            Route::post('/saveItemTemp', 'UserTempController@saveItem');
            Route::get('/getUserDaysTemp', 'UserTempController@getUserDays');
            Route::get('/getOrderUserTemp', 'UserTempController@getOrderUser');
            Route::post('/setDayTemp', 'UserTempController@setDay');
            Route::post('/chooseRandomFoodByDateTemp', 'UserTempController@chooseRandomFoodByDate');
            //end imtiaz
        });
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
