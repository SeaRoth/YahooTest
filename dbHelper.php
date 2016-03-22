<?php
/**
 *  dbHelper.php functions:
 * 1. Fetch all stock symbols
 * 2. Fetch a certain stock
 * 3. Fetch eligible stocks
 * 4. Create table and insert 
 * 5. Create and insert daily stock information 
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "all_nyse_stocks";

/*
 * changeDatabaseEntryType
 * 
 * Description: Function will iterate through every database entry and change it's respective type
 * 
 * Input: 
 *  -$dbName
 *  -$columnName
 *  -$newType
 * Output: SUCCESS OR FAILURE
 * 
 * newTYpes:
 *  1. int
 *  2. DECIMAL(15,3) [15 significant digits plus 3 precision]
 * 
 * e.g. function call: changeDatabaseEntryType("eligible_stocks", "52WeekLow", "DECIMAL(15,3)");
 */
 function changeDatabaseEntryType($dbName, $columnName, $newType){
    $mSymbolsArray = fetchAllStockSymbols();
    global $servername, $username, $password;
    
    for($i=0;$i<count($mSymbolsArray);$i++){
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            // sql to create table
            $sqlCreate = "ALTER TABLE " . $mSymbolsArray[$i] . " MODIFY " . $columnName . " " . $newType;
        
            // use exec() because no results are returned
            $conn->exec($sqlCreate);
            echo "Finished altering " . $mSymbolsArray[$i] . " " . $i . "/" . count($mSymbolsArray) . " to " . $newType . "<br>";
        }
        catch(PDOException $e)
        { echo $sqlCreate . "<br>" . $e->getMessage() . "<br><br>"; }    
    }
 } 

/*
 * checkIfRowExists
 * 
 * Input: Table name and row's date (primary key)
 * Output: True or false 
 */
function checkIfRowExists($tableName, $date){
    global $servername, $username, $password, $dbname;
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $stmt = $conn->prepare('SELECT * FROM aame WHERE date=?');
    $stmt->bindParam(1, $date, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$row){
        return FALSE;
    }else{
        return TRUE;
    }
}

/*
 * compareAskToYearlyHigh($mSymbol)
 * 
 * Input: Stock's symbol
 * Output: Ask and 52 week high
 * 
 */ 
 function compareAskToYearlyHigh($mSymbol){
    global $servername, $username, $password;
    $dbname = "eligible_stocks";

     try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare("SELECT ask,52WeekHigh from $mSymbol");
         $stmt->execute();
         $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);  
         return $stmt->fetchAll();
    }
    catch(PDOException $e) {
         echo "Error: " . $e->getMessage();
    }
    $conn = null;
 }

/*
 * checkIfTableExists
 * 
 * Input: Stock symbol
 * Output: True or false
 */
function checkIfTableExists($tableName){
    global $servername, $username, $password, $dbname;
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $conn->query("SELECT 1 FROM $tableName LIMIT 1");
        $conn = null;    
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;     
}

/*
 * fetchAllStockSymbols
 * 
 * Input: none
 * Output: Array full of symbols
 */
function fetchAllStockSymbols(){
    global $servername, $username, $password;
    $dbname = "stock_symbols_only";
    
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $result = $db->query("SHOW TABLES");
    $mArray = array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
       foreach($row as $value) {
           array_push($mArray,$value);
       }
    }
    return $mArray;
}

/*
 * fetchEligibleStocks
 * 
 * Input: 
 *  -$mTableName
 *  -$mMinPrice
 *  -$mMaxPrice
 *  -$mMinShares
 *  -$mMaxShares
 * Output: Array of eligible stocks
 * 
 */
 function fetchEligibleStocks($mTableName, $mMinPrice, $mMaxPrice, $mMinShares, $mMaxShares){
     global $servername, $username, $password;
     $dbname = "eligible_stocks"; 
        
    try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare("SELECT symbol FROM $mTableName WHERE ask > $mMinPrice AND ask < $mMaxPrice AND outstandingShares > $mMinShares AND outstandingShares < $mMaxShares");
         $stmt->execute();
         $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);  
         return $stmt->fetchAll();
    }
    catch(PDOException $e) {
         echo "Error: " . $e->getMessage();
    }
    $conn = null;
 } 

/*
 * fetchCSVFromTable
 * 
 * Input: Stock symbol
 * Output: CSV file
 * 
 */
