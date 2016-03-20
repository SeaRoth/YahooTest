<?php
require_once('dbHelper.php');
require_once('nyse_functions.php');


/*
 * function
 * 
 * 
 * 
 * 
 */ 
function fetchStockInformationStuff(){
    /*
        n  = name 
        s  = symbol 
        o  = open  
        p  = previousClose 
        v  = volume 
        a2 = averageDailyVolume 
        c  = change_percentChange 
        c1 = dayChange 
        currency 
        c8 = afterHoursChangeRealtime
        g  = daysLow
        h  = daysHigh
        yearLow 
        yearHigh
        c6 = changePercentRealtime
        k4 = changeFromYearHigh 
        k5 = percentChangeFromYearHigh
        m  = daysRange
        m2 = daysRangeRealtime
        m3 = fiftyDayMovingAverage
        m4 = twoHundredDayMovingAverage
        m5 = changeFromTwoHundredDayMovingAverage
        m7 = changeFromFiftyDayMovingAverage
        m8 = percentChangeFromFiftyDayMovingAverage
     */
    
    $mStockSymbol = "CSX";
    $mSpecs = "nsopva2c1c8ghc6k4k5mm2m3m4m5m7m8";  
    $thisArray = fetchCertainDataForSelectStock($mStockSymbol, $mSpecs);
    $thisArray = array_shift($thisArray);
    
    $mName = $thisArray[0];
    $mSymbol = $thisArray[1];
    $mOpen = $thisArray[2];
    $mPreviousClose = $thisArray[3];
    $mVolume = $thisArray[4];
    $mAverageDailyVolume = $thisArray[5];
    $mChange_percentChange = $thisArray[6];
    $mdayCHange = $thisArray[7];
    $mAfterHoursChangeRealtime = $thisArray[8];
    $mDaysLow = $thisArray[9];
    $mDaysHigh = $thisArray[10];
    $mChangePercentRealtime = $thisArray[11];
    $mchangeFromYearHigh = $thisArray[12];
    $mPercentChangeFromYearHigh = $thisArray[13];
    $mDaysRange = $thisArray[14];
    $mDaysRangeRealtime = $thisArray[15];
    $mFiftyDayMovingAverage = $thisArray[16];
    $mTwoHundredDayMovingAverage = $thisArray[17];
    $mChangeFromTwoHundredDayMoingAverage = $thisArray[18];
    $mChangeFromFiftyDayMovingAverage = $thisArray[19];
    
    for($i=0;$i<count($thisArray);$i++){
        echo $thisArray[$i] . "<br>";
    }
    var_dump(checkIfTableExists("YHOO"));    
}
      
/*
 * fetchCertainDataForSelectedStock($symbol, $parameters)
 * 
 * Input:
 *  $symbol: Acronym
 *  $parameters: Separated by commas 
 *      -http://wern-ancheta.com/blog/2015/04/05/getting-started-with-the-yahoo-finance-api/
 * Output: Array with values
 * 
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

/*
 * explodeTextFileToArray($delimiter, $textFileLocation)
 * 
 * Input: 
 *      $delimiter - How to split up the file
 *      $textFileLocation - Location of the file
 * Output: Array of contents
 */
 function explodeTextFileToArray($delimiter, $textFileLocation){
    $thisString = file_get_contents($textFileLocation);
    $thisArray = explode($delimiter, $thisString);
    return $thisArray;
 }
 
       
/*
 * parseTextFile($file)
 * 
 * Input: Text file location
 * Output: Text file as String
 */       
function parseTextFile($file){
    if( !$file = file_get_contents($file))
        throw new Exception('No file was found!!');
    $data = [];
    $firstLine = true;
    foreach(explode("\n", $file) as $line) {
        if($firstLine) { 
            $keys=explode('|', $line);
            $firstLine = false; 
            continue; 
        } // skip first line
        $texts = explode('|', $line);
        $data[] = array_combine($keys,$texts);
    }
    return $data;
}

/*
 * removeNewLinesFromString
 * 
 * Input: A String
 * Output: A String
 */
function removeNewLinesFromString($theString){
    //return str_replace(PHP_EOL, '', $theString);
    return preg_replace("/[\n\r]/","",$theString);  
}
 
 
/**
 * removeNewLinesFromTextFile($inputFileLocation, $outputFileLocation)
 * 
 * Input: File's input and output locations 
 * Output: Nothing
 */
function removeNewLinesFromTextFile($inputFileLocation, $outputFileLocation){
    file_put_contents($inputFileLocation,
        preg_replace(
            '/\R+/',
            "\n",
            file_get_contents($outputFileLocation)
        )
    );  
}


?>