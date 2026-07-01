<?php

namespace App\Repositories;

use App\Services\ApiClientService;

class ApiClientRepository
{
    protected ApiClientService $api;

    public function __construct(ApiClientService $api)
    {
        $this->api = $api;
    }

    public function get(string $url, array $params = [])
    {
        $response = $this->api->get($url, $params);

        // jika API gagal / timeout
        if (!is_array($response) || isset($response['success']) && $response['success'] === false) {
            return [];
        }

        return $response;
    }
}
