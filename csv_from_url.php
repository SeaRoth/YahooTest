<?php
$url = 'http://finance.yahoo.com/d/quotes.csv?s=AAL&f=j2,b';    
$sharesOustanding = file_get_contents($url);
$currentData = explode(",", $sharesOustanding);
echo $url . "<br>";
var_dump($currentData);
    
    
?>