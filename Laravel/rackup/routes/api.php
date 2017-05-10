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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login','HomeController@returnToken')->name('login');
Route::post('/getContent','UploadController@getContent')->name('getContent');
Route::post('/slotDetails','AppointmentController@sendAppointmentSlotDetails')->name('slotDetails');
Route::post('/bookAppointments','AppointmentController@bookAppointments')->name('bookAppointments');
Route::post('/sendEvent','AppointmentController@sendEvent')->name('sendEvent');
Route::post('/updateEvent','AppointmentController@updateEvent')->name('updateEvent');
//Route::post('/uploadLink','UploadController@returnUploadLink');