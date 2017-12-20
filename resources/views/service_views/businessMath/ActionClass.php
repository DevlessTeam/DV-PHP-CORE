<?php
/**
 * Created by Devless.
 * Author: 05044545
 * Date Created: 5th of July 2017 03:32:13 PM
 * Service: businessMath
 * Version: 1.3.0
 */

use App\Helpers\ActionClass;
//Action method for serviceName
class businessMath
{
    public $serviceName = 'businessMath';

 


    /**
     * Calculate the simple interest on a loan 
     * @param integer/float $principal
     * @param integer/float $rate
     * @param integer $duration
     * @return integer
     * @ACL public
     */
    public function findSimpleInterestOf($principal,$rate,$duration)
    {       
            return  ($rate * $duration * $principal)/100;  
    }

    /**
     * Find the compound interest on a loan 
     * @param integer/float $principal
     * @param integer/float $rate
     * @param integer $duration 
     * @param integer $numberofTimesPerYear
     * @return integer
     * @ACL public
     */

    public function findCompoundInterestOf($principal, $rate, $duration, $numberofTimesPerYear)
    {       
            $rate = $rate/100;
            $base = 1 + $rate/$numberofTimesPerYear;
            $exp = $numberofTimesPerYear * $duration;
            $amount = $principal * pow($base, $exp);
            return $amount;  
    }


    /**
     * Find price after applying discount 
     * @param integer/float $original_price
     * @param integer/float $discount
     * @return integer
     * @ACL public
     */
    public function findPriceAfterDiscountOf($original_price,$discount)
    {
        $remainingPercent = 1-($discount/100);
        return $original_price * $remainingPercent;
    }                                               

    /**
     * Calculate the amortized loan payment
     * @param integer/float $amtBorrowed
     * @param integer/float $interestPerPeriod
     * @param integer $numberofPayment
     * @return integer
     * @ACL public
     */
    public function findAmortizedLoanPayment($amtBorrowed, $interestPerPeriod,$numberofPayment)
    {
        return $amtBorrowed * $interestPerPeriod / 1 - pow((1 + $interestPerPeriod + $interestPerPeriod), -$numberofPayment);
    }

    /**
     * Calculate profit of an item sold
     * @param integer/float $cost_price
     * @param integer/float $selling_price 
     * @return integer
     * @ACL public
     */
    public function findProfit($cost_price, $selling_price)
    {
        return $selling_price - $cost_price;
    }

    /**
     * Calculate loss of an item sold 
     * @param integer/float $cost_price
     * @param integer/float $selling_price
     * @return integer 
     * @ACL public
     */
    public function findLoss($cost_price, $selling_price)
    {
        return $cost_price - $selling_price;
    }


    /**
     * convert from one currency to another(internal use only)
     * @ACL private
     */
    public function getCurrencyObject($from,$to)
    {
        return json_decode(file_get_contents('https://dev.kwayisi.org/apis/forex/'.$from.'/'.$to));
    }


    /**
     * Convert from one currency to another
     * @param integer/float $from
     * @param integer/float $to
     * @param integer $amount
     * @ACL public
     * 
     */

    function convertCurrency($from,$to,$amount)
    {
        $data = self::getCurrencyObject($from,$to);
        $rate = $data->rate;
        
        return $rate * $amount;
    }

    /**
     * convert from one currency to another currency for a specific date
     * @param integer/float $from
     * @param integer/float $to
     * @param integer $amount
     * @param string $date (yyyy-mm-dd)
     * @ACL public
     */
    function convertCurrencyForDate($from,$to,$amount,$date)
    {
        $data = json_decode(file_get_contents('https://dev.kwayisi.org/apis/forex/'.$from.'/'.$to.'/'.$date));
        $rate = $data->rate;
        
        return $rate * $amount ;
    }
    

    /**
     * List out all possible callbale methods as well as get docs on specific method 
     * @param $methodToGetDocsFor
     * @return $this;
     */
    public function help($methodToGetDocsFor=null)
    {
        $serviceInstance = $this;
        $actionClass = new ActionClass();
        return $actionClass->help($serviceInstance, $methodToGetDocsFor=null);   
    }
    /**
     * This method will execute on service importation
     * @ACL private
     */
    public function __onImport()
    {
        //add code here

    }

    /**
     * This method will execute on service exportation
     * @ACL private
     */
    public function __onDelete()
    {
        //add code here
    }




}

