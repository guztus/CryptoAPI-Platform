<?php declare(strict_types=1);

namespace App;

use App\Models\Collections\CoinCollection;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class CoinMarketCapCryptoAPI
{
    private Object $results;
//        $url = 'https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest'; // DUMMY DATA
    private string $url = 'https://pro-api.coinmarketcap.com/';
    private array $headers;

    public function __construct()
    {
        $this->headers = [
            "Accepts" => " application/json",
            "X-CMC_PRO_API_KEY" => $_ENV['APIKEY']
        ];
    }

    public function getList(): Object
    {
        $this->fetchListCoinInformation();
        return $this->results;
    }

    public function getSingle(string $coinSymbol): Object
    {
        $this->fetchSingleCoinInformation($coinSymbol);
        return $this->results;
    }

    private function fetchSingleCoinInformation(string $coinSymbol): void
    {
        $url = $this->url . 'v2/cryptocurrency/quotes/latest';

        $parameters = [
            'symbol' => $coinSymbol,
            'convert' => 'USD'
        ];

        $this->results = $this->getResults($url, $parameters, $this->headers);
    }

    private function fetchListCoinInformation(): void
    {
        $url = $this->url . 'v1/cryptocurrency/listings/latest';

        $parameters = [
            'start' => '1',
            'limit' => '10',
            'convert' => 'USD'
        ];

        $this->results = $this->getResults($url, $parameters, $this->headers);
    }

    private function getResults($url, $parameters, $headers): Object
    {
        $client = new Client();
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'query' => $parameters
        ]);
        return json_decode($response->getBody()->getContents());
    }
}