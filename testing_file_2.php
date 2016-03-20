<?php
require_once("nyse_functions.php");

$thisArray = explodeTextFileToArray("Stock:", "include/testing_file.txt");

for($i = 0; $i < count($thisArray); $i++){
    echo $thisArray[$i] . "<br>";
}


?>