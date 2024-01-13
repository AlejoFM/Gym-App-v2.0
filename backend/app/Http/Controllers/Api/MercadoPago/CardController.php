<?php

namespace App\Http\Controllers\Api\MercadoPago;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use MercadoPago\Client\Customer\CustomerCardClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class CardController extends Controller
{
    public $card;
    public function __construct(){
        $this->card = new CustomerCardClient();
        MercadoPagoConfig::setAccessToken(\env("MERCADOPAGO_API_KEY"));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }
    public function generateCard(){
        try {
            $request = [
                "token" => "9b2d63e00d66a8c721607214ceda233a",
                "card_id" => "123123"
            ];

            $card_generated = $this->card->create('1635598518', $request);

            return response()->json($card_generated);
        }catch (MPApiException $exception){
            return $exception->getApiResponse()->getContent();
        }
    }
}
