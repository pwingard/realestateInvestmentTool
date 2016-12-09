<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$outputObj=$_SESSION['data'];

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=REITProjection.csv");
header("Pragma: no-cache");
header("Expires: 0");

//print_r($outputObj);
/*
 *     [amortInputsArr] => Array
        (
            [loan_amount] => 268000
            [term_years] => 30
            [interest] => 4.1
            [terms] => 12
        )
    [amortSummaryArr] => Array
        (
            [total_pay] => 466189.78844
            [total_interest] => 198189.78844
        )
    [amortScheduleArr] => Array
        (
            [0] => Array
                (
                    [payment] => 1294.97163455
                    [interest] => 915.666666667
                    [principal] => 379.304967888
                    [balance] => 267620.695032
                )
              ...
    [address] => 1991 N. Williamsburg Dr. Suite A
    [output] => Array	
        (	
            [0] => stdClass Object	
                (	
                    [date] => Feb 2017	
                    [YearMonth] => 0_1	
                    [MortTaxIns] => $1	717.8
                    [NetInc] => $214.20	
                    [TotalInvested] => $66	785.8
                    [HouseVal] => $335	0
                    [BalOwed] => $267	620.7
                    [Equity] => $67	379.3
                )
              ...
 */


outputCSV($outputObj);

function outputCSV($outputObj) {
    
    $title[]="";   
    $title[]="";   
    
    $outputBuffer = fopen("php://output", 'w');
    
    date_default_timezone_set('america/new_york');
    if(date('I')){
        $tz="EST";
    }else {
        $tz="ET";
    }
    
    $title[]="Real Estate Investment Tool";
    $title[]="Pete Wingard © 2016 bizpfw@gmail.com";
    $title[]=""; 
    $title[]=""; 
    $title[]="Projected results for ".$outputObj->address." on ".date('F jS, Y h:i:s a', time())." ". $tz;
    $title[]=""; 
    $title[]=""; 
    foreach($title as $row) {//rows
        $thisrow[]=$row;
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }

    $heading[]="Mortgage Payment: $".number_format(round($outputObj->amortScheduleArr[0]["payment"], 2),2);
    $heading[]="Amount Borrowed: $".number_format(round($outputObj->amortInputsArr["loan_amount"], 2),2);
    $heading[]="Interest Rate: ".number_format(round($outputObj->amortInputsArr["interest"], 2),2)."% APR";
    $heading[]="Term: ".$outputObj->amortInputsArr["term_years"]." years";
    $heading[]="Total Paid: $".number_format(round($outputObj->amortSummaryArr["total_pay"], 2),2);
    $heading[]="Total Interest: $".number_format(round($outputObj->amortSummaryArr["total_interest"], 2),2);
    $heading[]="";
    $heading[]="";
    
    foreach($heading as $row) {//rows
        $thisrow[]=$row;
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }
    
    
    
    foreach($outputObj->output as $row) {//rows
        foreach ($row as $colName=>$cell) {//column names
            if($colName!="YearMonth")//skip a column
                $thisrow[]=$colName;
        }
        fputcsv($outputBuffer, $thisrow);
        break;
    }
    $thisrow=array();
    foreach($outputObj->output as $row) {//rows
        foreach ($row as $key=>$cell) {//cells
            if($key!="YearMonth")//skip a column
                $thisrow[]=$cell;
        }
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }
    fclose($outputBuffer);
 }