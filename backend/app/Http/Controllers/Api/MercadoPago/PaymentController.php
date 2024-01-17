<?php

namespace App\Http\Controllers\Api\MercadoPago;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public $payment;
    public function __construct(){
        $this->payment = new PaymentClient();
        MercadoPagoConfig::setAccessToken(\env("MERCADOPAGO_API_KEY"));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    public function getPaymentStatus($payment_id){
        try {
        $payment_data = $this->payment->get($payment_id)->getResponse()->getContent();
        Log::info($payment_data);
        return $payment_data;
        }catch (MPApiException $exception){
            Log::info($exception->getApiResponse()->getContent());
            return $exception->getApiResponse()->getContent();
        }
    }
}
