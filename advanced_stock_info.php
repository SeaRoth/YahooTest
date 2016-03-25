<?php
require('myClass.php');
include_once('functions_stocks.php');
session_start();

/*
 * 1.  
 * 
 */

if (isset($_GET['symbol'])) {
    $_SESSION['symbol'] = $_GET['symbol'];
    echo $_SESSION['symbol'] . "<br><br>";
}
     
     /* $mPricingApi 
        a – ask
        b – bid
        b2 – ask (realtime)
        b3 – bid (realtime)
        p – previous close
        o – open
      */
        $mPricingApi = "abb2b3po";
  /* $mDividendsApi
     y – dividend yield
     d – dividend per share
     r1 – dividend pay date
     q – ex-dividend date
   */
        $mDividendsApi = "ydr1q";
    /* $mDateApi
        c1 – change
        c – change & percentage change
        c6 – change (realtime)
        k2 – change percent
        p2 – change in percent
        d1 – last trade date
        d2 – trade date
        t1 – last trade time
     */
        $mDateApi = "c1cc6k2p2d1d2t1";
     /* $mAveragesApi
        c8 – after hours change
        c3 – commission
        g – day’s low
        h – day’s high
        k1 – last trade (realtime) with time
        l – last trade (with time)
        l1 – last trade (price only)
        t8 – 1 yr target price
        m5 – change from 200 day moving average
        m6 – percent change from 200 day moving average
        m7 – change from 50 day moving average
        m8 – percent change from 50 day moving average
        m3 – 50 day moving average
        m4 – 200 day moving average
      */   
        $mAveragesApi = "c8c3ghk1ll1t8m5m6m7m8m3m4";
      /* $mMiscApi
        w1 – day’s value change
        w4 – day’s value change (realtime)
        p1 – price paid
        m – day’s range
        m2 – day’s range (realtime)
        g1 – holding gain percent
        g3 – annualized gain
        g4 – holdings gain
        g5 – holdings gain percent (realtime)
        g6 – holdings gain (realtime)
        t7 – ticker trend
        t6 – trade links
        i5 – order book (realtime)
        l2 – high limit
        l3 – low limit
        v1 – holdings value
        v7 – holdings value (realtime)
        s6 – revenue
       */          
        $mMiscApi = "w1w4p1mm2g1g3g4g5g6t7t6i5l2l3v1v7s6";
        /* $m52WeekApi
            k – 52 week high
            j – 52 week low
            j5 – change from 52 week low
            k4 – change from 52 week high
            j6 – percent change from 52 week low
            k5 – percent change from 52 week high
            w – 52 week range
         */
         $m52WeekApi = "kjj5k4j6k5w";
         /* $mSymbolInfoApi
            v – more info
            j1 – market capitalization
            j3 – market cap (realtime)
            f6 – float shares
            n – name
            n4 – notes
            s – symbol
            s1 – shares owned
            x – stock exchange
            j2 – shares outstanding
          */
        $mSymbolInfoApi = "vj1j3f6nn4ss1xj2";
        /*  $mVolumeApi
            v – volume
            a5 – ask size
            b6 – bid size
            k3 – last trade size
            a2 – average daily volume
         */
         $mVolumeApi = "va5b6k3a2";
         /* $mRatioApi
            e – earnings per share
            e7 – eps estimate current year
            e8 – eps estimate next year
            e9 – eps estimate next quarter
            b4 – book value
            j4 – EBITDA
            p5 – price / sales
            p6 – price / book
            r – P/E ratio
            r2 – P/E ratio (realtime)
            r5 – PEG ratio
            r6 – price / eps estimate current year
            r7 – price /eps estimate next year
            s7 – short ratio
          */
          $mRatioApi = "ee7e8e9b4j4p5p6rr2r5r6r7s7";
        
    
    $mStockSymbol = $_SESSION['symbol'];
    $mSpecs = "nsopva2c1c8ghc6k4k5mm2m3m4m5m7m8";
    $thisArray = fetchCertainDataForSelectStock($mStockSymbol, $mSpecs);
    $thisArray = array_shift($thisArray);
    
    $mName = $thisArray[0];
    $mSymbol = $thisArray[1];
    $mOpen = $thisArray[2];
    $mPreviousClose = $thisArray[3];
    $mVolume = $thisArray[4];
    $mAverageDailyVolume = $thisArray[5];
    $mChange_percentChange = $thisArray[6];
    $mdayChange = $thisArray[7];
    $mAfterHoursChangeRealtime = $thisArray[8];
    $mDaysLow = $thisArray[9];
    $mDaysHigh = $thisArray[10];
    $mChangePercentRealtime = $thisArray[11];
    $mChangeFromYearHigh = $thisArray[12];
    $mPercentChangeFromYearHigh = $thisArray[13];
    $mDaysRange = $thisArray[14];
    $mDaysRangeRealtime = $thisArray[15];
    $mFiftyDayMovingAverage = $thisArray[16];
    $mTwoHundredDayMovingAverage = $thisArray[17];
    $mChangeFromTwoHundredDayMoingAverage = $thisArray[18];
    $mChangeFromFiftyDayMovingAverage = $thisArray[19];
    
    echo "<b>Is this info in the database? </b>" . (boolval(checkIfTableExists($mStockSymbol)) ? 'true' : 'false') . "<br>";
    echo "<b>Company Name: </b>" . $mName . "<br>";
    echo "<b>Symbol: </b>" . $mSymbol . "<br>";
    echo "<b>Open: </b>" . $mOpen . "<br>";
    echo "<b>PreviousClose: </b>" . $mPreviousClose . "<br>";
    echo "<b>Volume: </b>" . $mVolume . "<br>";
    echo "<b>Average Daily Volume: </b>" . $mAverageDailyVolume . "<br>";
    echo "<b>Change Percent Change: </b>" . $mChange_percentChange . "<br>";
    echo "<b>Day Change: </b>" . $mdayChange . "<br>";
    echo "<b>After hours change realtime: </b>" . $mAfterHoursChangeRealtime . "<br>";
    echo "<b>Days Low: </b>" . $mDaysLow . "<br>";
    echo "<b>Days High: </b>" . $mDaysHigh . "<br>";
    echo "<b>Change percent realtime: </b>" . $mChangePercentRealtime . "<br>";
    echo "<b>Change from year high: </b>" . $mChangeFromYearHigh . "<br>";
    echo "<b>Percent change from year high: </b>" . $mPercentChangeFromYearHigh . "<br>";
    echo "<b>Days Range: </b>" . $mDaysRange . "<br>";
    echo "<b>Days Range realtime: </b>" . $mDaysRangeRealtime . "<br>";
    echo "<b>50 day moving average: </b>" . $mFiftyDayMovingAverage . "<br>";
    echo "<b>Two Hundred Day moving average: </b>" . $mTwoHundredDayMovingAverage . "<br>";
    echo "<b>Change from 200 day moving average: </b>" . $mChangeFromTwoHundredDayMoingAverage . "<br>";
    echo "<b>Change from 50 day moving average: </b>" . $mChangeFromFiftyDayMovingAverage . "<br>";
        

?>