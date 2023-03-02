<?php

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

Route::get('/temp', 'Admin\AdminSettingsController@temp');
//test
Route::get('/renew', 'TestController@renew');

Route::get('/importexcel', 'TestController@importexcel');

Route::namespace('Admin')->prefix('admin/process')->middleware(['web'])->group(function () {
    Route::get('login', 'ProcessAdminController@login');
    Route::get('logout', 'ProcessAdminController@logout');
    Route::get('lang/{lang}', 'ProcessAdminController@getLang');
    Route::post('login', 'ProcessAdminController@loginAction');
});

Route::namespace('Admin')->prefix('admin')->middleware(['web', 'admin'])->group(function () {

    //imtiaz
    //Route::get('future_pkg_subs_approve/{renew_id}','FuturePackageRenewalController@future_pkg_subs_approve');
    Route::resource('future_pkg_subs', 'FuturePackageRenewalController')->only(['index']);
    //end imtiaz

    Route::get('/', 'ProcessAdminController@showHome');
    Route::get('/logs', 'LogsController@index');

    //discount
    Route::post('discount/save', 'DiscountController@store');
    Route::post('discount/delete', 'DiscountController@postDelete');
    Route::get('discount/edit/{id}', 'DiscountController@edit');
    Route::get('discount/delete/{id}', 'DiscountController@destroy');
    Route::get('discount/add', 'DiscountController@create');
    Route::get('discount/ajax', 'DiscountController@getAjax');
    Route::resource('discount', 'DiscountController')->only(['index']);;


    Route::get('payments/ajax', 'AdminPaymentsController@getAjax');
    Route::get('payments/edit/{id}', 'AdminPaymentsController@edit');
    Route::post('payments/delete', 'AdminPaymentsController@postDelete');
    Route::get('payments/{type}', 'AdminPaymentsController@indexcustom');
    Route::resource('payments', 'AdminPaymentsController')->only(['index']);;


    Route::get('slideshow/ajax', 'AdminSlideShowController@getAjax');
    Route::get('slideshow/add', 'AdminSlideShowController@create');
    Route::post('slideshow/save', 'AdminSlideShowController@store');
    Route::get('slideshow/edit/{id}', 'AdminSlideShowController@edit');
    Route::post('slideshow/delete', 'AdminSlideShowController@postDelete');
    Route::resource('slideshow', 'AdminSlideShowController')->only(['index']);



    Route::get('appointments/ajax', 'AdminAppointmentController@getAjax');
    Route::get('appointments/add', 'AdminAppointmentController@create');
    Route::post('appointments/save', 'AdminAppointmentController@store');
    Route::get('appointments/edit/{id}', 'AdminAppointmentController@edit');
    Route::post('appointments/delete', 'AdminPaymentsController@postDelete');
    Route::resource('appointments', 'AdminAppointmentController')->only(['index']);

    Route::get('cash-back/ajax', 'AdminCashBackController@getAjax');
    Route::get('cash-back/reject/{id}', 'AdminCashBackController@reject');
    Route::get('cash-back/accept/{id}', 'AdminCashBackController@accept');
    // Route::get('orders/view/{id}','AdminOrdersController@getView');
    Route::resource('cash-back', 'AdminCashBackController')->only(['index']);;

    Route::get('autoSaveResult', 'AdminUsersController@getAutoSaveResult');


    Route::get('orders/ajax', 'AdminOrdersController@getAjax');
    Route::get('orders/view/{id}', 'AdminOrdersController@getView');
    Route::resource('orders', 'AdminOrdersController')->only(['index']);;


    Route::post('users_days/save', 'AdminUsersDaysController@store');
    Route::get('users_days/ajax', 'AdminUsersDaysController@getAjax');
    Route::get('users_days/edit/{id}', 'AdminUsersDaysController@edit');
    Route::resource('users_days', 'AdminUsersDaysController')->only(['index']);;


    Route::post('users/save', 'AdminUsersController@store');
    Route::post('users/addDay', 'AdminUsersController@addDay');
    Route::get('users/edit/{id}', 'AdminUsersController@edit');
    Route::get('users/active', 'AdminUsersController@getActiveUser');
    Route::get('users/demo', 'AdminUsersController@getDemoUser');
    Route::get('users/freeze/{id}', 'AdminUsersController@getFreezeView');
    Route::get('users/unfreeze/{id}', 'AdminUsersController@getUnFreezeView');
    //client days
    Route::get('users_days/freeze/{id}', 'AdminUsersController@getFreezeView');
    Route::get('users_days/unfreeze/{id}', 'AdminUsersController@getUnFreezeView');
    Route::post('users_days/freeze/{id}', 'AdminUsersController@freezeDays');
    Route::post('users_days/unfreeze/{id}', 'AdminUsersController@unFreezeDays');

    Route::get('users/menu/{id}', 'AdminUsersController@getMenu');
    Route::get('users/progress/{id}', 'AdminUsersController@getProgress');
    Route::get('users/delete/{id}', 'AdminUsersController@deleteUser');
    Route::post('users/delete', 'AdminUsersController@postDeleteUser');
    Route::get('users/add', 'AdminUsersController@create');
    Route::get('users/ajax', 'AdminUsersController@getAjax');
    //Route::get('users/ajax','AdminUsersController@getAjax');
    Route::get('users/transactions/{id}', 'AdminUsersController@getTransactionsUser');
    Route::get('users/points/{id}', 'AdminUsersController@getPointList');
    Route::get('users/orders/{id}', 'AdminUsersController@getOrders');
    Route::get('users/cancelDays/{id}', 'AdminUsersController@cancelDays');
    Route::get('users/cancelActiveDays/{id}', 'AdminUsersController@cancelActiveDays');
    Route::get('users/cancelSingleDays/{id}/{date}', 'AdminUsersController@cancelSingleDays');
    Route::get('users/order/{packageId}/{dateId}/{userId}', 'AdminUsersController@getOrder');
    Route::get('users/renew-or-addmembership/{id}', 'AdminUsersController@renewOrAddMemberShip');
    Route::post('users/renew-or-addmembership/{id}', 'AdminUsersController@saveRenewOrAddMemberShip');
    //client days
    Route::get('users_days/renew-or-addmembership/{id}', 'AdminUsersController@renewOrAddMemberShip');
    Route::post('users_days/renew-or-addmembership/{id}', 'AdminUsersController@saveRenewOrAddMemberShip');

    Route::post('users/saveOrder', 'AdminUsersController@saveOrder');
    Route::post('users/freeze/{id}', 'AdminUsersController@freezeDays');
    Route::post('users/unfreeze/{id}', 'AdminUsersController@unFreezeDays');

    Route::resource('users', 'AdminUsersController')->only(['index']);;

    Route::get('notificationuser', 'AdminUsersController@notificationuser')->name('notificationuser');
    Route::get('notificationuserpost', 'AdminUsersController@notificationuserpost')->name('notificationuserpost');
    Route::get('sendusernotification', 'AdminUsersController@sendusernotification')->name('sendusernotification');

    Route::post('admin_users/save', 'AdminUsersAdminController@store');
    Route::post('admin_users/delete', 'AdminUsersAdminController@postDelete');
    Route::get('admin_users/edit/{id}', 'AdminUsersAdminController@edit');
    Route::get('admin_users/add', 'AdminUsersAdminController@create');
    Route::get('admin_users/delete/{id}', 'AdminUsersDoctorsController@destroy');
    Route::get('admin_users/ajax', 'AdminUsersAdminController@getAjax');
    Route::resource('admin_users', 'AdminUsersAdminController')->only(['index']);;



    Route::post('doctor_users/save', 'AdminUsersDoctorsController@store');
    Route::post('doctor_users/delete', 'AdminUsersDoctorsController@postDelete');
    Route::get('doctor_users/edit/{id}', 'AdminUsersDoctorsController@edit');
    Route::get('doctor_users/delete/{id}', 'AdminUsersDoctorsController@destroy');
    Route::get('doctor_users/add', 'AdminUsersDoctorsController@create');
    Route::get('doctor_users/ajax', 'AdminUsersDoctorsController@getAjax');
    Route::resource('doctor_users', 'AdminUsersDoctorsController')->only(['index']);;



    Route::post('d_appointments/save', 'DAppointmentsController@store');
    Route::post('d_appointments/delete', 'DAppointmentsController@postDelete');
    Route::get('d_appointments/edit/{id}', 'DAppointmentsController@edit');
    Route::get('d_appointments/delete/{id}', 'DAppointmentsController@destroy');
    Route::get('d_appointments/add', 'DAppointmentsController@create');
    Route::get('d_appointments/ajax', 'DAppointmentsController@getAjax');
    Route::get('d_appointments/load_dieticians', 'DAppointmentsController@loadDieticians'); //
    Route::post('d_appointments/get_clients', 'DAppointmentsController@getClients'); //
    Route::resource('d_appointments', 'DAppointmentsController')->only(['index']);; //



    Route::post('kitchen_users/save', 'AdminUsersKitchensController@store');
    Route::post('kitchen_users/delete', 'AdminUsersKitchensController@postDelete');
    Route::get('kitchen_users/edit/{id}', 'AdminUsersKitchensController@edit');
    Route::get('kitchen_users/delete/{id}', 'AdminUsersKitchensController@destroy');
    Route::get('kitchen_users/add', 'AdminUsersKitchensController@create');
    Route::get('kitchen_users/ajax', 'AdminUsersKitchensController@getAjax');
    Route::resource('kitchen_users', 'AdminUsersKitchensController')->only(['index']);;

    Route::get('/message/view/{id}', 'AdminMessageController@getView');

    Route::post('portions/save', 'AdminPortionsController@store');
    Route::post('portions/delete', 'AdminPortionsController@postDelete');
    Route::get('portions/edit/{id}', 'AdminPortionsController@edit');
    Route::get('portions/delete/{id}', 'AdminPortionsController@destroy');
    Route::get('portions/add', 'AdminPortionsController@create');
    Route::get('portions/ajax', 'AdminPortionsController@getAjax');
    Route::resource('portions', 'AdminPortionsController')->only(['index']);;


    Route::post('delivery_type/save', 'AdminDeliveryTypeController@store');
    Route::post('delivery_type/delete', 'AdminDeliveryTypeController@postDelete');
    Route::get('delivery_type/edit/{id}', 'AdminDeliveryTypeController@edit');
    Route::get('delivery_type/delete/{id}', 'AdminDeliveryTypeController@destroy');
    Route::get('delivery_type/add', 'AdminDeliveryTypeController@create');
    Route::get('delivery_type/ajax', 'AdminDeliveryTypeController@getAjax');
    Route::resource('delivery_type', 'AdminDeliveryTypeController')->only(['index']);;


    Route::post('addons/save', 'AdminAddonController@store');
    Route::post('addons/delete', 'AdminAddonController@postDelete');
    Route::get('addons/edit/{id}', 'AdminAddonController@edit');
    Route::get('addons/delete/{id}', 'AdminAddonController@destroy');
    Route::get('addons/add', 'AdminAddonController@create');
    Route::get('addons/ajax', 'AdminAddonController@getAjax');
    Route::resource('addons', 'AdminAddonController')->only(['index']);;

    Route::post('items/save', 'AdminItemController@store');
    Route::post('items/delete', 'AdminItemController@postDelete');
    Route::get('items/edit/{id}', 'AdminItemController@edit');
    Route::get('items/delete/{id}', 'AdminItemController@destroy');
    Route::get('items/add', 'AdminItemController@create');
    Route::get('items/ajax', 'AdminItemController@getAjax');
    Route::get('items/export/{tt}', 'AdminItemController@export');
    Route::get('item-days', 'AdminItemController@getItemDays');
    Route::get('items/category/{id}', 'AdminItemController@chooseItemCategory');
    Route::get('items/choose/category/{item}/{category}', 'AdminItemController@chooseCategory');
    Route::resource('items', 'AdminItemController')->only(['index']);;

    Route::post('categories/save', 'AdminCategoryController@store');
    Route::post('categories/delete', 'AdminCategoryControllerr@postDelete');
    Route::get('categories/edit/{id}', 'AdminCategoryController@edit');
    Route::get('categories/delete/{id}', 'AdminCategoryController@destroy');
    Route::get('categories/add', 'AdminCategoryController@create');
    Route::get('categories/ajax', 'AdminCategoryController@getAjax');
    Route::resource('categories', 'AdminCategoryController')->only(['index']);;



    Route::post('meals/save', 'AdminMealController@store');
    Route::post('meals/delete', 'AdminMealController@postDelete');
    Route::get('meals/edit/{id}', 'AdminMealController@edit');
    Route::get('meals/delete/{id}', 'AdminMealController@destroy');
    Route::get('meals/add', 'AdminMealController@create');
    Route::get('meals/ajax', 'AdminMealController@getAjax');
    Route::resource('meals', 'AdminMealController')->only(['index']);;



    Route::post('packagereports/save', 'AdminPackageReportController@store');
    Route::post('packagereports/delete', 'AdminPackageReportController@postDelete');
    Route::get('packagereports/edit/{id}', 'AdminPackageReportController@edit');
    Route::get('packagereports/delete/{id}', 'AdminPackageReportController@destroy');
    Route::get('packagereports/add', 'AdminPackageReportController@create');
    Route::get('packagereports/ajax', 'AdminPackageReportController@getAjax');
    Route::resource('packagereports', 'AdminPackageReportController')->only(['index']);;



    Route::post('packages/save', 'AdminPackageController@store');
    Route::post('packages/delete', 'AdminPackageController@postDelete');
    Route::get('packages/edit/{id}', 'AdminPackageController@edit');
    Route::get('packages/delete/{id}', 'AdminPackageController@destroy');
    Route::get('packages/add', 'AdminPackageController@create');
    Route::get('packages/ajax', 'AdminPackageController@getAjax');
    Route::post('package/durations', 'AdminPackageController@getDurations');
    Route::resource('packages', 'AdminPackageController')->only(['index']);


    Route::post('gifts/save', 'AdminGiftController@store');
    Route::post('gifts/delete', 'AdminGiftController@postDelete');
    Route::get('gifts/edit/{id}', 'AdminGiftController@edit');
    Route::get('gifts/delete/{id}', 'AdminGiftController@destroy');
    Route::get('gifts/add', 'AdminGiftController@create');
    Route::get('gifts/ajax', 'AdminGiftController@getAjax');
    Route::resource('gifts', 'AdminGiftController')->only(['index']);

    Route::post('packages-duration/save', 'AdminPackageDurationsController@store');
    Route::post('packages-duration/delete', 'AdminPackageDurationsController@postDelete');
    Route::get('packages-duration/edit/{id}', 'AdminPackageDurationsController@edit');
    Route::get('packages-duration/delete/{id}', 'AdminPackageDurationsController@destroy');
    Route::get('packages-duration/add', 'AdminPackageDurationsController@create');
    Route::get('packages-duration/ajax', 'AdminPackageDurationsController@getAjax');
    Route::resource('packages-duration', 'AdminPackageDurationsController')->only(['index']);;



    Route::post('clinics/save', 'AdminClinicController@store');
    Route::post('clinics/delete', 'AdminClinicController@postDelete');
    Route::get('clinics/edit/{id}', 'AdminClinicController@edit');
    Route::get('clinics/delete/{id}', 'AdminClinicController@destroy');
    Route::get('clinics/add', 'AdminClinicController@create');
    Route::get('clinics/ajax', 'AdminClinicController@getAjax');
    Route::resource('clinics', 'AdminClinicController')->only(['index']);;




    Route::post('areas/save', 'AdminAreasController@store');
    Route::post('areas/delete', 'AdminAreasController@postDelete');
    Route::get('areas/edit/{id}', 'AdminAreasController@edit');
    Route::get('areas/delete/{id}', 'AdminAreasController@destroy');
    Route::get('areas/add', 'AdminAreasController@create');
    Route::get('areas/ajax', 'AdminAreasController@getAjax');
    Route::resource('areas', 'AdminAreasController')->only(['index']);;


    Route::post('sms/save', 'AdminSmsController@store');
    // Route::post('sms/delete','AdminAreasController@postDelete');
    Route::get('sms/edit/{id}', 'AdminSmsController@edit');
    // Route::get('sms/delete/{id}','AdminAreasController@destroy');
    // Route::get('sms/add','AdminAreasController@create');
    Route::get('sms/ajax', 'AdminSmsController@getAjax');
    Route::resource('sms', 'AdminSmsController')->only(['index']);;



    Route::post('provinces/save', 'AdminProvincesController@store');
    Route::post('provinces/delete', 'AdminProvincesController@postDelete');
    Route::get('provinces/edit/{id}', 'AdminProvincesController@edit');
    Route::get('provinces/delete/{id}', 'AdminProvincesController@destroy');
    Route::get('provinces/add', 'AdminProvincesController@create');
    Route::get('provinces/ajax', 'AdminProvincesController@getAjax');
    Route::resource('provinces', 'AdminProvincesController')->only(['index']);;







    Route::get('countries/ajax', 'AdminCountriesController@getAjax');
    Route::resource('countries', 'AdminCountriesController')->only(['index']);;



    Route::post('menu/save', 'AdminSiteMenuController@store');
    Route::post('menu/delete', 'AdminSiteMenuController@postDelete');;
    Route::get('menu/delete/{id}', 'AdminSiteMenuController@destroy');
    Route::get('menu/add', 'AdminSiteMenuController@create');
    Route::get('menu/edit/{id}', 'AdminSiteMenuController@edit');
    Route::get('menu/ajax', 'AdminSiteMenuController@getAjax');
    Route::resource('menu', 'AdminSiteMenuController')->only(['index']);;



    Route::post('admin_menu/save', 'AdminMenuController@store');
    Route::post('admin_menu/delete', 'AdminMenuController@postDelete');;
    Route::get('admin_menu/delete/{id}', 'AdminMenuController@destroy');
    Route::get('admin_menu/add', 'AdminMenuController@create');
    Route::get('admin_menu/edit/{id}', 'AdminMenuController@edit');
    Route::get('admin_menu/ajax', 'AdminMenuController@getAjax');
    Route::resource('admin_menu', 'AdminMenuController')->only(['index']);;



    Route::post('languages/save', 'AdminLangController@store');
    Route::post('languages/delete', 'AdminLangController@postDelete');;
    Route::get('languages/delete/{id}', 'AdminLangController@destroy');
    Route::get('languages/add', 'AdminLangController@create');
    Route::get('languages/edit/{id}', 'AdminLangController@edit');
    Route::get('languages/ajax', 'AdminLangController@getAjax');
    Route::resource('languages', 'AdminLangController')->only(['index']);;



    Route::post('settings/save', 'AdminSettingsController@store');
    Route::get('settings/edit/{id}', 'AdminSettingsController@getEdit');
    Route::resource('settings', 'AdminSettingsController')->only(['index']);;



    Route::post('site-page/about/save', 'AdminAboutPageController@store');
    Route::get('site-page/about/edit/{id}', 'AdminAboutPageController@getEdit');

    Route::post('site-page/terms-and-conditions/save', 'AdminTermsAndConditionsPageController@store');
    Route::get('site-page/terms-and-conditions/edit/{id}', 'AdminTermsAndConditionsPageController@getEdit');



    Route::post('site-page/membership/save', 'AdminMembershipPageController@store');
    Route::get('site-page/membership/edit/{id}', 'AdminMembershipPageController@getEdit');

    Route::post('site-page/contact-info/save', 'AdminContactInfoController@store');
    Route::get('site-page/contact-info/edit/{id}', 'AdminContactInfoController@getEdit');


    Route::get('site-page/examples-of-meals/ajax', 'AdminExamplesOfMealsController@getAjax');
    Route::get('site-page/examples-of-meals/add', 'AdminExamplesOfMealsController@create');
    Route::post('site-page/examples-of-meals/save', 'AdminExamplesOfMealsController@store');
    Route::get('ssite-page/examples-of-meals/edit/{id}', 'AdminExamplesOfMealsController@edit');
    Route::post('site-page/examples-of-meals/delete', 'AdminExamplesOfMealsController@postDelete');
    Route::get('site-page/examples-of-meals/', 'AdminExamplesOfMealsController@index');



    Route::get('membership/{endDate}', 'MembershipDashboard@getEndDahsFilterDate');
    Route::get('membership/ajax', 'MembershipDashboard@getAjax');
    Route::resource('membership', 'MembershipDashboard')->only(['index']);; //

    Route::get('membership_suspension/ajax', 'MembershipSuspensionController@getAjax');
    Route::resource('membership_suspension', 'MembershipSuspensionController')->only(['index']);; //


    Route::post('membership_suspensions/suspend', 'MembershipSuspensionController@suspendMembership'); //
    Route::post('membership_suspensions/resume', 'MembershipSuspensionController@resumeMembership'); //

    Route::get('membership_renewal/get_packages', 'MembershipRenewalController@loadPackages'); //
    Route::get('membership_renewal/get_user_details/{userid}', 'MembershipRenewalController@loadUserDetails'); //
    Route::post('membership_renewal/add_membership', 'MembershipRenewalController@addMembership'); //
    Route::get('membership_renewal/ajax', 'MembershipRenewalController@getAjax');
    Route::resource('membership_renewal', 'MembershipRenewalController')->only(['index']);; //

    Route::get('memberships_end/load_followups/{user_id}', 'FollowupsController@loadData'); //
    Route::get('memberships_end/send_email/{user_id}', 'MembershipDashboard@sendEmail'); //
    Route::post('memberships_end/add_note', 'MembershipDashboard@addNote'); //
    Route::get('memberships_end/get_notes/{user_id}', 'MembershipDashboard@loadNotes'); //


    Route::get('memberships_date/get_dates/{sdate}', 'MembershipDashboard@saveSession'); //  imtiaz



    Route::get('standard_menus_new/ajax', 'AdminStandardMenusControllerNew@getAjax');
    Route::get('standard_menus_new/delete/{id}', 'AdminStandardMenusControllerNew@delete');
    Route::resource('standard_menus_new', 'AdminStandardMenusControllerNew')->only(['index']);; //IMTIAZ



    Route::post('standard_menus/save_menu', 'StandardMenusController@saveMenu'); //
    Route::post('standard_menus/get_s_menus_list', 'StandardMenusController@getSM_List'); //
    Route::post('standard_menus/get_users_list', 'StandardMenusController@get_users_list'); //
    Route::post('standard_menus/assign_menu', 'StandardMenusController@assign_menu'); //
    Route::post('standard_menus/get_meals', 'StandardMenusController@getMeals'); //
    Route::post('appointments_by_doctorstandard_menus/add_s_menu', 'StandardMenusController@addStandardMenu'); //
    Route::post('standard_menus/get_standard_menu', 'StandardMenusController@getStandardMenu'); //
    Route::post('standard_menus/get_standard_menu_day', 'StandardMenusController@get_standard_menu_day'); //
    Route::get('standard_menus/ajax', 'StandardMenusController@getAjax');
    Route::post('standard_menus/add_s_menu', 'StandardMenusController@addStandardMenu'); //
    Route::resource('standard_menus', 'StandardMenusController')->only(['index']);; //






    Route::post('invoices/getinvoice_data', 'InvoicesController@getInvoiceData');
    Route::post('invoices/getusers', 'InvoicesController@getUsers'); //
    Route::post('invoices/getinvoice', 'InvoicesController@getGridView'); //
    Route::get('invoices/add', 'InvoicesController@add'); //
    Route::get('invoices/print/{id}', 'InvoicesController@print'); //
    Route::post('invoices/save', 'InvoicesController@save');
    Route::resource('invoices', 'InvoicesController')->only(['index']);; //

    Route::post('appointments_by_doctor/get_appointments_for_d', 'AppointmentsDoctorController@getAppt'); //
    Route::resource('appointments_by_doctor', 'AppointmentsDoctorController')->only(['index']);; //

    Route::resource('settings', 'AdminSettingsController')->only(['index']);;
});

