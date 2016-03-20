<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>Vizualyse PK</title>
   <script src="https://www.google.com/jsapi"></script>
   <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<!--   <script src="http://prithwis.x10.bz/charts/jquery.csv-0.71.js"></script> -->
   <script src="https://jquery-csv.googlecode.com/files/jquery.csv-0.71.js"></script>
   <script type='text/javascript'>
   // load the visualization library from Google and set a listener
   google.load("visualization", "1", {packages:["corechart","controls"]});
   google.setOnLoadCallback(drawCandlefromQuandlPK);
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
   function drawCandlefromQuandlPK(){
   
   var dashboardPK = new google.visualization.Dashboard(
       document.getElementById('div_dashboardPK'));
//----------------------------------------------------------------------------------------------------------------------------------------       
   var controlPK = new google.visualization.ControlWrapper({
     'controlType': 'ChartRangeFilter',
     'containerId': 'div_controlPK',
     'options': {
       // Filter by the date axis.
       'filterColumnIndex': 0,
       'ui': {
         'chartType': 'LineChart',
         'chartOptions': {
  //         'chartArea': {'width': '90%'},
           'hAxis': {'baselineColor': 'none'}
         },
         // Display a single series that shows the closing value of the stock.
         // Thus, this view has two columns: the date (axis) and the stock value (line series).
         'chartView': {
           'columns': [0, 4]
         },
         // 1 day in milliseconds = 24 * 60 * 60 * 1000 = 86,400,000
         'minRangeSize': 86400000
       }
     },
     // Initial range: 2007-11-31 to 2008-09-31.
     'state': {'range': {'start': new Date(2007, 10, 31), 'end': new Date(2008, 8, 31)}}
   });
 //--------------------------------------------------------------------------------------------------------------------------------------------
   var chartPK = new google.visualization.ChartWrapper({
     'chartType': 'CandlestickChart',
     'containerId': 'div_chartPK',
     'options': {
       title: "Reliance Stock Prices at NSE from Quandl",
       // Use the same chart area width as the control for axis alignment.
//       'chartArea': {'height': '80%', 'width': '90%'},
//       'hAxis': {'slantedText': false},
          'vAxis': {'viewWindow': {'min': 0, 'max': 3500}},
       'legend': {'position': 'none'}
     },
   });
//--------------------------------------------------------------------------------------------------------------------------------------------
     // grab the CSV
         //  $.get("https://www.quandl.com/api/v1/datasets/NSE/RELIANCE.csv?&trim_start=1998-03-20&trim_end=2013-12-27&collapse=weekly&
         $.get("https://www.quandl.com/api/v1/datasets/NSE/RELIANCE.csv??&auth_token=xxxxxxxxxxxxxxxxxxxxx&trim_start=1998-03-20&trim_end=2013-12-27&collapse=weekly&sort_order=desc", function(csvStringPK) {
         // transform the CSV string into a 2-dimensional array
            var arrayDataPK = $.csv.toArrays(csvStringPK, {onParseValue: $.csv.hooks.castToScalar});
         // this new DataTable object holds all the data
            var dataPK = new google.visualization.arrayToDataTable(arrayDataPK);
            
         // this view selects only a subset of the data, that is columns 0,3,1,5,2 that is appropriate for the candlestick chart
         // more importantly it converts the 0th column from string to date using the example given in http://jsfiddle.net/asgallant/8RFsB/1/
         // the string to date conversion is very important as otherwise the ControlWrapper will not work
            
            var viewPK = new google.visualization.DataView(dataPK);
            viewPK.setColumns([{
                type: 'date',
                label: dataPK.getColumnLabel(0),
                calc: function (dt, row) {
                    // split the string into date and time
                    var valArr = dt.getValue(row, 0).split(' ');
                    // split the date into year, month, day
                    var dateArr = valArr[0].split('-');
                   // create a new Date object from the data
                   // note that we subtract 1 from the month to convert to javascripts 0-index
                   var date = new Date(dateArr[0], dateArr[1] - 1, dateArr[2]);
                  // return the date and the formatted value as set in the original DataTable
                  return {
                      v: date,
                      f: dt.getFormattedValue(row, 0)
                  };
               }
          }, 3, 1,5,2]);
            
  //-----------------------------------------------------------------------------------------------------------------------------------------------          
    
        dashboardPK.bind(controlPK, chartPK);
        dashboardPK.draw(viewPK);
         });
   }
   </script>
</head>
<body>
<div id="div_dashboardPK">
<div id="div_chartPK" style="height: 600px; width: 900px;">
</div>
<div id="div_controlPK" style="height: 50px; width: 900px;">
</div>
</div>
</body>
</html>