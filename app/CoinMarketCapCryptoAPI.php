<?php declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;

class CoinMarketCapCryptoAPI
{
    private string $url = 'https://pro-api.coinmarketcap.com/';
    private array $headers;

    public function __construct()
    {
        $this->headers = [
            "Accepts" => " application/json",
            "X-CMC_PRO_API_KEY" => $_ENV['COINMARKETCAP_APIKEY']
        ];
    }

    public function getList(): Object
    {
        return $this->fetchListCoinInformation();
    }

    public function getSingle(string $coinSymbol): ?Object
    {
        return $this->fetchSingleCoinInformation($coinSymbol);
    }

    private function fetchSingleCoinInformation(string $coinSymbol): ?object
    {
        $url = $this->url . 'v2/cryptocurrency/quotes/latest';

        $parameters = [
            'symbol' => $coinSymbol,
            'convert' => 'USD'
        ];

        $resultFetched = $this->getResults($url, $parameters, $this->headers);
        if (empty($resultFetched->data->$coinSymbol)){
            return null;
        }
        return $resultFetched;
    }

    private function fetchListCoinInformation(): object
    {
        $url = $this->url . 'v1/cryptocurrency/listings/latest';

        $parameters = [
            'start' => '1',
            'limit' => '10',
            'convert' => 'USD'
        ];

        return $this->getResults($url, $parameters, $this->headers);
    }

    private function getResults($url, $parameters, $headers): object
    {
        $client = new Client();
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'query' => $parameters
        ]);
        return json_decode($response->getBody()->getContents());
    }
}