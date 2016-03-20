<?php

$counter = 0;
$handle = fopen("include/applOneYear.csv", "r");
$data = fgetcsv($handle, 1000, ",");
$date_price_array = array();
$date_array       = array();
$price_array      = array();
$volume_array     = array();
$percent_change_array = array();
$difference_volume_array = array();

echo "<br>" . count($data) . "<br>";
echo $data[6] . " " . $data[0] . "<br>"; 


while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    //Date,Open,High,Low,Close,Volume,Adj Close
    $date          = $data[0];
    $price         = $data[6];
    $adjustedClose = $date . " " . $price;
    $volume        = number_format($data[5]);
    
    if ( !in_array($adjustedClose, $date_price_array) ) {
        array_push($date_price_array, $adjustedClose);
    }
    if( !in_array($date, $date_array)){
        array_push($date_array, $date);
    }
    if( !in_array($price, $price_array)){
        array_push($price_array, $price);
    }
    if( !in_array($volume, $volume_array)){
        array_push($volume_array, $volume);
        if(count($volume_array) > 1){
            $difference = $volume - $volume_array[$counter - 1];
            $difference = number_format($difference);
            array_push($difference_volume_array,$difference);
        }
    }
    $counter++;
}
fclose($handle);

$date_array       = array_reverse($date_array);
$date_price_array = array_reverse($date_price_array);
$price_array      = array_reverse($price_array);
$volume_array     = array_reverse($volume_array);
$difference_volume_array = array_reverse($difference_volume_array);

//compare percent decline form day to day
foreach ($price_array as $index=>$node) {
	if($index > 0){
	    $decrease = $price_array[$index-1] - $node;
        $percent_decrease = round($decrease / $price_array[$index] * 100, 3);
        if( !in_array($decrease,$percent_change_array)){
            array_push($percent_change_array,$percent_decrease);
        }
        echo $percent_decrease . "<br>";
	}
}

echo "Number elements: " . count($price_array) . "<br>";
echo "Largest volume: " . max($volume_array) . "<br>";
echo "Smallest volume: " . min($volume_array) . "<br>";
echo "Most stocks gained in one day: " . max($difference_volume_array) . "<br>";
echo "Most stocks lost in one day: " . min($difference_volume_array) . "<br>";
echo "Largest percent change: "   . max($percent_change_array) . "<br>";
echo "Smallest percent change: " . min($percent_change_array) . "<br>";


// print '<pre>'; print_r($date_array); print '</pre>';
// print '<pre>'; print_r($date_price_array); print '</pre>';

 
?>