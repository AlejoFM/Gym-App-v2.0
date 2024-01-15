<?php

namespace App\Http\Controllers\Api\MercadoPago;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class ProductController extends Controller
{
    public $product;
    public function __construct(){
        $this->product = new PreferenceClient();
        MercadoPagoConfig::setAccessToken(\env("MERCADOPAGO_API_KEY"));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    public function createProduct(Request $request){

        $membership = new Membership();
        $membership = $membership->find($request['id']);


        try {
            $product_data = $this->product->create([
                "external_reference" => $request['id'],
                "items" => array(
                    array(
                        "id" => "4567",
                        "title" => "Dummy Title",
                        "description" => "Dummy description",
                        "category_id" => "eletronico",
                        "quantity" => 1,
                        "currency_id" => "BRL",
                        "unit_price" => 100
                    )
                ),
                "payment_methods" => [
                    "default_payment_method_id" => "master",
                    "excluded_payment_types" => array(
                        array(
                            "id" => "ticket"
                        )
                    ),
                    "installments" => 12,
                    "default_installments" => 1
                ],

                "notification_url" => "https://7c71-186-136-1-244.ngrok-free.app/webhooks",
            ]);
            return response()->json(['data' => $product_data, 'membership_data' => $membership]);
        }catch (MPApiException $exception){
            return $exception->getApiResponse()->getContent();
        }
    }
    public function getPaymentNotification(Request $request)
    {
        $client = new PaymentClient();
        $payment_id = $request['payment_id'];
        $payment = $client->get($payment_id);

        return 200;
    }
}
