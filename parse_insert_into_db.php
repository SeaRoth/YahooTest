<?php
/**
 *  parse_insert_into_db.php
 * 1. Reads the text file
 * 2. Gets the last 90 days of trading information
 * 3. Checks if row exists
 * 4. Inserts all stocks
 */
require_once 'vendor/scheb/yahoo-finance-api/ApiClient.php';
require_once 'vendor/scheb/yahoo-finance-api/HttpClient.php';
require_once 'vendor/scheb/yahoo-finance-api/Exception/HttpException.php';
require_once 'vendor/scheb/yahoo-finance-api/Exception/ApiException.php';
include_once('functions_stocks.php');
include_once('functions_files.php');
include_once('dbHelper.php');
include_once('yahooFinanceHelper.php');

$yahoo = new U_Yahoo();
$mTextFile = parseTextFile('include/stock_list.txt');                                            
$yahooFinanceAPIClient = new \Scheb\YahooFinanceApi\ApiClient();
$time_pre = microtime(true);
  
for($i = 361; $i < count($mTextFile); $i++ ){
    //find value and shares outstanding
    $sharesOustanding = file_get_contents('http://finance.yahoo.com/d/quotes.csv?s=' . $mTextFile[$i]['Symbol'] . '&f=j2,p');
    $sharesOustandingArray = explode(",", $sharesOustanding);
    $mSharesOustanding     = $sharesOustandingArray[0];
    $mBookValue            = $sharesOustandingArray[2];
    
    //remove apostrophe if exists (mysql error)
    $mTextFile[$i]['Security Name'] = str_replace("'", "", $mTextFile[$i]['Security Name']);
    
    echo "Record: " . $i . " of: " . count($mTextFile) . " for stock: " . $mTextFile[$i]['Symbol'] . "<br>";
    echo "<br>";
    
    //get the last 90 days of trading information
    $yahooHistorical = $yahoo->getHistoryQuote($mTextFile[$i]['Symbol'], 90, 0);
    // get the last 90 days of trading information
    foreach($yahooHistorical as $key=>$arrayData) {
            $arrayData    = get_object_vars($arrayData);
            if(checkIfRowExists($arrayData['Symbol'], $arrayData['Date']) == TRUE){
                //table exists, skip
            }else{
                //table doesn't exist, create it
                if($key == 0){
                    $arrayData['outstanding_shares'] = $mSharesOustanding;    
                }else{
                    $arrayData['outstanding_shares'] = 0;
                }
                $arrayData['Name'] = $mTextFile[$i]['Security Name'];
                createTableAndInsert($arrayData['Symbol'], $arrayData);                
            }
    } 
    sleep(.25);      
}
$time_post = microtime(true);
$exec_time = $time_post - $time_pre;
echo "<br> This process took: " . $exec_time . " seconds or " . $exec_time/60 . " minutes";
?>