<?php
require_once('dbHelper.php');
require_once('functions_stocks.php');
require_once('myClass.php');
session_start();
$stockList = fetchAllStockSymbols();
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" charset="utf-8" content="width=device-width, initial-scale=1">
      
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js"></script>
    <script src="include/cookies.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="include/css_for_autocomplete.css">
    <link rel="stylesheet" type="text/css" href="include/mainsheet.css">
    
    <script type="text/javascript">
        $(document).ready(function(){
            
            if(getCookie("minPrice") != ""){
                document.getElementById('minPrice').value = getCookie('minPrice');    
            }
            if(getCookie("maxPrice") != ""){
                document.getElementById('maxPrice').value = getCookie('maxPrice');    
            }        
            if(getCookie("minBeta") != ""){
                document.getElementById('minBeta').value = getCookie('minBeta');    
            }
            if(getCookie("minShares") != ""){
                document.getElementById('minShares').value = getCookie('minShares');    
            }
            if(getCookie("maxShares") != ""){
                document.getElementById('maxShares').value = getCookie('maxShares');    
            }
            if(getCookie("declinedYear") != ""){
                document.getElementById('declinedYear').value = getCookie('declinedYear');    
            }
            if(getCookie("monthsSIGrowth") != ""){
                document.getElementById('monthsSIGrowth').value = getCookie('monthsSIGrowth');    
            }
            if(getCookie("perDeclinedYest") != ""){
                document.getElementById('perDeclinedYest').value = getCookie('perDeclinedYest');    
            }
            if(getCookie("dailyAvgVol") != ""){
                document.getElementById('dailyAvgVol').value = getCookie('dailyAvgVol');
                //alert(dailyAvgVol.value);    
            }
            if(getCookie("gapTrading") != ""){
                document.getElementById('gapTrading').value = getCookie('gapTrading');    
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
                },
                error: function() {
                    alert("Something went wrong!");
                }
            });           
              $(this).typeahead('setQuery', '');
            });        
        });  
    </script>
  
</head>
<body>

<div class="bs-example">
    <h2>Type stock name</h2>
    <input type="text" class="typeahead tt-query" autocomplete="off" spellcheck="false">
    <p id="recent-search-list">Recent Stocks: </p>
</div>

<div>
    <img id="loading_spinner" src="images/spinner.gif">
</div>

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
          <input type="text" id="declinedYear" class="form-control" placeholder="Example: 30% or 30" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>      
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">months increasing SI:</span>
          <input type="text" id="monthsSIGrowth" class="form-control" placeholder="Example: 5" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>      
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">% declined from yesterday:</span>
          <input type="text" id="perDeclinedYest" class="form-control" placeholder="Example: 5" aria-describedby="basic-addon1"
          onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>        
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Doubled from daily avg vol in todays trading:</span>
          <input id="dailyAvgVol" class="checkbox_left" type="checkbox" checked="checked"></input>
        </div>
        
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Days gap in trading:</span>
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

<script type="text/javascript">
    function submit(){
        
      $('#loading_spinner').show();
      
      setCookie("minPrice",document.getElementById("minPrice").value,90);
      setCookie("maxPrice",document.getElementById("maxPrice").value,90);
      setCookie("minBeta",document.getElementById("minBeta").value,90);
      setCookie("minShares",document.getElementById("minShares").value,90);
      setCookie("maxShares",document.getElementById("maxShares").value,90);
      setCookie("declinedYear",document.getElementById("declinedYear").value,90);
      setCookie("monthsSIGrowth",document.getElementById("monthsSIGrowth").value,90);
      setCookie("perDeclinedYest",document.getElementById("perDeclinedYest").value,90);
      setCookie("dailyAvgVol",document.getElementById("dailyAvgVol").value,90);
      setCookie("gapTrading",document.getElementById("gapTrading").value,90);
      
        var post_data = "minPrice="+minPrice.value+"&maxPrice="+maxPrice.value+"&minBeta="+minBeta.value+"&minShares="+minShares.value
        +"&maxShares="+maxShares.value+"&declinedYear="+declinedYear.value+"&monthsSIGrowth="+monthsSIGrowth.value
        +"&perDeclinedYest="+perDeclinedYest.value+"&dailyAvgVol="+dailyAvgVol.value+"&gapTrading="+gapTrading.value;
        
        
        $.ajax({
            url: 'find_eligible_stocks.php?'+post_data,
            type: 'POST',
            data: post_data,
            dataType: 'html',
            success: function(data) {
                $('.b').html(data);
                $('#loading_spinner').hide();
            },
            error: function() {
                alert("Something went wrong!");
            }
        });  
    }
</script>




</html>
