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

Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/home', 'HomeController@show')->name('home');


//Admin panel

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group([
    'prefix' => '/admin',
    'middleware' => 'admin'
], function () {
    Route::get('/panel','AdminController@servicesAndDevices')->name('servicesAndDevices');
    Route::get('/prices','AdminController@prices')->name('prices');
    Route::get('/profile','AdminController@profile')->name('profile');
    Route::get('/chat','AdminController@chat')->name('chat');
    Route::get('/map','AdminController@showMap')->name('map');
    Route::post('/open_conversation','AdminController@openConversation');
    Route::post('/notifications','AdminController@getNotes');
    Route::post('/delete_conversation','AdminController@deletePanel');
    Route::post('/add_service','AdminController@addService');
    Route::post('/update_service','AdminController@updateService');
    Route::post('/delete_service','AdminController@deleteService');
    Route::post('/add_device','AdminController@addDevice');
    Route::post('/update_device','AdminController@updateDevice');
    Route::post('/delete_device','AdminController@deleteDevice');
    Route::post('/add_service_product','AdminController@addServiceProduct');
    Route::post('/update_sp_price','AdminController@updateServiceProduct');
    Route::post('/delete_service_product','AdminController@delServiceProduct');
    Route::post('/add_branch','AdminController@addBranch');
});

Route::post('/send_image','HomeController@imageUpload');
Route::post('/send_mail_user','MailController@userSendMail');