Route::prefix('admin/app')->namespace('App')->middleware(['web', 'admin'])->group(function () {
    Route::get('slideshow/ajax', 'AdminAppSlideShowController@getAjax');
    Route::get('slideshow/add', 'AdminAppSlideShowController@create');
    Route::post('slideshow/save', 'AdminAppSlideShowController@store');
    Route::get('slideshow/edit/{id}', 'AdminAppSlideShowController@edit');
    Route::post('slideshow/delete', 'AdminAppSlideShowController@postDelete');
    Route::get('slideshow', 'AdminAppSlideShowController@index');


    Route::get('advertising/ajax', 'AppAdvertisingController@getAjax');
    Route::get('advertising/add', 'AppAdvertisingController@create');
    Route::post('advertising/save', 'AppAdvertisingController@store');
    Route::get('advertising/edit/{id}', 'AppAdvertisingController@edit');
    Route::post('advertising/delete', 'AppAdvertisingController@postDelete');
    Route::get('advertising', 'AppAdvertisingController@index');

    Route::get('notifications', 'NotificationsController@index');
    Route::get('settings', 'AppAdvertisingController@appSettings');
    Route::post('setting/save', 'AppAdvertisingController@saveSettings');
    Route::post('notifications/save', 'NotificationsController@store');
});


