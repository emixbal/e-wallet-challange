<?php

namespace App\Services;

use GuzzleHttp\Client;

class PaymentService
{
    private $client;
    private $url;
    private $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->url = env('PAYMENT_GATEWAY_API_URL');
        $this->token = env('PAYMENT_GATEWAY_API_TOKEN');
    }

    public function deposit($orderId, $amount, $timestamp)
    {
        $response = $this->client->post($this->url . '/deposit', [
            'headers' => [
                'Authorization' => 'Bearer ' . base64_encode($this->token),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'order_id' => $orderId,
                'amount' => $amount,
                'timestamp' => $timestamp,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function withdraw($orderId, $amount, $timestamp, $bank)
    {
        // Validate bank parameter
        $allowedBanks = ['ABC', 'DEF', 'FGH'];
        if (!in_array($bank, $allowedBanks)) {
            throw new \InvalidArgumentException('Invalid bank. Allowed values are: ' . implode(', ', $allowedBanks));
        }

        $data = [
            'order_id' => $orderId,
            'amount' => $amount,
            'bank' => $bank,
            'timestamp' => $timestamp,
        ];

        $response = $this->client->post($this->url . '/withdraw', [
            'headers' => [
                'Authorization' => 'Bearer ' . base64_encode($this->token),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