function fetchCSVFromTable($stockSymbol){
    global $servername, $username, $password, $dbname;
    
    $db_con = mysqli_connect($servername, $username, $password, $dbname) or die("Connection Error " . mysqli_error($connection));
    $sql = 'SELECT date, open, high, low, volume FROM AAPL';    
    $result = mysqli_query($db_con, $sql) or die("Selection Error " . mysqli_error($connection));
    $fp = fopen('books.csv', 'a+');
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($fp, $row);
    }
    fclose($fp);
    mysqli_close($db_con);
    var_dump($fp);  
} 

/*
 * fetchCertainStock($stockSymbol)
 * 
 * Input: stock symbol we're querying for
 * Output: All historical data on said stock
 */
function fetchCertainStock($stockSymbol){
    global $servername, $username, $password, $dbname;
    try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare("SELECT * FROM $stockSymbol");
         $stmt->execute();
    
         // set the resulting array to associative
         $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);  
         return $stmt->fetchAll();
    }
    catch(PDOException $e) {
         echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

/*
 * createSymbolTableOnly($mSymbol)
 * 
 * Input: The stock's symbol
 * Output: Nada
 * 
 */
 function createSymbolTableOnly($mSymbol){
    global $servername, $username, $password;
    $dbname = "stock_symbols_only";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // sql to create table
        $sqlCreate = "CREATE TABLE IF NOT EXISTS $mSymbol(
        symbol varchar(6) NOT NULL PRIMARY KEY
        )";
        // use exec() because no results are returned
        $conn->exec($sqlCreate);
    }
    catch(PDOException $e)
    { echo $sqlCreate . "<br>" . $e->getMessage() . "<br><br>"; }
    $conn = null;       
 }
 
 /*
  * createEligibleTableAndInsert($mArray)
  * 
  * Input: Array of values
  * Output: Nothing
  */ 
  function createEligibleTableAndInsert($mArray){
    global $servername, $username, $password;
    $dbname = "eligible_stocks";
    $stockSymbol = $mArray[1];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // sql to create table
        $sqlCreate = "CREATE TABLE IF NOT EXISTS $stockSymbol(
        date varchar(15) NOT NULL PRIMARY KEY,
        symbol varchar(6),
        name varchar(60),
        ask varchar(20),
        outstandingShares varchar(20),
        lastTradeSize varchar(20),
        averageDailyVolume varchar(20),
        pERatio varchar(20),
        shortRatio varchar(20),
        52WeekHigh varchar(20),
        52WeekHighPercentChange varchar(20),
        52WeekLow varchar(20),
        52WeekLowPercentChange varchar(20)
        )";
    
        // use exec() because no results are returned
        $conn->exec($sqlCreate);
    }
    catch(PDOException $e)
    { echo $sqlCreate . "<br>" . $e->getMessage() . "<br><br>"; }
    
    try {
        $sqlInsert = "INSERT INTO $stockSymbol
         (date, symbol, name, ask, outstandingShares, lastTradeSize, averageDailyVolume, pERatio, shortRatio, 52WeekHigh,
          52WeekHighPercentChange, 52WeekLow, 52WeekLowPercentChange)
        VALUES ('$mArray[0]', '$mArray[1]', '$mArray[2]', '$mArray[3]', '$mArray[4]', '$mArray[5]', '$mArray[6]', '$mArray[7]',
         '$mArray[8]', '$mArray[9]', '$mArray[10]', '$mArray[11]', '$mArray[12]')";
        
        // use exec() because no results are returned
        $conn->exec($sqlInsert);
    }
    catch(PDOException $e)
    {
        echo $sqlInsert . "<br>" . $e->getMessage();
    }        
    $conn = null;        
  }

/*
 * createTableAndInsert($tableName, $objects)
 * 
 * Input: Table's name and the corresponding ojects
 * Output: None
 */
