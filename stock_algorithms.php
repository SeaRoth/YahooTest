<?php
require_once('dbHelper.php');
require_once('nyse_functions.php');
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
                $('.a').html(data);
        //Moved the hide event so it waits to run until the prior event completes
        //It hide the spinner immediately, without waiting, until I moved it herr
                $('#loading_spinner').hide();
                
                var firstDivContent = document.getElementById('a');
                var secondDivContent = document.getElementById('b');
                secondDivContent.innerHTML = firstDivContent.innerHTML;
                
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
        <input id="checkbox" class="checkbox_left" type="checkbox" checked="checked"> $ > 5 </input>
        <div></div>
        <input id="checkbox" type="checkbox" aria-label="Checkbox" checked="checked"> OutstandingShares < 300M </input>   
        <div></div>
        <input id="checkbox" type="checkbox" aria-label="Checkbox" checked="checked"> OutstandingShares > 5M </input>
        <div></div>
        <input id="checkbox" type="checkbox" aria-label="Checkbox" checked="checked"> Declined 30% from 52-week high </input>
        <div></div>
        <input id="checkbox" type="checkbox" aria-label="Checkbox" checked="checked"> 3-consecutive months increasing S.I. </input>
        <div></div>
        <input id="checkbox" type="checkbox" aria-label="Checkbox" checked="checked"> Declined at least 10% from yest </input>
        <div></div>
        <input id="checkbox" type="checkbox" aria-label="Checkbox" checked="checked"> Doubled Volume in today </input>                
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
</html>
