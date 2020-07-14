<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('me', 'AuthController@me');
Route::post('login', 'AuthController@login');
//Route::post('register', 'AuthController@register');
Route::resource('manage-users', 'AdminController');
//Route::post('add-doctor', 'AdminController@add_doctor');
Route::resource('doctors', 'DoctorController');

Route::resource('role', 'RoleController');
Route::get('user_roles', 'RoleController@roles_without_doctor');
Route::resource('status', 'StatusController');
Route::resource('district', 'DistrictController');

Route::resource('patient', 'PatientController');
Route::post('add_patient/{id}', 'PatientController@add_patient_record');
Route::resource('diagnosis', 'DiagnosisController');
Route::resource('followup', 'FollowupController');
Route::get('patients_by_field_officer', 'PatientController@show_patients_by_field_officer');
Route::post('officers_by_district/{id}', 'AdminController@field_officer_by_district');
Route::get('field_officers', 'AdminController@get_all_field_officers');
Route::get('patient_details/{id}', 'FollowupController@followups_by_patient');

Route::get('send_reminder', 'ScheduleController@index');