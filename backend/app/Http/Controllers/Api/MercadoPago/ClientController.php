<?php

namespace App\Http\Controllers\Api\MercadoPago;

use App\Http\Controllers\Controller;
use MercadoPago\Client\Customer\CustomerClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class ClientController extends Controller
{
    public $client;
    public function __construct(){
        $this->client = new CustomerClient();
        MercadoPagoConfig::setAccessToken(\env("MERCADOPAGO_API_KEY"));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }
    public function generateClient(){
        try {
            $body = [
                'email' => '222ddd@tadr.com',
                'first_name' => 'Jhon',
                'last_name' => 'Doe',
                'phone' => [
                    'area_code' => '55',
                    'number' => '991234567'
                ],
                'identification' => [
                    'type' => 'CPF',
                    'number' => '12345678900'
                ],
                'default_address' => 'Home',
                'address' => [
                    'id' => '123123',
                    'zip_code' => '2222',
                    'street_name' => 'Rua Exemplo',
                    'street_number' => 123,
                    'city' => ['name' => 'basas']
                ],
                'date_registered' => '2023-09-07T11:37:30.000-04:00',
                'description' => 'Description del user',
                'default_card' => ''
            ];

            $payment = $this->client->create($body);
            return response()->json($payment);
        }catch (MPApiException $exception){
            return $exception->getApiResponse()->getContent();
        } catch (\Exception $exception) {
            // Log or print the exception message and API response details
            return $exception->getMessage();
        }
    }
}
