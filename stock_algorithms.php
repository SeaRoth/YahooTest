<?php
require_once('dbHelper.php');
include_once('functions_stocks.php');
require('myClass.php');
session_start();
$stockList = fetchAllStockSymbols();

?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js"></script>
  <script src="include/cookies.js" type="application/javascript"></script>
  
  <script type="application/javascript">
    $(function(){
        $(".dropdown-menu li a").click(function(){
          $(this).parents(".dropdown").find('.btn').html($(this).text() + ' <span class="caret"></span>');
          $(this).parents(".dropdown").find('.btn').val($(this).data('value'));
        });
    });  
  </script>
  
  <script type="application/javascript">
    
  </script>
  
  <script type="text/javascript">
    $(document).ready(function(){
        var myArray = new Array();
        
        if(getCookie("recent_search_symbols") != ""){
            myArray = getCookie("recent_search_symbols");
            document.getElementById('recent-search-list').innerHTML = myArray;
        }else{
            setCookie("recent_search_symbols", myArray, 30);
        }
        
        $('input.typeahead').typeahead({
            name: 'accounts',
            local: <?php echo json_encode($stockList); ?> 
        }).on('typeahead:selected', function(event, selection){
          
        $('#loading_spinner').show();
        var post_data = "symbol="+selection.value;
        $.ajax({
            url: 'advanced_stock_info.php?symbol=' + selection.value,
            type: 'POST',
            data: post_data,
            dataType: 'html',
            success: function(data) {
                $('.b').html(data);
        //Moved the hide event so it waits to run until the prior event completes
        //It hide the spinner immediately, without waiting, until I moved it herr
                $('#loading_spinner').hide();
                
                
//COPY FIRST DIV TO SECOND
                // var firstDivContent = document.getElementById('a');
                // var secondDivContent = document.getElementById('b');
                // secondDivContent.innerHTML = firstDivContent.innerHTML;
                
                myArray.push(34);
                
            },
            error: function() {
                alert("Something went wrong!");
            }
        });           
          $(this).typeahead('setQuery', '');
        });        
    });  
</script>
<link rel="stylesheet" type="text/css" href="include/css_for_autocomplete.css">
<link rel="stylesheet" type="text/css" href="include/mainsheet.css">
  
</head>
<body>

<div class="bs-example">
    <h2>Type stock name</h2>
    <input type="text" class="typeahead tt-query" autocomplete="off" spellcheck="false">
    <p id="recent-search-list">Recent Stocks: </p>
</div>

<img id="loading_spinner" src="images/spinner.gif">

  <div id="wrapper">
      
    <div id="a" class="a">
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Min Price:</span>
          <input type="text" id="minPrice" class="form-control" placeholder="Example: 5" aria-describedby="basic-addon1" 
          onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Max Price:</span>
          <input type="text" id="maxPrice" class="form-control" placeholder="Example: 400" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Min Beta:</span>
          <input type="text" id="minBeta" class="form-control" placeholder="Example: 1.1" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Min Shares outstanding:</span>
          <input type="text" id="minShares" class="form-control" placeholder="Example: 5M or 5000000" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Max shares outstanding:</span>
          <input type="text" id="maxShares" class="form-control" placeholder="Example: 300M or 300000000" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Declined % from 52-week:</span>
          <input type="text" id="declined52" class="form-control" placeholder="Example: 30% or 30" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>      
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">months increasing SI:</span>
          <input type="text" id="monthsSIGrowth" class="form-control" placeholder="Example: 5" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>      
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Percent declined from yesterday:</span>
          <input type="text" id="perDeclinedYest" class="form-control" placeholder="Example: 5" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>        
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">At least doubled from daily avg vol in todays trading:</span>
          <input id="dailyAvgVol" class="checkbox_left" type="checkbox" checked="checked"></input>
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">With gap in trading in the last trading sessions:</span>
          <input type="text" id="gapTrading" class="form-control" placeholder="Example: 5" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>                
        
        <button onclick="submit()" type="button" class="btn btn-primary">Submit</button>                
    </div>
    
    <div id="b" class="b">
        <br>
        <?php
            $mSymbolsArray = fetchAllStockSymbols();
            //for($i=0;$i<count($mSymbolsArray);$i++){
            for($i=0;$i<2;$i++){  
                $eligibleStocksArray = fetchEligibleStockInformation($mSymbolsArray[$i]);
                echo "<br> <br>";
                var_dump($eligibleStocksArray);
                createEligibleTableAndInsert($eligibleStocksArray);    
            }
        ?>    
        
    </div>
  </div>
</body>

<script type="application/javascript">
    function submit(){
        var minPrice = document.getElementById("minPrice").value;
        var maxPrice = document.getElementById("maxPrice").value;
        var minBeta  = document.getElementById("minBeta").value;
       var minShares = document.getElementById("minShares").value;
       var maxShares = document.getElementById("maxShares").value;
      var declined52 = document.getElementById("declined52").value;
  var monthsSIGrowth = document.getElementById("monthsSIGrowth").value;
var perDeclinedYest  = document.getElementById("perDeclinedYest").value;
     var dailyAvgVol = document.getElementById("dailyAvgVol").value;
     var gapTrading  = document.getElementById("gapTrading").value;
      
  
  
    }
    
</script>

</html>
