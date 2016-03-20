<?php
require_once 'vendor/scheb/yahoo-finance-api/ApiClient.php';
require_once 'vendor/scheb/yahoo-finance-api/HttpClient.php';
require_once 'vendor/scheb/yahoo-finance-api/Exception/HttpException.php';
require_once 'vendor/scheb/yahoo-finance-api/Exception/ApiException.php';
require_once 'yahooFinanceHelper.php';

use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\HttpClient;
use Scheb\YahooFinanceApi\Exception;

$client = new \Scheb\YahooFinanceApi\ApiClient();
$yahoo = new U_Yahoo();

echo "<b> yahoo->getHistoryQuote </b> <br> ";
$yahooHistorical = $yahoo->getHistoryQuote('YHOO', 5, 0);
var_dump($yahooHistorical);
echo "<br> <br>";

echo "<b> client->getHistoricalData </b> <br> ";
//Get historical data
$start = new DateTime('2014-01-02');
$end = new DateTime('2014-01-10');
$histData = $client->getHistoricalData("YHOO",$start,$end);
var_dump($histData);
echo "<br> <br>";

echo "<b> client->getQuotesList </b> <br> ";
$quoteList = $client->getQuotesList("CSX");
var_dump($quoteList);
echo "<br> <br>";

echo "<b> yahoo->getCurrentData </b> <br> ";
$currentData = $yahoo->getCurrentData("CSX");
var_dump($currentData);
echo "<br> <br>";

echo "<b> yahoo->getCurrentQuote </b> <br> ";
$currentQuote = $yahoo->getCurrentQuote("CSX");
var_dump($currentQuote);
echo "<br> <br>";

echo "<b> yahoo->getCurrentQuote </b> <br> ";
$getQuotes = $client->getQuotes("CSX");
var_dump($getQuotes);
echo "<br> <br>";

// 
// echo "After historical Data <br><br>";
// $data = $client->getQuotes("YHOO"); //Single stock
// echo("symbol: " . $data['query']['results']['quote']['symbol']) . "<br>";
// echo("Created: " . $data['query']['created']) . "<br>";
// echo("Ask: " . $data['query']['results']['quote']['Ask']) . "<br>";
// echo("AverageDailyVolume: " . $data['query']['results']['quote']['AverageDailyVolume']) . "<br>";
// echo("Bid: " . $data['query']['results']['quote']['Bid']) . "<br>";
// echo("BookValue: " . $data['query']['results']['quote']['BookValue']) . "<br>";
// echo("Change_PercentChange: " . $data['query']['results']['quote']['Change_PercentChange']) . "<br>";
// echo("Change: " . $data['query']['results']['quote']['Change']) . "<br>";
// echo("DaysLow: " . $data['query']['results']['quote']['DaysLow']) . "<br>";
// echo("DaysHigh: " . $data['query']['results']['quote']['DaysHigh']) . "<br>";
// echo("YearLow: " . $data['query']['results']['quote']['YearLow']) . "<br>";
// echo("YearHigh: " . $data['query']['results']['quote']['YearHigh']) . "<br>";
// echo("MarketCapitalization: " . $data['query']['results']['quote']['MarketCapitalization']) . "<br>";
// echo("ChangeFromYearLow: " . $data['query']['results']['quote']['ChangeFromYearLow']) . "<br>";
// echo("ChangeFromYearHigh: " . $data['query']['results']['quote']['ChangeFromYearHigh']) . "<br>";
// echo("Open: " . $data['query']['results']['quote']['Open']) . "<br>";
// echo("PreviousClose: " . $data['query']['results']['quote']['PreviousClose']) . "<br>";
// echo("ShortRatio: " . $data['query']['results']['quote']['ShortRatio']) . "<br>";
// echo("Volume: " . $data['query']['results']['quote']['Volume']) . "<br>";
// 
// echo "<br>";
// echo "<br>";
// 
// 
// 
// var_dump( $yahoo->getCurrentQuote('GOOG') );
// echo "<br>";
// echo "<br>";
// var_dump( $yahoo->getCurrentQuote(['GOOG', 'YHOO']) );
// echo "<br>";
// echo "<br>";
// 
// 
// $currentData = $yahoo->getCurrentData(['GOOG', 'YHOO']);
// foreach($currentData as &$cd){
    // $cd = get_object_vars($cd);
    // $symbol     = $cd['Symbol'];
    // $adv        = $cd['AverageDailyVolume'];
    // $change     = $cd['Change'];
    // $daysLow    = $cd['DaysLow'];
    // $daysHigh   = $cd['DaysHigh'];
    // $yearLow    = $cd['YearLow'];
    // $yearHigh   = $cd['YearHigh'];
    // $marketCap  = $cd['MarketCapitalization'];
    // $lastTradeP = $cd['LastTradePriceOnly'];
    // $daysRange  = $cd['DaysRange'];
    // $name       = $cd['Name'];
//       
    // echo "Symbol: " . $symbol . "<br>";
    // echo "Average Daily Volume: " . $adv . "<br>";
    // echo "Change: " . $change . "<br>";
    // echo "DaysLow: " . $daysLow . "<br>";
    // echo "DaysHigh: " . $daysHigh . "<br>";
    // echo "YearLow: " . $yearLow . "<br>";
    // echo "YearHigh: " . $yearHigh . "<br>";
    // echo "MarketCapitalization: " . $marketCap . "<br>";
    // echo "LastTradePrice " . $lastTradeP . "<br>";
    // echo "DaysRange " . $daysRange . "<br>";
    // echo "Name " . $name . "<br>";
    // echo "<br><br>";
// }
// echo "<br>";
// echo "<br>";
// 
// 
// echo "<br>";
// echo "<b>Historical Data: </b> <br>";
// foreach($yahooHistorical as &$yahooData) {
        // $yahooData    = get_object_vars($yahooData);
        // $symbol       = $yahooData['Symbol'];
        // $date         = $yahooData['Date'];
        // $open         = $yahooData['Open'];
        // $high         = $yahooData['High'];
        // $low          = $yahooData['Low'];
        // $close        = $yahooData['Close'];
        // $volume       = $yahooData['Volume'];        
//         
        // echo "Symbol: " . $symbol . "<br>";
        // echo "Date: " . $date . "<br>";
        // echo "Open: " . $open . "<br>";
        // echo "High: " . $high . "<br>";
        // echo "Low: " . $low . "<br>";
        // echo "Close: " . $close . "<br>";
        // echo "Volume: " . $volume . "<br>";
//         
        // echo "<br> <br>";
//         
        // require_once("dbHelper.php");
        // createTableAndInsert($symbol, $yahooData);
// }
// 
// echo "<br>";
// echo "<br>";




?>