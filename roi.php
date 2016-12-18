<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '2048M');

$response=new stdClass();
    $response->errFlag=false;;
    $response->errMsg="";
    
//check input
foreach ($_POST as $key => &$value) {
    switch ($key) {
        
        case "prprtyAddr":
            break;
        
        case "fxPrchPrcDlrAmt"://required >0
                $operandArr=array("Required","isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Purchase Price of Property", $value, $response,$operandArr,1, NULL);//max
            break;
        
        case "fxdDwnPmtPrct":  //between 0-99
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Down Payment %", $value, $response,$operandArr,0,99);
            break;
            
        case "termYrs"://required between 1-100
                $operandArr=array("Required","isNumeric","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Term", $value, $response,$operandArr,1,100);
            break;
        
        case "APR"://required //between 0-100
                $operandArr=array("Required","isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("APR", $value, $response,$operandArr,0,100);
            break;
        
        case "strtMnthlyRent"://required >=0
                $operandArr=array("Required","isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Rent", $value, $response,$operandArr,0, NULL);//max
            break;
        
        case "mnthlyMngmtPrct": //b/t 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Monthly Management Percentage", $value, $response,$operandArr,0,100);
            break;
            
        case "mnthlyMntnNCpImprPrct": //b/t 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Monthly Maintenance and Capitol Improvements Percentage", $value, $response,$operandArr,0,100);
            break;
            
        case "mnthlyVacColLossPrct": //b/t 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Monthly Vacancy and Collection Losses Percentage", $value, $response,$operandArr,0,100);
            break;
            
        case "fxdClsngCstDlrAmt": //b/t 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Closing Costs", $value, $response,$operandArr,0,null);
            break;
            
        case "annlTxsDlrAmt": 
                if(IsNullOrEmptyString($value)){ 
                    $value=0;//assume a default
                }
                $operandArr=array("isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Taxes", $value, $response,$operandArr,0, NULL);//max
            break;
        
        case "annlInsDlrAmt": 
                if(IsNullOrEmptyString($value)){ 
                    $value=0;//assume a default
                }
                $operandArr=array("isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Insurance", $value, $response,$operandArr,0, NULL);//max
            break;
            
        case "annlApprcPrct"://between 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//assume a default
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Appreciation", $value, $response,$operandArr,-100,100);
            break;
            
        case "yrsToSale"://no greater than term
                if(IsNullOrEmptyString($value)){
                    $value=$_POST["yrsToSale"]; //set defaut value of report length to term of loan
                }
                $operandArr=array("isNumeric","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Years Until Sale", $value, $response,$operandArr,1,$_POST["termYrs"]);
            break;
         case "mthsToCls"://.=0
                if(IsNullOrEmptyString($value)){
                    $value=1;//assume a default
                }
                $operandArr=array("isNumeric","ErrIfValLessthanMin");
                $response=checkInput("Months Before Close", $value, $response,$operandArr,0,NULL);
            break;
            
        default:
                $response->errFlag=true;
                $response->errMsg.="'$key' unknown parameter found<br />";
            break;
    }
    
}

//kill off if any errors in input found
if($response->errFlag==true){
    echo json_encode($response);
die();
}

//gather post data
$fxPrchPrcDlrAmt=$_POST["fxPrchPrcDlrAmt"];
$fxdDwnPmtPrct=$_POST["fxdDwnPmtPrct"]/100;
$termYrs=$_POST["termYrs"];
$APR=$_POST["APR"];
$fxdClsngCstDlrAmt=$_POST["fxdClsngCstDlrAmt"];//Vacancy and collection losses perct
$yrsToSale=(integer)$_POST["yrsToSale"]+1;//year zero shows starting balances

$strtMnthlyRent=$_POST["strtMnthlyRent"];//initial rent
$mnthlyMngmtPrct=$_POST["mnthlyMngmtPrct"]/100;//management perct
$mnthlyMntnNCpImprPrct=$_POST["mnthlyMntnNCpImprPrct"]/100;//Maintanence & capitol Improvements perct
$mnthlyVacColLossPrct=$_POST["mnthlyVacColLossPrct"]/100;//Vacancy and collection losses perct

$strtAnnlTxsDlrAmt=$_POST["annlTxsDlrAmt"];//starting annual tax amount
$strtAnnlInsDlrAmt=$_POST["annlInsDlrAmt"];//starting annual ins dollar amount
$annlApprcPrct=$_POST["annlApprcPrct"]/100;//annual appriciation on rent, taxes, insurance, property value

$data = array(
        'loan_amount' 	=> $fxPrchPrcDlrAmt-($fxdDwnPmtPrct*$fxPrchPrcDlrAmt),
        'term_years' 	=> $termYrs,
        'interest' 	=> $APR,
        'terms' 	=> 12//months in year
        );
        $amort=new Amortization($data);
        $amortArr=$amort::$results;
        $mortPaymnt=$amortArr["schedule"][0]["payment"];
//save the amort data in an object
$outputObj=new stdClass();
    $outputObj->post=$_POST;
    $outputObj->amortInputsArr=$amortArr["inputs"];
    $outputObj->amortSummaryArr=$amortArr["summary"];
    $outputObj->amortScheduleArr=$amortArr["schedule"];
    $outputObj->prprtyAddr=$_POST["prprtyAddr"];
    $outputObj->costAnlys=array();
    //$outputObj->annlCashFlow=array();
    //$outputObj->costAnlys->annlCashFlow=array();
    //$outputObj->output=array();
$annlCashFlowArr=array();
    
    //annual mort payment (static)
    $annlMortPay=$outputObj->amortScheduleArr[0]["payment"]*12;
    //annual appreciation perct (static)
    $annlApprcPrct;
    //initial annual gross rent (appreciated)
    $annlGrossRent=$strtMnthlyRent*12;
    //initial annual ins pay (appreciated)
    $annlInsFee=$strtAnnlInsDlrAmt;
    //initial annual tax payment (appreciated)
    $annlTaxFee=$strtAnnlTxsDlrAmt;
    //initial annual mamangment fee (increases taken care by increased rent)
    $annlMngmtFee=$annlGrossRent*$mnthlyMngmtPrct;
    //initial annual vacancy fee (increases taken care by increased rent)
    $annlVacNCollecLossAcc=$annlGrossRent*$mnthlyVacColLossPrct;
    //initial annual cap improv fee (increases taken care by increased rent)
    $annlMainNCapImprvAcc=$annlGrossRent*$mnthlyMntnNCpImprPrct;
    //initial cash outlay (downpayment and closing cost3)
    $initialCashOutlay=round($fxPrchPrcDlrAmt*$fxdDwnPmtPrct+$fxdClsngCstDlrAmt,2);//year 0
    
    $accNames=array(                
                    "annlGrossRent"=>array("appreciable",(real)$annlGrossRent, "add"),//initial value for year 1
                    "annlInsFee"=>array("appreciable",(real)$annlInsFee, "subtract"),//initial value for year 1
                    "annlTaxFee"=>array("appreciable",(real)$annlTaxFee, "subtract"),//initial value for year 1
                    "annlMortPay"=>array("static",(real)$annlMortPay, "subtract"),
                    "annlMngmtFee"=>array("rentPrct",(real)$annlMngmtFee, "subtract"),
                    "annlVacNCollecLossAcc"=>array("rentPrct",(real)$annlVacNCollecLossAcc, "subtract"),
                    "annlMainNCapImprvAcc"=>array("rentPrct",(real)$annlMainNCapImprvAcc, "subtract"),
                    //"annlCashFlow"=>array(),
    );
    //$annlCashFlowArr=array();
    foreach (range(0, $yrsToSale-1) as $year) {
        //set initial values to 0 except for colising costs and down payment
        if($year==0){
            foreach ($accNames as $key => $whoCares) {
                if($key!="annlCashFlow"){
                    $outputObj->costAnlys[$key][$year]=0;
                }else{
                    
                }
                $annlCashFlowArr[$year]=-1*$initialCashOutlay;
            }
            $propertyVal=$fxPrchPrcDlrAmt;
        }else{//save first year preappriation values
            $annlCashFlowArr[$year]=0;//initialize
            foreach ($accNames as $accKey1 => $arr1) {
                //record the initial values
                $outputObj->costAnlys[$accKey1][$year]=round($arr1[1],2);
                //add or subtract from the annual cash flow
                if($arr1[2]=="add"){
                    $annlCashFlowArr[$year]+=round($arr1[1],2);
                }
                else{
                    $annlCashFlowArr[$year]-=round($arr1[1],2);
                }
            }
            //print_r($accNames);
            /*
            [annlGrossRent] => Array
        (
            [0] => appreciable
            [1] => 25200
            [2] => add
             */

            //add appreciation
            foreach ($accNames as $accKey2 => &$arr2) {
                if($arr2[0]=="appreciable"){
                    $arr2[1]=round($arr2[1]+$annlApprcPrct*$arr2[1],2);
                }
            }
            //adjust rental perctages
            foreach ($accNames as $accKey3 => &$arr3) {
                if($arr3[0]=="rentPrct"){
                    $arr3[1]=round($arr3[1]+$annlApprcPrct*$arr3[1],2);
                }
            }
            $propertyVal=$propertyVal+$propertyVal*$annlApprcPrct;
        }
    }
$outputObj->annlCashFlowArr=$annlCashFlowArr;

$absValInitialCashOutlay=abs($initialCashOutlay);//absolute value


foreach ($annlCashFlowArr as $key7 => $value7) {
    if($key7!=0)//skip year 0 which is the cash outlay
        $outputObj->earningst+=$value7;
}

//ROI = ( (Earnings) - Initial Invested Amount) / Initial Invested Amount) ) × 100
$outputObj->roi=round(($outputObj->earningst-$absValInitialCashOutlay)/($absValInitialCashOutlay),4)*100;
$outputObj->equity="$".number_format(round($propertyVal-$amortArr["schedule"][(($yrsToSale-1)*12)-1]["balance"],2), 2);

/*
 *     foreach (range(1,$reportLength*12) as $monthIn) 
        foreach ($assocArrNamesarray as $value
        $thisRowObj->$value
        $outputObj->output[]=$thisRowObj;
 */
$thisRowObjSum=new stdClass();
foreach (range(0, $yrsToSale-1) as $year) {
    
    $thisRowObj=new stdClass();
    
    $thisRowObj->year=$year;
    $thisRowObj->annlGrossRent="$".number_format($outputObj->costAnlys["annlGrossRent"][$year],2);
    $thisRowObjSum->annlGrossRent+=$outputObj->costAnlys["annlGrossRent"][$year];//sum
    $thisRowObj->annlInsFee="$".number_format($outputObj->costAnlys["annlInsFee"][$year],2);
    $thisRowObjSum->annlInsFee+=$outputObj->costAnlys["annlInsFee"][$year];//sum
    $thisRowObj->annlTaxFee="$".number_format($outputObj->costAnlys["annlTaxFee"][$year],2);
    $thisRowObjSum->annlTaxFee+=$outputObj->costAnlys["annlTaxFee"][$year];//sum
    $thisRowObj->annlMortPay="$".number_format($outputObj->costAnlys["annlMortPay"][$year],2);
    $thisRowObjSum->annlMortPay+=$outputObj->costAnlys["annlMortPay"][$year];//sum
    $thisRowObj->annlMngmtFee="$".number_format($outputObj->costAnlys["annlMngmtFee"][$year],2);
    $thisRowObjSum->annlMngmtFee+=$outputObj->costAnlys["annlMngmtFee"][$year];//sum
    $thisRowObj->annlVacNCollecLossAcc="$".number_format($outputObj->costAnlys["annlVacNCollecLossAcc"][$year],2);
    $thisRowObjSum->annlVacNCollecLossAcc+=$outputObj->costAnlys["annlVacNCollecLossAcc"][$year];//sum
    $thisRowObj->annlMainNCapImprvAcc="$".number_format($outputObj->costAnlys["annlMainNCapImprvAcc"][$year],2);
    $thisRowObjSum->annlMainNCapImprvAcc+=$outputObj->costAnlys["annlMainNCapImprvAcc"][$year];//sum
    $thisRowObj->cashFlow="$".number_format($outputObj->annlCashFlowArr[$year],2);
    $thisRowObjSum->cashFlow+=$outputObj->annlCashFlowArr[$year];//sum
    
    $outputObj->output[]=$thisRowObj;
    
    
}

//echo "<pre>";
//print_r($thisRowObjSum);
//echo "</pre>";die();

//add totals as last row
    $thisRowObj=new stdClass();
    
    $thisRowObj->year="Totals";
    $thisRowObj->annlGrossRent="$".number_format($thisRowObjSum->annlGrossRent,2);
    $thisRowObj->annlInsFee="$".number_format($thisRowObjSum->annlInsFee,2);
    $thisRowObj->annlTaxFee="$".number_format($thisRowObjSum->annlTaxFee,2);
    $thisRowObj->annlMortPay="$".number_format($thisRowObjSum->annlMortPay,2);
    $thisRowObj->annlMngmtFee="$".number_format($thisRowObjSum->annlMngmtFee,2);
    $thisRowObj->annlVacNCollecLossAcc="$".number_format($thisRowObjSum->annlVacNCollecLossAcc,2);
    $thisRowObj->annlMainNCapImprvAcc="$".number_format($thisRowObjSum->annlMainNCapImprvAcc,2);
    $thisRowObj->cashFlow="$".number_format($thisRowObjSum->cashFlow,2);
    
    $outputObj->output[]=$thisRowObj;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['data']=$outputObj;


echo json_encode($outputObj);
die();


class Amortization {
        private $loan_amount;
        private $term_years;
        private $interest;
        private $terms;
        private $period;
        private $currency = "XXX";
        private $principal;
        private $balance;
        private $term_pay;
        public static $results="yo"; 
        public function __construct($data){
                if($this->validate($data)) {

                        $this->loan_amount 	= (float) $data['loan_amount'];
                        $this->term_years 	= (int) $data['term_years'];
                        $this->interest 	= (float) $data['interest'];
                        $this->terms 		= (int) $data['terms'];

                        $this->terms = ($this->terms == 0) ? 1 : $this->terms;
                        $this->period = $this->terms * $this->term_years;
                        $this->interest = ($this->interest/100) / $this->terms;
                        static::$results = array(
                                'inputs' => $data,
                                'summary' => $this->getSummary(),
                                'schedule' => $this->getSchedule(),
                                );
                }
        }

        private function validate($data) {
                $data_format = array(
                        'loan_amount' 	=> 0,
                        'term_years' 	=> 0,
                        'interest' 	=> 0,
                        'terms' 	=> 0
                        );
                $validate_data = array_diff_key($data_format,$data);

                if(empty($validate_data)) {
                        return true;
                }else{
                        echo "<div style='background-color:#ccc;padding:0.5em;'>";
                        echo '<p style="color:red;margin:0.5em 0em;font-weight:bold;background-color:#fff;padding:0.2em;">Missing Values</p>';
                        foreach ($validate_data as $key => $value) {
                                echo ":: Value <b>$key</b> is missing.<br>";
                        }
                        echo "</div>";
                        return false;
                }
        }
        private function calculate($i){
                $deno = 1 - 1 / pow((1+ $this->interest),$this->period);
                if($deno==0)$deno=.0001;
                $this->term_pay = ($this->loan_amount * $this->interest) / $deno;
                $interest = $this->loan_amount * $this->interest;
                $this->principal = $this->term_pay - $interest;
                $this->balance = $this->loan_amount - $this->principal;
                return array (//"$".number_format(round(($amortArr["schedule"][$monthIn-1]["balance"]), 2),2);
                        'paymentNo'     => $i,
                        'payment' 	=> round($this->term_pay,2),
                        'interest' 	=> "$".number_format(round(($interest), 2),2),
                        'principal' 	=> "$".number_format(round(($this->principal), 2),2),
                        'balance' 	=> round($this->balance,2),
                        );
        }
        public function getSummary(){
                $this->calculate(0);
                $total_pay = $this->term_pay *  $this->period;
                $total_interest = $total_pay - $this->loan_amount;
                return array (
                        'total_pay' => $total_pay,
                        'total_interest' => $total_interest,
                        );
        }
        public function getSchedule(){
                $shedule = array();
                $i=1;
                while  ($this->balance >= 0) {
                        array_push($shedule, $this->calculate($i));
                        $this->loan_amount = $this->balance;
                        $this->period--;
                        $i++;
                }
                return $shedule;
        }
}
    
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

function checkInput($name, $value, $response,$operandArr,$min, $max){
    
    foreach ($operandArr as $operand) {

        switch ($operand) {
            case "ErrIfValLessthanOrEqtoMin":
                    if($value<=$min){
                        $response->errFlag=true;
                        $response->errMsg.="'$name' must be greater than or equal to '$min'<br />";
                    }
                break;
            case "ErrIfValLessthanMin":
                    if($value<$min){
                        $response->errFlag=true;
                        $response->errMsg.="'$name' must be greater than or equal to '$min'<br />";
                    }
                break;
            case "ErrIfValGreaterthanMaxOrLesthanMin":
                    if($value>$max || $value<$min ){
                        $response->errFlag=true;
                        $response->errMsg.="'$name' must be greater than '$min' and less than '$max'<br />";
                    }
                break;
            case "Required":
                    if(IsNullOrEmptyString($value)){
                        $response->errFlag=true;
                        $response->errMsg.="'$name' required<br />";
                    }
                break;
            case "isNumeric":
                    if((!is_numeric($value) || floor( $value ) != $value)){//no decimals
                        $response->errFlag=true;
                        $response->errMsg.="'$name' can must be interger values<br />";
                    }
                break;
            case "isNumericFloat":
                    if(!is_numeric($value)){//allow decimals
                        $response->errFlag=true;
                        $response->errMsg.="'$name' must be an interger or decimal value<br />";
                    }
                break;
            default:
                    $response->errFlag=true;
                    $response->errMsg.="Unknown operand '$operand' submitted<br />";
                break;
        }//end switch
    }//end for each operands
    return $response;
}
    
die();


