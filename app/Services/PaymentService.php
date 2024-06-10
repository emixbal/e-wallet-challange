<?php

namespace App\Services;

// use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    private $client;
    private $url;
    private $token;

    public function __construct()
    {
        // $this->client = new Client();
        $this->url = env('PAYMENT_GATEWAY_API_URL');
        $this->token = env('PAYMENT_GATEWAY_API_TOKEN');
    }

    public function deposit($orderId, $amount, $timestamp)
    {
        // Log::info('Attempting to deposit to external endpoint.', [
        //     'order_id' => $orderId,
        //     'amount' => $amount,
        //     'timestamp' => $timestamp,
        //     'url' => $this->url . '/deposit',
        //     'token' => 'Bearer ' . base64_encode($this->token),
        // ]);

        // $response = $this->client->request('GET', 'http://localhost/dprd-payroll/api/json', [
        //     // 'headers' => [
        //     //     'Authorization' => 'Bearer ' . base64_encode($this->token),
        //     //     'Content-Type' => 'application/json',
        //     // ],
        //     'json' => [
        //         'name' => "iqbal",
        //         'job' => "programmer",
        //     ],
        // ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . base64_encode($this->token),
            'Content-Type' => 'application/json',
        ])->post("http://localhost/payment_app/api/iqbalpay" . '/deposit', [
            'order_id' => $orderId,
            'amount' => $amount,
            'timestamp' => $timestamp,
        ]);

        // $response = $this->client->request('POST', "localhost:8000/api/iqbalpay/deposit", [
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . base64_encode($this->token),
        //         'Content-Type' => 'application/json',
        //     ],
        //     'json' => [
        //         'order_id' => $orderId,
        //         'amount' => $amount,
        //         'timestamp' => $timestamp,
        //     ],
        // ]);

        // Log::info($response);

        $responseData = json_decode($response->getBody(), true);

        return $responseData;
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
