<?php

namespace App;

use App\Models\Collections\CoinCollection;

class APIRequest
{
    private $results;

    public function __construct()
    {
//        $url = 'https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '10',
            'convert' => 'USD'
        ];

        $headers = [
            'Accepts: application/json',
//            "X-CMC_PRO_API_KEY: {$_ENV['APIKEY_DUMMY']}"
        "X-CMC_PRO_API_KEY: {$_ENV['APIKEY']}"

        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
// Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $this->results = json_decode(curl_exec($curl)); // Send the request, save the response
        curl_close($curl); // Close request
    }

    public function getResults(): Object
    {
        return $this->results;
    }
}