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
Route::get('/cms/get/{slug?}','PageController@Mobile');
Route::get('/clear-cache', function() {

    $exitCode = Artisan::call('cache:clear');
    dd("heello");
    // return what you want
});
Route::group(['middleware' => ['adminrole','disablepreventback']], function () {
    Route::prefix('admin')->group(function() {
    Route::get('/', 'AdminController@index');
    Route::get('/{slug}','UserController@index');
    Route::get('/user/add','UserController@create');
    Route::post('/user/store','UserController@store');
    Route::get('/user/delete/{slug}','UserController@destroy');
    Route::get('/user/status/{slug}','UserController@userstatus');
    Route::get('/user/view/{slug}','UserController@show');
    Route::get('/user/edit/{slug}','UserController@edit');
    Route::post('/user/update/{slug}','UserController@update');


    /**
     * Category
     */
    Route::get('/category/category','CategoryController@index');
    Route::get('/category/add','CategoryController@create');
    Route::post('/category/store','CategoryController@store');
    Route::get('/category/delete/{slug}','CategoryController@destroy');
    Route::get('/category/edit/{slug}','CategoryController@edit');
    Route::post('/category/update/{slug}','CategoryController@update');
    Route::get('/category/status/{slug}','CategoryController@userstatus');


    /**
     * Service
     */
    Route::get('/service/service','ServiceController@index');
    Route::get('/service/add','ServiceController@create');
    Route::post('/service/store','ServiceController@store');
    Route::get('/service/delete/{slug}','ServiceController@destroy');
    Route::get('/service/edit/{slug}','ServiceController@edit');
    Route::post('/service/update/{slug}','ServiceController@update');
    Route::get('/service/status/{slug}','ServiceController@userstatus');
    Route::get('/service/view/{slug}','ServiceController@show');
    Route::get('/service/appstatus/{slug}','ServiceController@appstatus');


    	/**Cms Page Start */
	Route::get('/cms/cms','PageController@index');
	Route::get('/cms/add','PageController@create');
	Route::post('/cms/store','PageController@store');
	Route::get('/cms/view/{slug}','PageController@show');
	Route::get('/cms/edit/{slug}','PageController@edit');
	Route::post('/cms/update','PageController@update');
	Route::get('/cms/status/{slug}','PageController@updateStatus');
	Route::get('/cms/delete/{slug}','PageController@destroy');
	Route::post('ckeditor/upload', 'PageController@upload')->name('ckeditor.upload');
    /**Cms Page End */
    

    /**
     * Add Service Provider
     */
    Route::get('/provider/add/{slug?}', 'ProviderController@create');
    Route::post('/provider/add/{slug?}','ProviderController@postCreate');

    Route::get('/provider/step1/{slug}', 'ProviderController@createStep1');
    Route::post('/provider/step1/{slug}', 'ProviderController@postCreateStep1');

    Route::get('/provider/step2/{slug}', 'ProviderController@createStep2');
    Route::post('/provider/step2/{slug}', 'ProviderController@postCreateStep2');

    Route::get('/provider/step3/{slug}', 'ProviderController@createStep3');
    Route::post('/provider/step3/{slug}', 'ProviderController@postCreateStep3');

    Route::get('/provider/step4/{slug}', 'ProviderController@createStep4');
    Route::post('/provider/step4/{slug}', 'ProviderController@postCreateStep4');

    Route::get('/provider/step5/{slug}', 'ProviderController@createStep5');
    Route::post('/provider/step5/{slug}', 'ProviderController@postCreateStep5');

    Route::get('/provider/step6/{slug}', 'ProviderController@createStep6');
    Route::post('/provider/step6/{slug}', 'ProviderController@postCreateStep6');

    Route::get('/provider/step7/{slug}', 'ProviderController@createStep7');
    Route::post('/provider/step7/{slug}', 'ProviderController@postCreateStep7');

    Route::get('/provider/step8/{slug}', 'ProviderController@createStep8');
    Route::post('/provider/step8/{slug}', 'ProviderController@postCreateStep8');

    Route::get('/provider/step9/{slug}', 'ProviderController@createStep9');
    Route::post('/provider/step9/{slug}', 'ProviderController@postCreateStep9');

    Route::get('/provider/provider', 'ProviderController@index');
    Route::get('/provider/status/{slug}','ProviderController@updateApprove');
    Route::get('/provider/delete/{slug}','ProviderController@destroy');
    Route::get('/provider/view/{slug}','ProviderController@show');
    Route::get('/provider/profileStatus/{slug}','ProviderController@ProfileStatus');
    Route::get('/provider/provider/edit/{slug}','ProviderController@edit');
  /**End Service Proovider */

    /**Service Provider Bank Detail */
    Route::get('/bank/list','BankController@index');
    Route::get('/bank/view/{slug}','BankController@show');
    /**End Bank */

    Route::get('/profile/view','AdminController@show');
    Route::get('/profile/view','AdminController@show');
    Route::get('/profile/edit','AdminController@edit');
    Route::post('/profile/update','AdminController@update');

    /**
     * Service Provider Update Profile Request
     */
    Route::get('/profile/request','ProviderController@UpdateProfileRequest');


    /**
     * Walk Screen
     */
    Route::get('/walkthrough/walkthrough','WalkthroughController@index');
    Route::get('/walkthrough/add','WalkthroughController@create');
    Route::post('/walkthrough/store','WalkthroughController@store');
    Route::get('/walkthrough/delete/{slug}','WalkthroughController@destroy');
    Route::get('/walkthrough/edit/{slug}','WalkthroughController@edit');
    Route::post('/walkthrough/update','WalkthroughController@update');

    /**Varient Controller */
    Route::get('/varient/varient','VarientController@index');
    Route::get('/varient/add','VarientController@create');
    Route::post('/varient/store','VarientController@store');
    Route::get('/varient/edit/{slug?}','VarientController@edit');
    Route::get('/varient/delete/{slug?}','VarientController@destroy');
    Route::post('/varient/update/{slug?}','VarientController@update');

    /**Price Controller */
    Route::get('/price/price','PriceController@index');
    Route::get('/price/add','PriceController@create');
    Route::post('/price/store','PriceController@store');
    Route::get('/price/edit/{slug?}','PriceController@edit');
    Route::post('/price/update/{slug?}','PriceController@update');
    Route::get('/price/delete/{slug?}','PriceController@destroy');
    /** **/
    
    /**Tax Controller */
    Route::get('/tax/tax','TaxController@index');
    Route::get('/tax/add','TaxController@create');
    Route::post('/tax/store','TaxController@store');
    Route::get('/tax/edit/{id?}','TaxController@edit');
    Route::post('/tax/update/{id?}','TaxController@update');
    Route::get('/tax/delete/{id?}','TaxController@destroy');

    /**Contact Us Listing */
    Route::get('/contact/all','ContactusController@index');
    Route::get('/contact/delete/{slug}','ContactusController@destroy');
    Route::get('/contact/view/{slug}','ContactusController@show');


    Route::get('/job/request','JobController@index');
    Route::get('/job/view/{slug}','JobController@show');
    Route::get('/reschedule/request','JobController@reschedule');
    Route::get('/track/user/{slug}','JobController@Track');
    Route::get('/job/accept/{slug}','JobController@acceptJob');
    Route::post('/job/quote/{slug}','JobController@jobQuote');
    Route::get('/job/reyaltime/{slug}','JobController@reyaltime');


    /**
     * Banner
     */
    Route::get('/banner/list','BannerController@index');
    Route::get('/banner/add','BannerController@create');
    Route::post('/banner/store','BannerController@store');
    Route::get('/banner/delete/{slug}','BannerController@destroy');
    Route::get('/banner/edit/{slug}','BannerController@edit');
    Route::post('/banner/update/{slug}','BannerController@update');
    Route::get('/banner/appstatus/{slug}','BannerController@appstatus');

    Route::get('/cancel-master/master','CancelPriceController@index');
    Route::get('/cancel-master/add','CancelPriceController@create');
    Route::post('/cancel-master/store','CancelPriceController@store');
    Route::get('/cancel-master/delete/{slug}','CancelPriceController@destroy');
    Route::get('/cancel-master/edit/{slug}','CancelPriceController@edit');
    Route::post('/cancel-master/update/{slug}','CancelPriceController@update');
    Route::get('/base-fare/base-fare','CancelPriceController@basePrice');
    Route::post('/cancel-master/update-base-price','CancelPriceController@updateBaseFare');

    
    Route::get('/dispute/manager','DisputeController@index');
    Route::get('/dispute/view/{id}','DisputeController@show');
    Route::get('/dispute/edit/{order_id}','DisputeController@edit');
    Route::post('/dispute/update/{order_id}','DisputeController@update');


    Route::get('/finances/manage','FinancesController@index');
    Route::get('/finances/view/{slug}','FinancesController@show');
    Route::get('/finances/listing/{slug}','FinancesController@listing');
    Route::get('/payment/toprovider/{slug}','FinancesController@Paymet');

    //Discountn 
   
    Route::get('/discount/all','DiscountController@index');
    Route::get('/discount/add','DiscountController@create');
    Route::post('/discount/store','DiscountController@store');
    Route::get('/discount/delete/{slug}','DiscountController@destroy');
    Route::get('/discount/edit/{slug}','DiscountController@edit');
    Route::post('/discount/update/{slug}','DiscountController@update');

    //City 
    Route::get('city/city','CityController@index');
    Route::get('city/add','CityController@create');
    Route::post('city/store','CityController@store');
    Route::get('city/delete/{slug}','CityController@destroy');
    Route::get('city/edit/{slug}','CityController@edit');
    Route::post('city/update/{slug}','CityController@update');

    //Add Price
    Route::get('service/addprice/{slug}','ServiceController@createPrice');
    Route::post('addprice/store','ServiceController@storePrice');
    Route::post('addprice/update','ServiceController@UpdatePrice');
//->name('message-user-data')
//->name('message-detail')
    Route::get('message/message/{slug}','MessageController@index');
    Route::post('/message-user-data','NewmessageController@userprofile');
    Route::get('/message-detail/{slug}/{id}','NewmessageController@userDetail');
    });
});