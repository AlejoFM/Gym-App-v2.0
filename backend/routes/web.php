<?php

use App\Http\Controllers\Api\MercadoPago\ProductController;
use App\Http\Controllers\Api\MercadoPago\WebhooksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhooks', [WebhooksController::class, 'getWebhook']);

Route::get('/success', [ProductController::class, 'getPaymentNotification'])->name("payment.success");
