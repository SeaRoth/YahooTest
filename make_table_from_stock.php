<!DOCTYPE html>
<html>
<body>

<?php
  require_once('dbHelper.php');

  echo "<table style='border: solid 1px black;'>";
  echo "<tr><th>Date</th><th>Name</th><th>Symbol</th><th>Open</th><th>Close</th><th>High</th><th>Low</th><th>Volume</th></tr>";

class TableRows extends RecursiveIteratorIterator { 
     function __construct($it) { 
         parent::__construct($it, self::LEAVES_ONLY); 
     }

     function current() {
         return "<td style='width: 150px; border: 1px solid black;'>" . parent::current(). "</td>";
     }

     function beginChildren() { 
         echo "<tr>"; 
     } 

     function endChildren() { 
         echo "</tr>" . "\n";
     } 
} 

foreach(new TableRows(new RecursiveArrayIterator(fetchCertainStock('athx'))) as $k=>$v) { 
         echo $v;
     }


echo "</table>";
?>  

</body>
</html>