// Site Routes
Route::middleware(['auth:api'])->group(function () {
    Route::get('/payment', 'PaymentController@mobilePayment');
    Route::post('/payment', 'PaymentController@mobilePayment');
    Route::get('/reNewSubscription', 'PaymentController@reNewSubscription');
    Route::post('/reNewSubscription', 'PaymentController@reNewSubscription');

    Route::get('/payment2', 'PaymentControllerV2@mobilePayment');
    Route::post('/payment2', 'PaymentControllerV2@mobilePayment');
    Route::get('/reNewSubscription2', 'PaymentControllerV2@reNewSubscription');
    Route::post('/reNewSubscription2', 'PaymentControllerV2@reNewSubscription');
});

Route::get('/callBackPayment', 'PaymentController@callBackPayment')->name('call_back_payment');
Route::get('/callBackPayment2', 'PaymentControllerV2@callBackPayment')->name('call_back_payment2');

Route::post('/newregister', 'PaymentControllerV2@newregister')->name('newregister');


Route::get('/css.css', array('uses' => 'HomeController@renderCss'));
Route::get('/js.js', array('uses' => 'HomeController@renderJs'));
Route::get('lang/{lang}', 'HomeController@switchLang2');
Route::post('subscribe', 'HomeController@subscribeList');
Route::get('page/{alias}', 'PageController@showPage');
Route::get('user/logout', 'UserController@getLogout');
Route::get('user/newlogout', 'UserController@getNewlogout');
Route::get('user/login', 'UserController@getLogin')->name('user_login');
Route::get('members/public/user/newlogin', 'UserController@getNewLogin')->name("new_login");
Route::get('user/register', 'UserController@getRegister')->name('user_register');
Route::get('user/reset/{token}', 'UserController@getReset');
Route::post('user/register', 'UserController@postRegister');

