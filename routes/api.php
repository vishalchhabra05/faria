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



Route::post('register', '\App\Http\Controllers\Api\UserController@register');
Route::post('otp-verify', '\App\Http\Controllers\Api\UserController@OtpVerify');
Route::post('otp-resend','\App\Http\Controllers\Api\UserController@ResendOtp');
Route::get('walkthrough','\App\Http\Controllers\Api\UserController@WalkthroughGet');
Route::get('/cms/get','\App\Http\Controllers\Api\UserController@webpages');
Route::post('login','\App\Http\Controllers\Api\UserController@login');
Route::post('forgot-password','\App\Http\Controllers\Api\UserController@forgotPassword');       
Route::post('otp-match','\App\Http\Controllers\Api\UserController@AfterMAtch');
Route::post('create-password','\App\Http\Controllers\Api\UserController@NewPassword');
Route::get('country-code','\App\Http\Controllers\Api\CustomerController@GetCountery');
Route::get('cron-job','\App\Http\Controllers\Api\CustomerController@cronJob');
Route::post('payment','\App\Http\Controllers\Api\CustomerController@paymentFull'); //testing
Route::get('auto-active','\App\Http\Controllers\Api\CustomerController@CronjobActivejob');


//service Provider
Route::post('/provider/register', '\App\Http\Controllers\Api\UserController@register');
Route::post('/provider/login','\App\Http\Controllers\Api\ProviderController@login');
Route::get('/provider/state','\App\Http\Controllers\Api\ProviderController@GetState');
Route::post('/provider/otp-verify', '\App\Http\Controllers\Api\UserController@OtpVerify');
Route::post('/provider/otp-resend','\App\Http\Controllers\Api\UserController@ResendOtp');
Route::post('/provider/forgot-password','\App\Http\Controllers\Api\ProviderController@forgotPassword');       
Route::post('/provider/otp-match','\App\Http\Controllers\Api\UserController@AfterMAtch');
Route::post('/provider/create-password','\App\Http\Controllers\Api\UserController@NewPassword');

