<?php
/**
 * today_quote_insert.php
 * 
 *          EVERY DAY
 * 1. Parse all_stocks for names
 * 2. Put into loop for client->getQuotes()
 * 3. Insert into database
 */
require('dbHelper.php');
require_once 'vendor/scheb/yahoo-finance-api/ApiClient.php';
require_once 'vendor/scheb/yahoo-finance-api/HttpClient.php';
require_once 'vendor/scheb/yahoo-finance-api/Exception/HttpException.php';
require_once 'vendor/scheb/yahoo-finance-api/Exception/ApiException.php';
require_once 'yahooFinanceHelper.php';
use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\HttpClient;
use Scheb\YahooFinanceApi\Exception;

$client = new \Scheb\YahooFinanceApi\ApiClient();

$stockSymbols = fetchAllStockSymbols();
$stockNamesArray = explode(",", $stockSymbols);

for($i = 0; $i < count($stockNamesArray); $i++){
    $getQuotes = $client->getQuotes($stockNamesArray[$i]);
    $quoteArray = $getQuotes['query']['results']['quote']; 
    
}

?>