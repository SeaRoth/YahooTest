<?php
require_once('dbHelper.php');




/*
 * checkIfStockHasGapInTrading($mSymbol, $mDaysInPast)
 * 
 * Input: 
 *  -$mSymbol to look up
 *  -$mDaysInPast to look back
 *  -$mMinPercentChange like 1% or 5%
 * Output: Array of date and the gap in trading
 * 
 */
function checkIfStockHasGapInTrading($mSymbol, $mDaysInPast, $mMinPercentChange){
    $mCloseAndOpenValues = fetchGapInTradingValues($mSymbol,$mDaysInPast);
    $mGapInTradingArray = array();
    
    for($i=1;$i<count($mCloseAndOpenValues);$i++){
        $mDifference = ($mCloseAndOpenValues[$i]['open'] - $mCloseAndOpenValues[$i-1]['close']);
        $mPercentDifference = $mDifference/$mCloseAndOpenValues[$i-1]['close']*100;
        
        if($mPercentDifference > $mMinPercentChange){
            $mGapInTradingArray[$i-1]['start_date'] = $mCloseAndOpenValues[$i]['date'];
            $mGapInTradingArray[$i-1]['end_date']   = $mCloseAndOpenValues[$i-1]['date'];
            $mGapInTradingArray[$i-1]['gap']        = $mCloseAndOpenValues[$i]['open'] - $mCloseAndOpenValues[$i-1]['close'];
        }
    }
    return $mGapInTradingArray;
}  


/*
 * fetchEligibleSTockInformation
 * 
 * Input: Symbol
 * Output: Array of information
 */ 
function fetchEligibleStockInformation($mStockSymbol){
    /*
     * d1 = last trade date [varchar]
     * s  = symbol [varchar]
     * n  = name [varchar]
     * a  = ask [double(15,3)]
     * j2 = outstanding shares [int]
     * k3 = last trade size [int]
     * a2 = average daily volume [int]
     * r  = P/E ratio [double(15,3)]
     * s7 = short ratio [double(15,3)]
     * k  = 52-week high [double(15,3)]
     * k5 = percent change from 52 week high [varchar(20)]
     * j  = 52-week low [double(15,3)]
     * j6 = percent change from 52 week low [varchar(20)]
     */ 
    $mSpecs = "d1snaj2k3a2rs7kk5jj6";  
    $thisArray = fetchCertainDataForSelectStock($mStockSymbol, $mSpecs);
    $thisArray = array_shift($thisArray);
    return $thisArray;
}
      
/*
 * fetchCertainDataForSelectedStock($symbol, $parameters)
 * 
 * Input:
 *  $symbol: Acronym
 *  $parameters: Separated by commas 
 *      -http://wern-ancheta.com/blog/2015/04/05/getting-started-with-the-yahoo-finance-api/
 * Output: Array with values
 */
function fetchCertainDataForSelectStock($symbol, $parameters){
    $url = 'http://finance.yahoo.com/d/quotes.csv?s=' . $symbol . '&f=' . $parameters;
    echo "<a href='$url'>CSV LINK</a> <br>";
    $csvFile = file_get_contents($url);
    $rows = explode(PHP_EOL,$csvFile);
    $s = array();
    foreach($rows as $row) {
        $s[] = str_getcsv($row);    
    }
    return $s;
}    
?>