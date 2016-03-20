<?php
require_once('dbHelper.php');
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
        //It hide the spinner immediately, without waiting, until I moved it here
                $('#loading_spinner').hide();
                document.getElementById('recent-search-list').innerHTML = document.getElementById('recent-search-list').innerHTML + " " + selection.value;
                
                
                
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
    <div class="a">Text</div>
    <div class="b"></div>
    <div class="c">Text</div>
    <div class="d">Text</div>
    <div class="e">Text</div>

  </div>



</body>
</html>
