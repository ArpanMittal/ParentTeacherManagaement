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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

Route::get('/home', 'HomeController@index')->middleware('check.session');

Route::get('/login','HomeController@showLogin');

Route::post('/login','HomeController@doLogin')->name('login');

Route::get('/logout','HomeController@doLogout')->name('logout');

Route::get('/registerParent','AdminController@showRegisterParent')->middleware('check.session');

Route::post('/registerParent','AdminController@doRegisterParent')->name('registerParent');

Route::get('/registerTeacher','AdminController@showRegisterTeacher')->middleware('check.session');

Route::post('/registerTeacher','AdminController@doRegisterTeacher')->name('registerTeacher');

