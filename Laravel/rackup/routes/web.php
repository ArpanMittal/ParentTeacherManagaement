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

Route::get('/editProfile','AdminController@editProfileDetails')->name('editProfileDetails')->middleware('check.session');
Route::post('/editProfile{id}','AdminController@updateProfileDetails')->name('updateProfileDetails');

Route::resource('registerParent','RegisterParentController');
Route::resource('registerTeacher','RegisterTeacherController');

Route::get('/uploadFile','UploadController@showUpload')->middleware('check.session');
Route::post('/uploadFile','UploadController@doUpload')->name('uploadFile');
Route::get('/uploadLink','UploadController@showUploadLink')->middleware('check.session');
Route::post('/uploadLink','UploadController@doUploadLink')->name('uploadLink');
Route::post('createCategory','UploadController@createCategory')->name('createCategory');
Route::get('category{id}','UploadController@getDropdownContent');
Route::post('sendNotification{id}','UploadController@sendNotification');
Route::resource('upload','UploadController');

Route::get('/calendar', ['uses' => 'EventController@calendar'])->name('calendar')->middleware('check.session');
Route::post('/getcalendar', ['uses' => 'EventController@getCalendar'])->name('getCalendar')->middleware('check.session');
Route::put('calendar_events/{teacherId}/{id}','CalendarEventController@edit1');
Route::get('showAppointments{id}','CalendarEventController@showAppointments')->name('showAppointments')->middleware('check.session');
Route::resource('calendar_events', 'CalendarEventController');

Route::get('/teacherCalendar', ['uses' => 'EventController@teacherCalendar'])->name('teacherCalendar')->middleware('check.session');
Route::get('getConfirm{id}','AppointmentController@getConfirm')->name('getConfirm')->middleware('check.session');
Route::post('postConfirm{id}','AppointmentController@postConfirm')->name('postConfirm');
Route::post('changeContactNumber{id}','AppointmentController@changeContactNumber')->name('changeContactNumber');
Route::get('getCancel{id}','AppointmentController@getCancel')->name('getCancel')->middleware('check.session');
Route::post('postCancel{id}','AppointmentController@postCancel')->name('postCancel');
Route::get('showFreeSlots{id}','AppointmentController@showFreeSlots')->name('showFreeSlots')->middleware('check.session');
Route::resource('appointments','AppointmentController');

Route::resource('school_events','SchoolEventController');