Route::post('user/reset', 'UserController@postReset');
Route::post('user/login', 'UserController@postLogin')->name('post_member_login');
Route::get('user/forget', 'UserController@getForget');
Route::get('summary', 'UserController@getSummeryIndex');

Route::get('pushme', 'HomeController@sendPushNotification');



Route::namespace('Frontend')->group(function () {
    Route::get('/', 'PageController@index')->name('main_index');
    Route::get('/about', 'PageController@aboutPage')->name('about');
    Route::get('/app-about', 'PageController@appAboutPage')->name('app_about');
    Route::get('/app-about/{lang}', 'PageController@appAboutPage')->name('app_about_');
    Route::get('/terms-and-conditions', 'PageController@termsAndConditions')->name('terms_and_conditions');
    Route::get('/terms-and-conditions2', 'PageController@termsAndConditions2')->name('terms_and_conditions2');
    Route::get('/app-terms-and-conditions/{lang}', 'PageController@appTermsAndConditions')->name('app_terms_and_conditions');
    Route::get('/membership', 'PageController@membershipPage')->name('membership');
    Route::get('/examples-of-meals', 'PageController@examplesOfMealsPage')->name('examplesOfMeals');
    Route::get('/contacts', 'PageController@contactsPage')->name('contacts');
    Route::post('/contacts', 'PageController@contactsPost')->name('contact_post');
    Route::post('/user/registerMessage', 'PageController@registerMessage');
    Route::get('/getDietUserNumber', 'PageController@getDietUserNumber');
});


