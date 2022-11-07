<?php

namespace ZplGenerator\Client;

use CURLFile;

class CloudClient implements Client {

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
        $file = tmpfile();
        fwrite($file, $zpl);

        $name = stream_get_meta_data($file)["uri"];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zebra.com/v2/devices/printers/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                "sn" => $this->serialNumber,
                "zpl_file"=> new CURLFILE($name),
            ],
            CURLOPT_HTTPHEADER => [
                "accept: text/plain",
                "apikey: $this->key",
                "tenant: $this->tenant",
            ],
        ));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($status !== 200) {
            throw new CloudClientException($status);
        }

        curl_close($curl);
    }

}
