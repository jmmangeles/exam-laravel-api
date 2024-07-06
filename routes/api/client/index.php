<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response('Ok!');
});

// Route::get('specialties', 'GetSpecialtiesController');

// Route::group(['middleware' => 'auth:client'], function () {
//     // Route::group([
//     //     'namespace' => 'Clinic',
//     // ], function() {
//     //     Route::get('clinics', 'GetClinicList');
//     // });

//     // Route::post('invitation', 'SendClientInvitation');
//     // Route::get('specialties-services', 'GetSpecialtiesAndServices');
//     // Route::get('profile/{id}', 'GetProfileDetails');
//     // Route::post('invite/dentist', 'InviteDentist');
//     // Route::post('cancel-invite/dentist', 'CancelDentistInvite');
//     // Route::get('affiliated-dentists', 'GetAffiliatedDentists');
//     // Route::get('unread-notifications', 'GetUnreadNotifications');
// });

Route::group([
    'namespace' => 'Auth',
    'prefix' => 'auth'
], function() {
    Route::post('login', 'AuthController@login');
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'Register\RegisterController');
    // Route::post('forgot-password/change-password', 'ForgotPassword\ChangePassword');
});
