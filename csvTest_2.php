<?php

$handle = fopen("include/sp_3_months.csv", "r");
$data = fgetcsv($handle, 1000, ","); 
$mDateAndCloseArray = array();
$counter = 0;

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $mDateAndCloseArray[$counter] = round($data[6],2);
    $counter++;
}
fclose($handle);
echo $mDateAndCloseArray[2];

?>