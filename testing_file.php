<?php
require_once('dbHelper.php');
include_once('functions_stocks.php');



$mAllStockSymbols = fetchAllStockSymbols();

for($i=0;$i<count($mAllStockSymbols);$i++){
    
    $mTemp = fetchEligibleStocks($mAllStockSymbols[$i], 5, 10, 300000, 300000000); 
    if($mTemp != NULL){
        echo $mTemp['symbol'] . "<br>";
    }
    
}

?>