Route::group(['middleware' => ['auth:api','verifyIfUser']], function () {
    Route::get('profile-get','\App\Http\Controllers\Api\UserController@GetProfile');
    Route::post('update-profile','\App\Http\Controllers\Api\UserController@UpdateProfile');
    Route::post('change-password','\App\Http\Controllers\Api\UserController@ChangePassword');
    Route::post('card-save','\App\Http\Controllers\Api\UserController@CardSave');
    Route::post('update-card','\App\Http\Controllers\Api\UserController@UpdateCard');
    Route::post('ride-booking','\App\Http\Controllers\Api\CustomerController@rideBooking');
    Route::get('service-slider','\App\Http\Controllers\Api\CustomerController@ServiceGet');
    
    Route::get('category','\App\Http\Controllers\Api\CustomerController@CategoryGet');
    Route::post('service-list','\App\Http\Controllers\Api\CustomerController@getServicecheck');
    Route::post('check-myarea','\App\Http\Controllers\Api\CustomerController@CheckService');
    Route::post('assine-service','\App\Http\Controllers\Api\CustomerController@AssineService');
    Route::post('disable-services','\App\Http\Controllers\Api\CustomerController@disableServices');
    Route::get('get-address','\App\Http\Controllers\Api\CustomerController@GetAddress');
    Route::get('get-pending','\App\Http\Controllers\Api\CustomerController@PendingJob');
    Route::get('active-job','\App\Http\Controllers\Api\CustomerController@ActiveJob');
    Route::get('complite-job','\App\Http\Controllers\Api\CustomerController@CompliteJob');
    Route::post('cancel-job','\App\Http\Controllers\Api\CustomerController@CancelJob');
    Route::post('update-latlong','\App\Http\Controllers\Api\CustomerController@UpdateLatLong');
    Route::post('job-detail','\App\Http\Controllers\Api\CustomerController@GetJobDetail');
    Route::post('review','\App\Http\Controllers\Api\CustomerController@Review');
    Route::post('dispute','\App\Http\Controllers\Api\CustomerController@Dispute');
    Route::post('diff-get','\App\Http\Controllers\Api\CustomerController@getDif');
    Route::get('notification','\App\Http\Controllers\Api\CustomerController@GetNotification');
    Route::get('/provider/notification','\App\Http\Controllers\Api\CustomerController@GetNotification');
    Route::post('/provider/update-lat-lng','\App\Http\Controllers\Api\UserController@updateLatLng');
    Route::post('contact-us','\App\Http\Controllers\Api\CustomerController@ContactUs');
    Route::post('/provider/contact-us','\App\Http\Controllers\Api\CustomerController@ContactUs');
    Route::get('home-screen','\App\Http\Controllers\Api\CustomerController@HomeScreen');
    //Route::post('/cab/get-distance','\App\Http\Controllers\Api\CustomerController@getDistance');

    Route::post('delete-card','\App\Http\Controllers\Api\CustomerController@DeleteCard');
    Route::post('add-primary','\App\Http\Controllers\Api\CustomerController@PrimaryCard');
    Route::get('get-dispute','\App\Http\Controllers\Api\CustomerController@DisputeJobList');
    Route::get('quote-get','\App\Http\Controllers\Api\CustomerController@Quoteget');
    Route::post('accept-code','\App\Http\Controllers\Api\CustomerController@Acceptcode');

    Route::get('reason-get','\App\Http\Controllers\Api\UserController@ReasonGet');
    Route::get('/provider/reason-get','\App\Http\Controllers\Api\UserController@ReasonGet');
    Route::post('/provider/job-details','\App\Http\Controllers\Api\ProviderController@jobDetail');
    //testing
    Route::get('address','\App\Http\Controllers\Api\CustomerController@getUserAddress');

    Route::get('logout','\App\Http\Controllers\Api\UserController@LogoutUser');
    Route::get('/provider/logout','\App\Http\Controllers\Api\UserController@LogoutUser');
    Route::post('status','\App\Http\Controllers\Api\CustomerController@Statusget');
    Route::post('/client-accept-order','\App\Http\Controllers\Api\CustomerController@clientAcceptOrder');
    Route::post('/distance-calculation','\App\Http\Controllers\Api\CustomerController@distanceCalculation');
    Route::post('/provider-quotation','\App\Http\Controllers\Api\ProviderController@providerQuotation');
    Route::post('/get-client-quotation','\App\Http\Controllers\Api\CustomerController@getQuotation');

  

    //Service Provider App
   Route::post('/provider/step1','\App\Http\Controllers\Api\ProviderController@step1');
   Route::post('/provider/step2','\App\Http\Controllers\Api\ProviderController@step2');
    Route::post('/provider/step3','\App\Http\Controllers\Api\ProviderController@step3');
    Route::post('/provider/step4','\App\Http\Controllers\Api\ProviderController@step4');
    Route::post('/provider/step5','\App\Http\Controllers\Api\ProviderController@step5');
    Route::post('/provider/step6','\App\Http\Controllers\Api\ProviderController@step6');
    Route::post('/provider/step7','\App\Http\Controllers\Api\ProviderController@step7');
    Route::post('/provider/step8','\App\Http\Controllers\Api\ProviderController@step8');
    Route::post('/provider/step9','\App\Http\Controllers\Api\ProviderController@step9');
    Route::get('/provider/confirmDetail','\App\Http\Controllers\Api\ProviderController@confirmDetail');
    Route::get('/provider/depostDetail','\App\Http\Controllers\Api\ProviderController@depostDetail');
   Route::post('/provider/checkstep','\App\Http\Controllers\Api\ProviderController@checkStep');
   Route::get('/provider/service','\App\Http\Controllers\Api\ProviderController@serviceList');
   Route::get('/provider/service-request','\App\Http\Controllers\Api\ProviderController@ServiceRequest');
   Route::post('/provider/reject-request','\App\Http\Controllers\Api\ProviderController@rejectRequest');
   Route::post('/provider/accept-request','\App\Http\Controllers\Api\ProviderController@AcceptRequest');
   Route::post('/provider/in-process','\App\Http\Controllers\Api\ProviderController@Inprocess');
   Route::post('/provider/order-detail','\App\Http\Controllers\Api\ProviderController@OrderDetail');
   Route::post('/provider/update-jobstatus','\App\Http\Controllers\Api\ProviderController@JobStatus');
   Route::post('/provider/reschedule','\App\Http\Controllers\Api\ProviderController@Reschedule');
   Route::post('/provider/another-visit','\App\Http\Controllers\Api\ProviderController@AnotherVisit');
   Route::post('/provider/invoice-save','\App\Http\Controllers\Api\ProviderController@InvoiceSave');
   Route::post('/provider/cancel-job','\App\Http\Controllers\Api\ProviderController@CancelJob');
   Route::get('/provider/complite-job','\App\Http\Controllers\Api\ProviderController@CompliteJob');
   Route::get('/provider/get-profile','\App\Http\Controllers\Api\ProviderController@ProfileGet');
   Route::post('/provider/update-profile','\App\Http\Controllers\Api\ProviderController@profileUpdate');
   Route::get('/provider/get-feedback','\App\Http\Controllers\Api\ProviderController@Feedback');
   Route::post('/provider/send-quote','\App\Http\Controllers\Api\ProviderController@SendQuote');
   
   Route::get('/provider/my-earnings','\App\Http\Controllers\Api\ProviderController@MyEarnings');
   Route::get('/provider/payment-history','\App\Http\Controllers\Api\ProviderController@PaymentHistory');
   Route::post('/provider/delete-service','\App\Http\Controllers\Api\ProviderController@deleteService');
   
   Route::get('/provider/all-step-data','\App\Http\Controllers\Api\ProviderController@AllStep');
   Route::get('/provider/update-register','\App\Http\Controllers\Api\ProviderController@UpdateRegister');
   Route::post('/provider/change-password','\App\Http\Controllers\Api\UserController@ChangePassword');
     //User Address Delete
   Route::post('/delete-address','\App\Http\Controllers\Api\UserController@DeleteAddress');
   //get service using service id
   Route::post('/provider/get-service','\App\Http\Controllers\Api\ProviderController@ServiceRequestget');
   //Check Coupon
   Route::post('/check-coupon','\App\Http\Controllers\Api\CustomerController@CheckCoupon');  
   Route::get('/provider/service-list','\App\Http\Controllers\Api\ProviderController@AllserviceList');
   Route::get('/provider/my-service-list','\App\Http\Controllers\Api\ProviderController@myServiceList');
   Route::get('/provider/add-more-service-list','\App\Http\Controllers\Api\ProviderController@addMoreServiceList');

   Route::post('/provider/set-service-status','\App\Http\Controllers\Api\ProviderController@setServiceStatus');

   Route::post('/provider/chat-notificaion','\App\Http\Controllers\Api\ProviderController@ChatNotificaion');
   
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//MapApi
//AIzaSyDkTqhcXmaE1rFi-Prdm6flnWX3pNUuPRI
// Fallback route incase anything goes wrong
Route::fallback(function(){
    return response()->json(['status_code' => 404, 'error' => 'Resource not found.'], 404);
})->name('fallback');