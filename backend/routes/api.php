<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\MercadoPago\CardController;
use App\Http\Controllers\Api\MercadoPago\ClientController;
use App\Http\Controllers\Api\MercadoPago\ProductController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:api');

    Route::get('/user-managment/users', [UserController::class, 'index']);


    Route::post('/user-managment/users', [UserController::class, 'store']);
    Route::delete('/user-managment/users/{id}', [UserController::class, 'destroy']);

    Route::post('/clients-managment/', [ClientController::class, 'generateClient']);

    Route::post('/clients-managment/card', [CardController::class, 'generateCard']);

    Route::post('/clients-managment/{customer_id}/cards', [MembershipController::class, 'generateMercadoPagoCard']);
    Route::post('/clients-managment/preferences', [MembershipController::class, 'generateMercadoPagoPreference']);


    Route::post('/membership-managment/suscriptions/payments-suscriptions', [MembershipController::class, 'generateMembershipSuscription']);
    Route::post('/membership-managment/suscriptions/generation-suscriptions', [MembershipController::class, 'generatePreapprovalPlan']);
    Route::get('/membership-managment/suscriptions/{id}', [MembershipController::class, 'getSuscriptionPlan']);
    Route::put('/membership-managment/suscriptions/{id}', [MembershipController::class, 'updateSuscriptionPlan']);


    Route::post('/membership-managment/one-time-payments', [ProductController::class, 'createProduct']);
    Route::post('/membership-managment/pay-membership', [ProductController::class, 'generatePay']);

    Route::get('/membership-managment/payment-status/{payment_id}', [ProductController::class, 'getPaymentNotification']);
    Route::get('/membership-managment/one-time-payments/{id}', [MembershipController::class, 'getMembershipOneTimePayment']);
    Route::put('/membership-managment/one-time-payments/{id}', [MembershipController::class, 'updateMembershipOneTimePayment']);

    Route::post('/membership-managment/payments', [MembershipController::class, 'generateMembershipSuscription']);
    Route::post('/membership-managment/generation', [MembershipController::class, 'generatePreapprovalPlan']);
    Route::post('/membership-managment/{id}', [MembershipController::class, 'getSuscriptionPlan']);
});
