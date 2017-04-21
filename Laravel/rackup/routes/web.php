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

Route::get('/','HomeController@doLogin');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('check.session');

Route::get('/login','HomeController@showLogin');

Route::post('/login','HomeController@doLogin')->name('login');

Route::get('/logout','HomeController@doLogout')->name('logout');

Route::get('/registerParent','AdminController@showRegisterParent')->middleware('check.session');

Route::post('/registerParent','AdminController@doRegisterParent')->name('registerParent');

Route::get('/registerTeacher','AdminController@showRegisterTeacher')->middleware('check.session');

Route::post('/registerTeacher','AdminController@doRegisterTeacher')->name('registerTeacher');

Route::get('/upload','UploadController@showUpload')->middleware('check.session');

Route::post('/upload','UploadController@doUpload')->name('upload');

Route::get('/uploadLink','UploadController@showUploadLink')->middleware('check.session');

Route::post('/uploadLink','UploadController@doUploadLink')->name('uploadLink');

Route::get('/showAppointmentDetails','AppointmentController@getAppointmentDetails')->middleware('check.session');

Route::post('/showAppointmentDetails','AppointmentController@showAppointmentDetails')->name('showAppointmentDetails');

Route::get('/teacherAppointments','AppointmentController@getTeacherAppointments')->name('teacherAppointments')->middleware('check.session');;

Route::post('/teacherAppointments','AppointmentController@confirmAppointments');

Route::get('/insertAppointmentsSlots','AppointmentController@getAppointmentsSlots')->middleware('check.session');;

Route::post('/insertAppointmentsSlots','AppointmentController@postAppointmentsSlots')->name('insertAppointmentsSlots');

Route::get('/teachersList','AdminController@getTeachersList')->name('teachersList')->middleware('check.session');;

Route::get('/parentsList','AdminController@getParentsList')->name('parentsList')->middleware('check.session');;

Route::get('/notifications','NotificationController@sendDownstreamMessage');

