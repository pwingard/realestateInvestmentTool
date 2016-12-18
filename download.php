<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//save the date to a session
$outputObj=$_SESSION['data'];

//set the headers to output a csv to download
header("Content-type: text/csv");
//name the downloaded file with the property address
header("Content-Disposition: attachment; filename=RE12C_".str_replace(' ', '', $outputObj->post["prprtyAddr"]).".csv");
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

//download
outputCSV($outputObj);

function outputCSV($outputObj) {
    
    $outputBuffer = fopen("php://output", 'w');
    
    date_default_timezone_set('america/new_york');
    if(date('I')){
        $tz="EST";
    }else {
        $tz="ET";
    }
    
    //section title
    $title[]="";
    $title[""]="TITLE SUMMARY";
    $title[]="";
    //name the file and stamp with a date and time
    $title[]="Report for '".$outputObj->post["prprtyAddr"]."' created on ".date('F jS, Y h:i:s a', time())." ". $tz;
    $title[]="RE12C The Real Estate Investment Tool";
    $title[]="Pete Wingard © 2016 bizpfw@gmail.com";
    $title[]=""; 

  
    //write the section
    foreach($title as $row) {//rows
        $thisrow[]="";
        $thisrow[]=$row;
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }
     
    //input data
    
    //section title
    $posted[]="";
    $posted[""]="INPUT SUMMARY";
    $posted[]="";
    
    //give the posted input readable names
    foreach ($outputObj->post as $field => $input) {
        
        switch ($field) {
            case "prprtyAddr":
                $posted["Property Address"]=$input;
                break;
            case "fxPrchPrcDlrAmt":
                $posted["Purchase Price of Property"]=$input;
                break;
            case "fxdDwnPmtPrct":
                $posted["Down Payment %"]=$input;
                break;
            case "termYrs":
                $posted["Term"]=$input;
                break;
            case "APR":
                $posted["APR"]=$input;
                break;
            case "strtMnthlyRent":
                $posted["Rent (monthly)"]=$input;
                break;
            case "mnthlyMngmtPrct":
                $posted["Monthly Management Percentage"]=$input;
                break;
            case "mnthlyMntnNCpImprPrct":
                $posted["Monthly Maintenance and Capitol Improvements Percentage"]=$input;
                break;
            case "mnthlyVacColLossPrct":
                $posted["Monthly Vacancy and Collection Losses Percentage"]=$input;
                break;
            case "fxdClsngCstDlrAmt":
                $posted["Closing Costs"]=$input;
                break;
            case "annlInsDlrAmt":
                $posted["Insurance"]=$input;
                break;
            case "annlTxsDlrAmt":
                $posted["Taxes"]=$input;
                break;
            case "annlApprcPrct":
                $posted["Property Appreciation Rate"]=$input;
                break;
            case "yrsToSale":
                $posted["Report Time Frame"]=$input;
                break;
            default:
                die("Field not forund!");
                break;
        }
    }

    //write to file section content
    foreach($posted as $field => $input) {//rows
        if(!is_numeric($field)){
            if($input!="INPUT SUMMARY"){
                $thisrow[]="";
            }
            
            $thisrow[]=$field;
            $thisrow[]=$input;
        }
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }

    //section title
    $heading[]="";
    $heading[""]="AMORTIZATION SUMMARY";
    $heading[]="";
    
    //section content
    $heading[]="Mortgage Payment: $".number_format(round($outputObj->amortScheduleArr[0]["payment"], 2),2);
    $heading[]="Amount Borrowed: $".number_format(round($outputObj->amortInputsArr["loan_amount"], 2),2);
    $heading[]="Interest Rate: ".number_format(round($outputObj->amortInputsArr["interest"], 2),2)."% APR";
    $heading[]="Term: ".$outputObj->amortInputsArr["term_years"]." years";
    $heading[]="Total Paid: $".number_format(round($outputObj->amortSummaryArr["total_pay"], 2),2);
    $heading[]="Total Interest: $".number_format(round($outputObj->amortSummaryArr["total_interest"], 2),2);
    $heading[]="";

    
    //write to file
    foreach($heading as $row) {//rows
        $thisrow[]="";
        $thisrow[]=$row;
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }
    
    //section title
    $roi[]="";
    $roi[""]="RETURN ON INVESTMENT";
    $roi[]="";
    
    //write to file 
    foreach($roi as $row) {//rows
        $thisrow[]="";
        $thisrow[]=$row;
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
    }
    
    //write section content colum names  to file
    foreach($outputObj->output as $row) {//rows
        foreach ($row as $colName=>$cell) {//column names
                $thisrow[]=renameField($colName);//make the keys into reable col names
        }
        fputcsv($outputBuffer, $thisrow);
        break;
    }
    $thisrow=array();
    
    //write content to file
    foreach($outputObj->output as $row) {//rows
        foreach ($row as $key=>$cell) {//cells
            if($key!="YearMonth")//skip a column
                $thisrow[]=$cell;
        }
        fputcsv($outputBuffer, $thisrow);
        $thisrow=array();
        
    }
    fclose($outputBuffer);
 }//end outputCSV
 
 function renameField($field){
        switch ($field) {
            case "year":
                return "Year";
                break;
            case "annlGrossRent":
                return "Gross Rents";                
                break;
            case "annlInsFee":
                return "Insurance";
                break;
            case "annlTaxFee":
                return "Taxes";
                break;
            case "annlMortPay":
                return "Mortgage";
                break;
            case "annlMngmtFee":
                return "Management Fee";;
                break;
            case "annlVacNCollecLossAcc":
                return "Vacancy/Col.Loss.Acc.";
                break;
            case "annlMainNCapImprvAcc":
                return "Main./Cap.Imprv.Acc.";
                break;
            case "cashFlow":
                return "Cash Flow";
                break;
            default:
                die("$field Field not forund!");
                break;
        }
 }
 
