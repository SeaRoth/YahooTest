<?php
require_once('dbHelper.php');

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
 
/*
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