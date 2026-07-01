<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class ApiClientService
{
    public function get(string $url, array $params = [], int $timeout = 10)
    {
        try {
            return Http::timeout($timeout)
                ->retry(3, 200) // coba ulang 3x jika gagal
                ->get($url, $params)
                ->throw()
                ->json();
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Request to API timed out or failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function post(string $url, array $data = [], int $timeout = 10)
    {
        try {
            return Http::timeout($timeout)
                ->retry(3, 200)
                ->post($url, $data)
                ->throw()
                ->json();
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Request to API timed out or failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}
