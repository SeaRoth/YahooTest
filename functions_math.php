<?php

/*
 * findBetaValueForStock() 
 * 
 * Input: Stock Symbol
 * Output: Beta Value
 */
function findBetaValueForStock($mSymbol){
    
}

/*
 * standard_covariance($aValues,$bValues)
 * 
 * Input: 
 *  -Daily percent change for stock
 *  -Daily percent change for S&P 500
 * Output: Covariance double
 * 
 */
function standard_covariance($aValues,$bValues)
{
        $a= (array_sum($aValues)*array_sum($bValues))/count($aValues);
        $ret = array();
        for($i=0;$i<count($aValues);$i++)
        {
            $ret[$i]=$aValues[$i]*$bValues[$i];
        }
        $b=(array_sum($ret)-$a)/(count($aValues)-1);        
        return (float) $b; 
} 

/*
 * average($arr)
 * 
 * Input: Array of values
 * Output: Average double
 */
function average($arr)
{
    if (!count($arr)) return 0;

    $sum = 0;
    for ($i = 0; $i < count($arr); $i++)
    {
        $sum += $arr[$i];
    }

    return $sum / count($arr);
}

/*
 * variance($arr)
 * 
 * Input: Percent Change array
 * Output: Variance double
 */
function variance($arr)
{
    if (!count($arr)) return 0;

    $mean = average($arr);

    $sos = 0;    // Sum of squares
    for ($i = 0; $i < count($arr); $i++)
    {
        $sos += ($arr[$i] - $mean) * ($arr[$i] - $mean);
    }

    return $sos / (count($arr)-1);  // denominator = n-1; i.e. estimating based on sample 
                                    // n-1 is also what MS Excel takes by default in the
                                    // VAR function
}














?>