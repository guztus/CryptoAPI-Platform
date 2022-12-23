# FTX-Noob - crypto trading platform

## Table of contents
* [General info](#general-info)
* [Demonstration GIFs](#demonstration-gifs)
* [Used Technologies](#used-technologies)
* [Setup](#setup)
* [Further Information](#further-information)

## General info
This project is a cryptocurrency trading platform where one can:
   * Create an account
   * <ins>**Buy**</ins>, <ins>**sell**</ins> and <ins>**short**</ins> cryptocurrencies
   * View their <ins>**assets**</ins> and <ins>**transaction history**</ins>

Information about coins can be retrieved using dummy data or real data from an <ins>**API**</ins> (set up for using the CoinMarketCap API)

## Demonstration GIFs
<div style="text-align: center">
    <h3>Purchasing Crypto (+ history and asset list)</h3>
    <p align="center">
        <img src="https://github.com/guztus/CryptoAPI-Platform/blob/master/DEMO_GIFS/part_2.gif" alt="animated-demo" /><br>
    </p>
    <h3>Sending Crypto to other users</h3>
    <p align="center">
        <img src="https://github.com/guztus/CryptoAPI-Platform/blob/master/DEMO_GIFS/part_1.gif" alt="animated-demo" /><br>
    </p>
</div>

## Used Technologies
* PHP `7.4`
* MySQL `8`
<br><br>
* Bootstrap `5`
* Composer `2.4.3`

#### The following PHP packages and extensions are used:
    * twig/twig: 3.4
    * nikic/fast-route: 1.3
    * vlucas/phpdotenv: 5.5
    * egulias/email-validator: 3.2
    * doctrine/dbal: 3.5
    * guzzlehttp/guzzle: 7.5
    * php-di/php-di 6.4
    * ext-json
    * ext-curl

## Setup
To install this project on your local machine, follow these steps:
1. Clone this repository - `git clone https://github.com/guztus/CryptoAPI-Platform`
2. Install all dependencies - `composer install`
3. Rename the ".env.example" file to ".env" <br>
4. Create a database and add the credentials to the ".env" file
5. Import the database structure from the "FTX-NOOB_schema.sql" file - 
  `mysql -u username -p new_database < FTX-NOOB_schema.sql` (located in "/setup") <br>
  Replace **username** with the **username** that you use to connect to the database, 
  **new_database** with the name of the new database that you want to create

* If you wish to use the **CoinMarketCap API**, you will need to enter your own API key in the ".env" file.<br>
* If you wish to use **your own data** for the coins, you can edit the "crypto_coins" table in the database.

And you are ready to roll! ;)

To run the project, locate "/public" and there, you can use a command such as `php -S localhost:8000`.

## Further information

To choose which data (**API** or **your own**) to display, you will need to change a line in the `index.php` file. <br>

* To use **API data** (set as standard) - 
`$container->set(CoinsRepository::class, \DI\create(\App\Repositories\Coins\CoinMarketCapCryptoCoinsRepository::class));`
* To use **your own data** - 
`$container->set(CoinsRepository::class, \DI\create(CryptoCoinTable::class));`