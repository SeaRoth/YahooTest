<?php
require_once('myClass.php');
require_once('functions_stocks.php');
require_once('dbHelper.php');
session_start();
$eligibles = array();

if (isset($_GET['minPrice'])) {
    $eligibles['minPrice'] = $_GET['minPrice'];
}
if (isset($_GET['maxPrice'])) {
    $eligibles['maxPrice'] = $_GET['maxPrice'];
}
if (isset($_GET['minBeta'])) {
    $eligibles['minBeta'] = $_GET['minBeta'];
}
if (isset($_GET['minShares'])) {
    $eligibles['minShares'] = $_GET['minShares'];
}
if (isset($_GET['maxShares'])) {
    $eligibles['maxShares'] = $_GET['maxShares'];
}
if (isset($_GET['declinedYear'])) {
    $eligibles['declinedYear'] = $_GET['declinedYear'];
}
if (isset($_GET['monthsSIGrowth'])) {
    $eligibles['monthsSIGrowth'] = $_GET['monthsSIGrowth'];
}
if (isset($_GET['perDeclinedYest'])) {
    $eligibles['perDeclinedYest'] = $_GET['perDeclinedYest'];
}
if (isset($_GET['dailyAvgVol'])) {
    $eligibles['dailyAvgVol'] = $_GET['dailyAvgVol'];
}
if (isset($_GET['gapTrading'])) {
    $eligibles['gapTrading'] = $_GET['gapTrading'];
}

//go through our session variables
// foreach($eligibles as &$values){
    // echo $values . "<br>";
// }

$mEligibleStockSymbol = fetchEligibleStocks("AAl", $eligibles['minPrice'], $eligibles['maxPrice'] , $eligibles['minShares'], $eligibles['maxShares']);

var_dump(fetchDonkey());


// for($i=0;$i<count($mEligibleStockSymbol);$i++){
    // echo $mEligibleStockSymbol[$i] . "<br>";
// }






















?>