<?php

namespace App\Http\Controllers\Api\MercadoPago;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use Symfony\Component\HttpFoundation\Response;

class WebhooksController extends Controller
{

    public function __construct(){
        MercadoPagoConfig::setAccessToken(env("MERCADOPAGO_API_KEY"));
    }
    public function getWebhook(){

        return response()->json(['success' => 'success'],Response::HTTP_OK);
    }
}
