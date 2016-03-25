<?php
require_once('dbHelper.php');
include_once('functions_stocks.php');

$mMinPrice = 5;
$mMaxPrice = 500;
$mMinShares = 5000000;
$mMaxShares = 300000000;
$mSymbolsArray = fetchAllStockSymbols();
$mEligibleSymbol = array();

//function fetchEligibleStocks($mTtableName, $mMinPrice, $mMaxPrice, $mMinShares, $mMaxShares){

// for($i=0;$i<count($mSymbolsArray);$i++){            
    // $mEligibleSymbol = fetchEligibleStocks($mSymbolsArray[$i], $mMinPrice, $mMaxPrice, $mMinShares, $mMaxShares);
    // if($mEligibleSymbol != NULL){
        // $mEligibleSymbol = array_shift($mEligibleSymbol);
        // $mArray = compareAskToYearlyHigh($mEligibleSymbol['symbol']);
        // $mArray = array_shift($mArray);
        // $mMultiplier = .70;
        // $mTarget = $mArray['52WeekHigh'] * $mMultiplier;
        // if($mTarget >= $mArray['ask']){
            // echo $mEligibleSymbol['symbol'] . "<br>";
        // }        
    // }
// }
//stock in question close values
$mPricesArray1 = [39.32, 39.45, 39.27, 38.73, 37.99, 38.38, 39.53, 40.55, 40.78, 41.3, 41.35, 41.25, 41.1, 41.26, 41.48, 41.68, 41.77, 41.92, 42.12, 41.85, 41.54];
//s&p 500  close values
$mPricesArray2 = [1972.18, 1988.87, 1987.86, 1940.51, 1867.61, 1893.21, 1970.89, 2035.73, 2079.61, 2096.92, 2102.44, 2091.54, 2083.39, 2086.05, 2084.07, 2104.18, 2077.57, 2083.56, 2099.84, 2093.32, 2098.04];

$mDailyChangeArray1 = array();
$mDailyChangeArray2 = array();
$mCloseValuesArray1 = array();
$mCloseValuesArray2 = array();

print_r($mPricesArray1);
echo "<br><br>";
print_r($mPricesArray2);
echo "<br><br>";

for($i=1;$i<count($mPricesArray1);$i++){
    $mDailyChange = round((( $mPricesArray1[$i] - $mPricesArray1[$i-1] ) / $mPricesArray1[$i-1] * 100),4);
    array_push($mDailyChangeArray1, $mDailyChange);
}
var_dump($mDailyChangeArray1);
echo "<br> <br>";

for($i=1; $i<count($mPricesArray2); $i++){
    $mDailyChange = round((( $mPricesArray2[$i] - $mPricesArray2[$i-1] ) / $mPricesArray2[$i-1] * 100),4);
    array_push($mDailyChangeArray2, $mDailyChange);
}
var_dump($mDailyChangeArray2);
echo "<br> <br>";

$mCovariance = round(standard_covariance($mDailyChangeArray1, $mDailyChangeArray2),3);
$mVariance   = round(variance($mDailyChangeArray2),3);
$mBeta       = round($mCovariance/$mVariance,3);

echo "Covariance: " . $mCovariance . "<br>";
echo "Variance: " . $mVariance . "<br>";
echo "<br> This stock's beta value is: " . $mBeta;


/*
 * 2nd part that i copied over
 * 
 * 
 */

$handle = fopen("include/sp_3_months.csv", "r");
$data = fgetcsv($handle, 1000, ","); 
$mCloseValuesArray2 = array();
$counter = 0;

/*
 * Stock: AAAP
 */
$mCloseValues1 = fetchCloseValues("AAL");
for($i=0;$i<count($mCloseValues1);$i++){
    array_push($mCloseValuesArray1, $mCloseValues1[$i]['close']);
}

/*  
 * Stock: S&P
 */
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $mCloseValuesArray2[$counter] = round($data[6],2);
    $counter++;
}
fclose($handle);

//stock 1 that uses s&p from internet
$m1Change  = find_percent_daily_change($mPricesArray1);
$m2Change  = find_percent_daily_change($mPricesArray2);
$stock1Cov = round(standard_covariance($m1Change, $m2Change),3);
$stock1Var = round(variance($m2Change),3);
$stock1Bet = round($stock1Cov/$stock1Var,3);
echo "beta: " . $stock1Bet . "<br><br>";

//stock in question thats uses s&p from database
$m3Change  = find_percent_daily_change($mCloseValuesArray1);
$m4Change  = find_percent_daily_change($mCloseValuesArray2);
$stock2Cov = round(standard_covariance($m3Change, $m4Change),3);
$stock2Var = round(variance($m4Change),3);
$stock2Bet = round($stock2Cov/$stock2Var,3);
echo "beta: " . $stock2Bet . "<br><br>";

//now using m2Change and m3Change
$stock2Cov = round(standard_covariance($m3Change, $m2Change),3);
$stock2Var = round(variance($m2Change),3);
$stock2Bet = round($stock2Cov/$stock2Var,3);
echo "beta: " . $stock2Bet . "<br><br>";



 
 
 
 
function standard_covariance($aValues,$bValues)
{
        $a= (array_sum($aValues)*array_sum($bValues))/count($aValues);
        $ret = array();
        for($i=0;$i<count($aValues);$i++)
        {
            $ret[$i]=$aValues[$i]*$bValues[$i];
        }
        $b=(array_sum($ret)-$a)/(count($aValues)-1);        
        return (float) $b; 
} 

function average($arr)
{
    if (!count($arr)) return 0;

    $sum = 0;
    for ($i = 0; $i < count($arr); $i++)
    {
        $sum += $arr[$i];
    }

    return $sum / count($arr);
}

function variance($arr)
{
    if (!count($arr)) return 0;

    $mean = average($arr);

    $sos = 0;    // Sum of squares
    for ($i = 0; $i < count($arr); $i++)
    {
        $sos += ($arr[$i] - $mean) * ($arr[$i] - $mean);
    }

    return $sos / (count($arr)-1);  // denominator = n-1; i.e. estimating based on sample 
                                    // n-1 is also what MS Excel takes by default in the
                                    // VAR function
}


?>