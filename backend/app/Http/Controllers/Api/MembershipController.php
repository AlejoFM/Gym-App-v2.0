<?php

namespace App\Http\Controllers\Api;

use App\Models\Membership;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Env;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function generatePreapprovalPlan()
    {
        $mercadopago = new GuzzleHttp\Client();
        $mercadopago_key = \env("MERCADOPAGO_API_KEY", null);
        try {
            $res = $mercadopago->request("post", "https://api.mercadopago.com/preapproval_plan", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $mercadopago_key,
                ],
                'json' => [
                    "reason" => 'Gym all week pass',
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

    public function generateMembership()
    {
        $mercadopago = new GuzzleHttp\Client();
        $mercadopago_key = \env("MERCADOPAGO_API_KEY", null);
        try {
            $res = $mercadopago->request("post", "https://api.mercadopago.com/preapproval", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $mercadopago_key,
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

    public function getSuscriptionPlan($id)
    {
        $mercadopago = new GuzzleHttp\Client();
        $mercadopago_key = \env("MERCADOPAGO_API_KEY", null);
        try {
            $res = $mercadopago->request("get", 'https://api.mercadopago.com/preapproval/' . $id, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $mercadopago_key,
                ]]);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        //
    }
}
