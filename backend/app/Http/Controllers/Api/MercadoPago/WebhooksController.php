<?php

namespace App\Http\Controllers\Api\MercadoPago;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use Symfony\Component\HttpFoundation\Response;
class WebhooksController extends Controller
{

    public function __construct(){
        MercadoPagoConfig::setAccessToken(env("MERCADOPAGO_API_KEY"));
    }
    public function getWebhook(Request $request){
        try {
        $payment_id = $request['data']['id'];
        $payment = new PaymentController();
        $data = $payment->getPaymentStatus($payment_id);

        Log::info('Payment Data: ' . $data);

        }catch (MPApiException $exception){
            Log::error('Exception caught: ' . $exception->getMessage());
            Log::error('API Response: ' . $exception->getApiResponse()->getContent());
            return $exception->getApiResponse()->getContent();
        }
        return response()->json(['success' => 'success'],Response::HTTP_OK);
    }
}
