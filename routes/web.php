<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'HomeController@index')->name('home');

Route::get('/settings', 'SettingsController@index')->name('settings');
Route::post('/settings/store/user', 'SettingsController@storeUser')->name('settings.store.user');
Route::put('/settings/store/password', 'SettingsController@storePassword')->name('settings.store.password');
Route::get('/settings/remove/current-user', 'SettingsController@removeCurrentUser')->name('settings.remove.user.current');

Route::get('/users', 'UserController@index')->middleware('can:manage-user')->name('users');
Route::put('/users/update', 'UserController@updateUserAccount')->middleware('can:manage-user')->name('user.update');
Route::put('/users/remove', 'UserController@removeUserAccount')->middleware('can:manage-user')->name('user.remove');

Route::get('/category', 'CategoryController@index')->middleware('can:manage-category')->name('category.list');
Route::post('/category/store', 'CategoryController@storeCategory')->middleware('can:manage-category')->name('category.store');
Route::get('/category/delete/{id}', 'CategoryController@deleteCategory')->middleware('can:manage-category')->name('category.delete');
Route::post('/category/list', 'CategoryController@getAllCategories')->name('category.list.all');

Route::get('/equipment/manage/list', 'EquipmentController@indexForManage')->middleware('can:manage-equipment')->name('equipment.manage.list');
Route::post('/equipment/manage/store', 'EquipmentController@storeEquipment')->middleware('can:manage-equipment')->name('equipment.manage.store');
Route::post('/equipment/manage/update', 'EquipmentController@updateEquipment')->middleware('can:manage-equipment')->name('equipment.manage.update');
Route::delete('/equipment/manage/remove', 'EquipmentController@removeEquipment')->middleware('can:manage-equipment')->name('equipment.manage.remove');
Route::get('/equipment', 'EquipmentController@index')->middleware('can:equipment')->name('equipment.list');
Route::post('/equipment/get', 'EquipmentController@getEquipment')->name('equipment.get');

Route::post('/booking/request', 'BookingController@requestBooking')->middleware('can:booking')->name('booking.request');
Route::get('/booking/manage/list', 'BookingController@indexForManage')->middleware('can:manage-booking')->name('booking.manage.list');
Route::delete('/booking/manage/remove', 'BookingController@removeBooking')->middleware('can:manage-booking')->name('booking.manage.remove');
Route::post('/booking/get', 'BookingController@getBooking')->name('booking.get');
Route::post('/booking/manage/update-status', 'BookingController@updateBookingStatus')->middleware('can:manage-booking')->name('booking.manage.update_status');
Route::get('/booking', 'BookingController@index')->middleware('can:booking')->name('booking.list');
Route::post('/booking/cancel', 'BookingController@cancelBooking')->middleware('can:booking')->name('booking.cancel');
Route::post('/booking/return', 'BookingController@returnBooking')->middleware('can:booking')->name('booking.return');
Route::post('/booking/update-period', 'BookingController@updateBookingPeriod')->middleware('can:booking')->name('booking.update_period');