function createTableAndInsert($tableName, $objects){
    
    global $servername, $username, $password, $dbname;
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // sql to create table
        $sqlCreate = "CREATE TABLE IF NOT EXISTS $tableName(
        date varchar(15) NOT NULL PRIMARY KEY,
        name varchar(50),
        symbol varchar(6),
        outstandingShares int(15),
        open double,
        close double,
        high double,
        low double,
        volume int(15),
        shortratio double,
        dayslow int(5),
        dayshigh int(5),
        yearslow int(5),
        yearshigh int(5),
        previousClose varchar(20),
        averageDailyVolume varchar(20),
        percentDifference varchar(20),
        dayDifference varchar(20),
        currency varchar(20),
        afterHoursAdjustmentRealtime varchar(20),
        differPercentRealtime varchar(20),
        differenceFromyearHigh varchar(20),
        percentDifferenceFromYearHigh varchar(20),
        daysRange varchar(20),
        daysRangeRealtime varchar(20),
        fiftyDayMovingAverage varchar(20),
        twoHundredDayMovingAverage varchar(20),
        differenceFromTwoHundredDayMovingAverage varchar(20),
        differenceFromFiftyDayMovingAverage varchar(20),
        percentDifferenceFromFiftyDayMovingAverage varchar(20)
       
        )";
    
        // use exec() because no results are returned
        $conn->exec($sqlCreate);
    }
    catch(PDOException $e)
    { echo $sqlCreate . "<br>" . $e->getMessage() . "<br><br>"; }
    
    try {
        $sqlInsert = "INSERT INTO $tableName
         (date, name, symbol, outstandingShares, open, high, low, close, volume)
        VALUES ('$objects[Date]', '$objects[Name]', '$objects[Symbol]', '$objects[outstanding_shares]', '$objects[Open]', '$objects[High]', '$objects[Low]', '$objects[Close]', '$objects[Volume]')";
        
        // use exec() because no results are returned
        $conn->exec($sqlInsert);
    }
    catch(PDOException $e)
    {
        echo $sqlInsert . "<br>" . $e->getMessage();
    }        
    $conn = null;    
}

/*
 * createAndInsertDailyStockInfo
 * 
 * Input: Eligible stock and it's corresponding information
 * Output: None
 */
function createAndInsertDailyStockInfo($objects){
    global $servername, $username, $password;
    $dbname = "daily_stock_info";
    $stockSymbol = $objects['symbol'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // sql to create table
        $sqlCreate = "CREATE TABLE IF NOT EXISTS $stockSymbol(
        date varchar(15) NOT NULL PRIMARY KEY ,
        name varchar(50),
        symbol varchar(6),
        open  varchar(20),
        previousClose varchar(20),
        volume varchar(20),
        averageDailyVolume varchar(20),
        change_percentChange varchar(20),
        dayChange varchar(20),
        currency varchar(20),
        afterHoursChangeRealtime varchar(20),
        daysLow varchar(20),
        daysHigh varchar(20),
        yearLow varchar(20),
        yearHigh varchar(20),
        changePercentRealtime varchar(20),
        changeFromYearHigh varchar(20),
        percentChangeFromYearHigh varchar(20),
        daysRange varchar(20),
        daysRangeRealtime varchar(20),
        fiftyDayMovingAverage varchar(20),
        twoHundredDayMovingAverage varchar(20),
        changeFromTwoHundredDayMovingAverage varchar(20),
        changeFromFiftyDayMovingAverage varchar(20),
        percentChangeFromFiftyDayMovingAverage varchar(20)
        )";
    
        // use exec() because no results are returned
        $conn->exec($sqlCreate);
    }
    catch(PDOException $e)
    { echo $sqlCreate . "<br>" . $e->getMessage() . "<br><br>"; }    
    
    try {
        $sqlInsert = "INSERT INTO $stockSymbol
         (date, name, symbol, open, previousClose, volume, averageDailyVolume, change_percentChange, dayChange, currency, afterHoursChangeRealtime, 
         daysLow, daysHigh, yearLow, yearHigh, changePercentRealtime, changeFromYearHigh, percentChangeFromYearHigh, daysRange,
         daysRangeRealtime, fiftyDayMovingAverage, twoHundredDayMovingAverage, changeFromTwoHundredDayMovingAverage, changeFromFiftyDayMovingAverage,
         percentChangeFromFiftyDayMovingAverage)
         VALUES ('$objects[LastTradeDate]', '$objects[Name]', '$objects[symbol]', '$objects[Open]', '$objects[PreviousClose]', '$objects[Volume]',
          '$objects[AverageDailyVolume]', '$objects[Change_PercentChange]', '$objects[Change]', '$objects[Currency]', '$objects[AfterHoursChangeRealtime]',
          '$objects[DaysLow]', '$objects[DaysHigh]', '$objects[YearLow]', '$objects[YearHigh]', '$objects[ChangePercentRealtime]', '$objects[ChangeFromYearHigh]',
          '$objects[PercebtChangeFromYearHigh]', '$objects[DaysRange]', '$objects[DaysRangeRealtime]', '$objects[FiftydayMovingAverage]', '$objects[TwoHundreddayMovingAverage]',
          '$objects[ChangeFromTwoHundreddayMovingAverage]', '$objects[ChangeFromFiftydayMovingAverage]', '$objects[PercentChangeFromFiftydayMovingAverage]')";
        
        // use exec() because no results are returned
        $conn->exec($sqlInsert);
    }
    catch(PDOException $e){
        echo $sqlInsert . "<br>" . $e->getMessage();
    }        
    $conn = null;     
}

?>