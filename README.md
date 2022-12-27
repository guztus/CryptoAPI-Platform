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

Information about coins can be retrieved using dummy data or real data from an <ins>**API**</ins> (set up for using the
CoinMarketCap API)

## Demonstration GIFs

<div style="text-align: center">
    <h3>Purchasing Crypto (+ history and asset list)</h3>
    <p align="center">
        <img src="/DEMO_GIFS/buy_sell.gif" alt="animated-demo" /><br>
    </p>
    <h3>Opening Short positions</h3>
    <p align="center">
        <img src="/DEMO_GIFS/short.gif" alt="animated-demo" /><br>
    </p>
    <h3>Sending coins to other users</h3>
    <p align="center">
        <img src="/DEMO_GIFS/send.gif" alt="animated-demo" /><br>
    </p>
    <h3>Table sorting</h3>
    <p align="center">
        <img src="/DEMO_GIFS/table_sorting.gif" alt="animated-demo" /><br>
    </p>
</div>

## Used Technologies

* PHP `7.4`
* MySQL `8.0`
* Bootstrap `5.2`
* Composer `2.4`

## Setup

To install this project on your local machine, follow these steps:

1. Clone this repository - `git clone https://github.com/guztus/CryptoAPI-Platform`
2. Install all dependencies - `composer install`
3. Rename the ".env.example" file to ".env" <br>
4. Create a database and add the credentials to the ".env" file
5. Import the database structure from the "FTX-NOOB_schema.sql" file (located in "/setup") -
   `mysql -u username -p new_database < FTX-NOOB_schema.sql`<br>
   Replace **username** with the **username** that you use to connect to the database,
   **new_database** with the name of the database that you want to use

* If you wish to use the **CoinMarketCap API**, you will need to enter your own API key in the ".env" file.<br>
* If you wish to use **your own data** for the coins, you can edit the "crypto_coins" table in the database.

To run the project, locate "/public" and there, you can use a command such as `php -S localhost:8000` to run the project
in your browser.

## Further information

To choose which data (**API** or **your own**) to display, you can select your choice in the "dataSourceConfig.php" file. <br>

By default, it uses the **CoinMarketCap API**.