<?php

namespace ZplGenerator\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class CloudClient implements Client {

    protected GuzzleClient $http;

    private string $key;

    private string $tenant;

    private string $serialNumber;

    public function __construct(string $key, string $tenant, string $serialNumber) {
        $this->key = $key;
        $this->tenant = $tenant;
        $this->serialNumber = $serialNumber;
    }

    public static function create(string $key, string $tenant, string $serialNumber): self {
        return new CloudClient($key, $tenant, $serialNumber);
    }

    public function send(string $zpl): void {
        $response = $this->http->request("POST", "https://api.zebra.com/v2/devices/printers/send", [
            RequestOptions::HEADERS => [
                "accept" => "text/plain",
                "apikey" => $this->key,
                "tenant" => $this->tenant,
            ],
            RequestOptions::BODY => [
                "sn" => $this->serialNumber,
                "zpl_file" => $zpl,
            ],
        ]);

        if($response->getStatusCode() !== 200) {
            throw new CloudClientException($response->getStatusCode());
        }
    }

}
