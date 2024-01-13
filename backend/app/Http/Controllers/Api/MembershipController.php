<?php

namespace App\Http\Controllers\Api;

use App\Models\Membership;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Env;

class MembershipController extends Controller
{
    public $mercadopago;
    public $mercadopago_key;

    public function __construct()
    {
        $this->mercadopago = new GuzzleHttp\Client();
        $this->mercadopago_key = \env("MERCADOPAGO_API_KEY", null);
    }
    public function generateMercadoPagoClient()
    {
        try {
            $res = $this->mercadopago->request("post", "https://api.mercadopago.com/v1/customers", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ],
                "json" => [
                    "data" => [
                        "address" => [
                            "id" => "123123",
                            "zip_code" => "01234567",
                            "street_name" => "Rua Exemplo",

                        ],
                        "date_registered" => "2021-10-20T11:37:30.000-04:00",
                        "default_address" => "Home",
                        "default_card" => "None",
                        "description" => "Description del user",
                        "email" => "jhon@doe.com",
                        "first_name" => "Jhon",
                        "identification" => [
                            "type" => "CPF",
                            "number" => "12345678900"
                        ],
                        "last_name" => "Doe",
                        "phone" => [
                            "area_code" => "55",
                            "number" => "991234567"
                        ]
                    ],
                ]
            ]);
            $data = json_decode($res->getBody(), true);
            return response()->json(['data' => $data]);
        } catch (GuzzleHttp\Exception\GuzzleException $exception) {
            return $exception->getMessage();
        }
    }
    public
    function generateMembershipOneTimePayment()
    {
        try {
            $res = $this->mercadopago->request("post", "https://api.mercadopago.com/checkout/preferences", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ],
                'json' => [
                    "back_urls" => null,
                    "differential_pricing" => null,
                    "expires" => false,
                    "items" => [
                        [
                            "title" => "Dummy Title",
                            "description" => "Dummy description",
                            "category_id" => "car_electronics",
                            "quantity" => 1,
                            "currency_id" => "ARS",
                            "unit_price" => 10
                        ]
                    ],
                    "marketplace_fee" => null,
                    "metadata" => null,
                    "payer" => [
                        "phone" => ["number" => null],
                        "identification" => null,
                        "address" => ["street_number" => null]
                    ],
                    "payment_methods" => [
                        "excluded_payment_methods" => null,
                        "excluded_payment_types" => null,
                        "installments" => null,
                        "default_installments" => null
                    ],
                    "shipments" => [
                        "local_pickup" => false,
                        "default_shipping_method" => null,
                        "free_methods" => [
                            ["id" => null]
                        ],
                        "cost" => null,
                        "free_shipping" => false,
                        "receiver_address" => ["street_number" => null]
                    ],
                    "tracks" => null,
                ]
            ]);
        } catch (GuzzleHttp\Exception\GuzzleException $exception) {
            return $exception->getMessage();
        }
        $data = json_decode($res->getBody(), true);
        $id = $data['id'];
        return response()->json(['payment_data' => $data, 'payment_id' => $id]);
    }
    public function getPayment($id){
        try {
            $res = $this->mercadopago->request("get", "https://api.mercadopago.com/v1/payments/{$id}", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ]]);
        }catch (GuzzleHttp\Exception\GuzzleException $exception){
            $response = $exception->getResponse();
            $statusCode = $response->getStatusCode();
            $errorBody = json_decode($response->getBody(), true);
            return response()->json(['error' => $errorBody, 'status_code' => $statusCode], $statusCode);
        }
        $data = json_decode($res->getBody(), true);
        return response()->json($data);
    }
    public
    function getMembershipOneTimePayment($id)
    {
        try {
            $res = $this->mercadopago->request("get", "https://api.mercadopago.com/checkout/preferences/{$id}", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                    ''
                ]]);
        }catch (GuzzleHttp\Exception\GuzzleException $exception){
            return $exception->getMessage();
        }
        $data = json_decode($res->getBody(), true);
        return response()->json($data);
    }
    public function updateMembershipOneTimePayment($id){
        try {
            $res = $this->mercadopago->request("put", "https://api.mercadopago.com/checkout/preferences/{$id}", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ]]);
        }catch (GuzzleHttp\Exception\GuzzleException $exception){
            return $exception->getMessage();
        }
        $data = json_decode($res->getBody(), true);
        return response()->json($data);
    }
    public
    function generatePreapprovalPlan()
    {
        try {
            $res = $this->mercadopago->request("post", "https://api.mercadopago.com/preapproval_plan", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ],
                'json' => [
                    "reason" => 'Gym all week pass',
                    "auto_recurring" => [
                        "frequency" => 1,
                        "frequency_type" => "months",
                        "repetitions" => 3,
                        "billing_day" => 10,
                        "billing_day_proportional" => true,
                        "free_trial" => [
                            "frequency" => 1,
                            "frequency_type" => "months"
                        ],
                        "transaction_amount" => 10,
                        "currency_id" => "ARS",
                    ],
                    "payment_methods_allowed" => [
                        "payment_types" => [
                            [
                                "id" => "credit_card" // Ejemplo de tipo de pago permitido
                            ]
                        ],
                        "payment_methods" => [
                            [
                                "id" => "visa" // Ejemplo de método de pago permitido
                            ]
                        ]
                    ],
                    "back_url" => 'http://www.localhost.com',
                ],
            ]);
            return $response = json_decode($res->getBody(), true);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public
    function generateMembershipSuscription()
    {
        try {
            $res = $this->mercadopago->request("post", "https://api.mercadopago.com/preapproval", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ],
                'json' => [
                    "card_token_id" => 1,
                    "Authorized" => true,
                    "auto_recurring" => [
                        "frequency" => 1,
                        "frequency_type" => "months",
                        "repetitions" => 12,
                        "billing_day" => 10,
                        "billing_day_proportional" => true,
                        "free_trial" => [
                            "frequency" => 1,
                            "frequency_type" => "months"
                        ],
                        "transaction_amount" => 10,
                        "currency_id" => "ARS",
                    ],
                    'payer_email' => 'email_del_pagador@gmail.com',
                    'back_url' => 'http://www.localhost.com',
                    'reason' => 'Gym all week pass',
                ]
            ]);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public
    function getSuscriptionPlan($id)
    {
        try {
            $res = $this->mercadopago->request("get", 'https://api.mercadopago.com/preapproval_plan/' . $id, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->mercadopago_key,
                    ]]
            );
            $data = json_decode($res->getBody(), true);
            return response()->json(['data' => $data]);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return $e->getMessage();
        }

    }

    public
    function updateSuscriptionPlan($id)
    {
        try {
            $res = $this->mercadopago->request("put", 'https://api.mercadopago.com/preapproval_plan/' . $id, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->mercadopago_key,
                ],
                'json' => [
                    "card_token_id" => 1,
                    "Authorized" => true,
                    "auto_recurring" => [
                        "frequency" => 1,
                        "frequency_type" => "months",
                        "repetitions" => 1,
                        "billing_day" => 10,
                        "billing_day_proportional" => true,
                        "free_trial" => [
                            "frequency" => 1,
                            "frequency_type" => "months"
                        ],
                        "transaction_amount" => 10000,
                        "currency_id" => "ARS",
                    ],
                ],
            ]);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public
    function show(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, Membership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(Membership $membership)
    {
        //
    }
}