Route::get('/myhome', array('uses' => 'HomeController@showHome'))->name('home_members');

Route::middleware(['web', 'authUser'])->group(function () {
    Route::get('/setSessionFilterUser', 'MenuController@setSessionFilterUser');
    Route::get('/user/submissions', 'UserController@getSubmissions');
    Route::get('/user/cp', 'UserController@getCp');
    Route::post('/user/cp', 'UserController@postInfo');
    Route::get('/user/clientsView', 'UserController@getClientsView');
    Route::get('/user/clients', 'UserController@getClients');
    Route::get('/user/profile/{id}', 'UserController@getProfile');
    Route::post('/user/loginExternal', 'UserController@postLoginExternal');

    Route::post('/user/saveprofile', 'UserController@postSaveprofile');


    Route::get('menu', 'MenuController@getIndex2')->name("user_menu");
    Route::get('menu2', 'MenuController@getIndex');
    Route::get('menu/listDays', 'MenuController@listDays');
    Route::get('menu/order/listHtml/{date}', 'MenuController@getOrderByDate');
    Route::get('/menu/order/listHtmlDoctor/{userIs}/{orderId}/{date}', 'MenuController@getListHtmlDoctor');
    Route::get('/menu/users', 'MenuController@getUsers')->name('list_users');
    Route::get('menu/user-menu/{id}', 'MenuController@getUserMenu');
    Route::get('menu/edit-order/{id}', 'MenuController@getEditOrder');
    Route::get('menu/approve-order/{id}', 'MenuController@getApproveOrder');
    Route::get('menu/package-day/{packId}/{dayId}/{userId}', 'MenuController@getPackageDay');
    Route::get('menu/approve-all/{id}', 'MenuController@getApproveAll');
    Route::get('menu/package-day-html/{packId}/{dayId}/{userId}', 'MenuController@getPackageDayHtml');
    Route::post('menu/save-item', 'MenuController@postSaveItem');
    Route::post('menu/save', 'MenuController@postSave');
    Route::post('menu/getUserDays', 'MenuController@getUserDays');
    Route::post('menu/saveDays', 'MenuController@saveDays');
    Route::post('menu/order/saveOrder', 'MenuController@saveOrder');
    Route::post('menu/order/saveOrderByDoctor', 'MenuController@saveOrderByDoctor');

    Route::get('appointments', 'AppointmentsController@getIndex');
    Route::get('appointments/manage', 'AppointmentsController@getManage');
    Route::get('appointments/confirm/{id}/{userId}', 'AppointmentsController@getConfirm');
    Route::get('appointments/doctor-times/{id}', 'AppointmentsController@getDoctorTimes');
    Route::get('appointments/add/{id}', 'AppointmentsController@getAdd');
    Route::get('appointments/edit/{id}/{userId}', 'AppointmentsController@getEdit');
    Route::get('appointments/doctor-date-times/{id}/{date}', 'AppointmentsController@getDoctorDateTimes');
    Route::post('appointments/save/{id}', 'AppointmentsController@postSave');
    Route::post('appointments/check-hour', 'AppointmentsController@postCheckHour');



    Route::get('faq', 'FAQController@getIndex');

    Route::get('contact', 'ContactController@getIndex');
    Route::post('contact', 'ContactController@postIndex');

    Route::get('kitchen', 'KitchenController@getIndex');
    Route::get('kitchen/portioning', 'KitchenController@getPortioning');
    Route::get('kitchen/delivery', 'KitchenController@getDelivery');
    Route::get('kitchen/get-delivery', 'KitchenController@getGetDelivery');
    Route::get('kitchen/get-portioning', 'KitchenController@getGetPortioning');
    Route::get('kitchen/preparation', 'KitchenController@getPreparation');
    Route::get('kitchen/cities', 'KitchenController@getCities');
    Route::get('kitchen/get-countWithCities', 'KitchenController@getCountWithCities');
    Route::get('kitchen/labeling', 'KitchenController@getLabeling');
    Route::get('kitchen/labeling2', 'KitchenController@getLabeling2');
    Route::post('kitchen/get-preparation', 'KitchenController@postGetPreparation');
    Route::get('kitchen/get-preparation', 'KitchenController@postGetPreparation')->name('kitchen.getPreparation');
    Route::post('kitchen/get-labeling', 'KitchenController@postGetLabeling');
    Route::get('kitchen/get-labeling', 'KitchenController@getGetLabeling')->name('kitchen.getLabeling');
    Route::get('kitchen/get-labeling2', 'KitchenController@getGetLabeling2')->name('kitchen.getLabeling2');
    Route::get('kitchen/pkreport', 'KitchenController@getPkreport');
    Route::get('kitchen/get-pkreport', 'KitchenController@getGetPkreport');
    Route::get('kitchen/packaging', 'KitchenController@getPackaging');
    Route::get('kitchen/packaging2', 'KitchenController@getPackaging2');

    Route::post('kitchen/get-packaging', 'KitchenController@postGetPackaging');
    Route::get('kitchen/get-packaging', 'KitchenController@getGetPackaging');
    Route::get('kitchen/get-packaging2', 'KitchenController@getPackagingPdf');
    Route::get('kitchen/approve-all/{id}', 'KitchenController@getApproveAll');
    Route::get('kitchen/user-menu/{id}', 'KitchenController@getUserMenu');
    Route::get('kitchen/get-editOrder/{id}', 'KitchenController@getEditOrder');
    Route::get('kitchen/approve-order/{id}', 'KitchenController@getApproveOrder');
    Route::get('kitchen/users', 'KitchenController@getUsers');
    Route::get('kitchen/package-day/{package}/{id}/{userId}', 'KitchenController@getPackageDay');
    Route::post('kitchen/save', 'KitchenController@postSave');
    Route::post('kitchen/save-item', 'KitchenController@postSaveItem');





    Route::post('kitchen/get-delivery', 'KitchenController@postGetDelivery');
    Route::post('kitchen/get-portioning', 'KitchenController@postGetPortioning');


    Route::get('kitchen_summary/get-preparation', 'KitchenSummaryController@GetPreparation'); //

    Route::get('payments', 'PaymentsController@getIndex');
    Route::get('payments/confirm', 'PaymentsController@getConfirm');
    Route::get('payments/response', 'PaymentsController@getResponse');
    Route::get('payments/success', 'PaymentsController@getSuccess')->name("payment_success");
    Route::get('payments/fail/{id}/{referenceID}', 'PaymentsController@getFail');
    Route::get('payments/error/{id}', 'PaymentsController@getError');
});


Route::middleware(['web'])->group(function () {
    Route::post('user/remind', 'UserController@postRemind');
    Route::get('password/reset', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');;
    Route::get('password/forget', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});

// Resize
Route::get(RESIZE_PATH, array('uses' => 'Admin\FileController@resize'));
// Upload Files
Route::post(UPLOAD_PATH, array('uses' => 'Admin\FileController@upload_files'));

Route::get('pushme', 'HomeController@sendPushNotification');
Route::get('getrandmeals','HomeController@getrandmeals');
Route::get('getprogress','HomeController@getprogress');
