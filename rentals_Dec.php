<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '2048M');

//print_r($_POST);
/*
(
    [address] => 123 Easy St.
    [purchaseprice] => 335000
    [percentdown] => 20
    [term] => 30
    [interestRate] => 4.1
    [rent] => 2100
    [managementpercentage] => 8
    [insurance] => 420
    [taxes] => 4654
    [appreciation] => 5.5
    [reportlength] => 6
    [offset] => 
)
 */

$response=new stdClass();
    $response->errFlag=false;;
    $response->errMsg="";
    
//check input
foreach ($_POST as $key => &$value) {
    switch ($key) {
        
        case "address"://required
                $operandArr=array("Required");
                $response=checkInput("Property Address ", $value, $response,$operandArr,NULL, NULL);//max
            break;
        
        case "purchaseprice"://required >0
                $operandArr=array("Required","isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Full Price of Unit", $value, $response,$operandArr,1, NULL);//max
            break;
        
        case "percentdown":  //between 0-99
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Down Payment %", $value, $response,$operandArr,0,99);
            break;
            
        case "term"://required between 1-100
                $operandArr=array("Required","isNumeric","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Term", $value, $response,$operandArr,1,100);
            break;
        
        case "interestRate"://required //between 0-100
                $operandArr=array("Required","isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("APR", $value, $response,$operandArr,0,100);
            break;
        
        case "rent"://required >=0
                $operandArr=array("Required","isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Rent", $value, $response,$operandArr,0, NULL);//max
            break;
        
        case "managementpercentage": //b/t 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//set default value if empty
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Management & Maintenance", $value, $response,$operandArr,0,100);
            break;
            
        case "taxes": 
                if(IsNullOrEmptyString($value)){ 
                    $value=0;//assume a default
                }
                $operandArr=array("isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Taxes", $value, $response,$operandArr,0, NULL);//max
            break;
        
        case "insurance": 
                if(IsNullOrEmptyString($value)){ 
                    $value=0;//assume a default
                }
                $operandArr=array("isNumericFloat","ErrIfValLessthanMin");
                $response=checkInput("Insurance", $value, $response,$operandArr,0, NULL);//max
            break;
            
        case "appreciation"://between 0-100
                if(IsNullOrEmptyString($value)){
                    $value=0;//assume a default
                }
                $operandArr=array("isNumericFloat","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Appreciation", $value, $response,$operandArr,-100,100);
            break;
            
        case "reportlength"://no greater than term
                if(IsNullOrEmptyString($value)){
                    $value=$_POST["term"]; //set defaut value of report length to term of loan
                }
                $operandArr=array("isNumeric","ErrIfValGreaterthanMaxOrLesthanMin");
                $response=checkInput("Report Length", $value, $response,$operandArr,1,$_POST["term"]);
            break;
         case "offset"://.=0
                if(IsNullOrEmptyString($value)){
                    $value=0;//assume a default
                }
                $operandArr=array("isNumeric","ErrIfValLessthanMin");
                $response=checkInput("Beginning Offseth", $value, $response,$operandArr,0,NULL);
            break;
            
        default:
                $response->errFlag=true;
                $response->errMsg.="'$name' unknown parameter found<br />";
            break;
    }
    
}

//kill off if any errors in input found
if($response->errFlag==true){
    echo json_encode($response);
die();
}

$priceOfHouse=$_POST["purchaseprice"];
$pertDwn=$_POST["percentdown"]/100;
$term=$_POST["term"];
$interestRate=$_POST["interestRate"];

$data = array(
        'loan_amount' 	=> $priceOfHouse-($pertDwn*$priceOfHouse),
        'term_years' 	=> $term,
        'interest' 	=> $interestRate,
        'terms' 	=> 12//months in year
        );
        $amort=new Amortization($data);
        $amortArr=$amort::$results;

$outputObj=new stdClass();
    $outputObj->amortInputsArr=$amortArr["inputs"];
    $outputObj->amortSummaryArr=$amortArr["summary"];
    $outputObj->amortScheduleArr=$amortArr["schedule"];
    $outputObj->address=$_POST["address"];
    $outputObj->output=array();
    
        //print_r($amortArr["schedule"][0]["summary"]);
        /*
         *     [inputs] => Array
        (
            [loan_amount] => 264000
            [term_years] => 30
            [interest] => 4
            [terms] => 12
        )

    [summary] => Array
        (
            [total_pay] => 453735.49681
            [total_interest] => 189735.49681
        )

    [schedule] => Array
        (
            [0] => Array
                (
                    [payment] => 1260.37638003
                    [interest] => 880
                    [principal] => 380.376380029
                    [balance] => 263619.62362
                )

         */

$rent=$_POST["rent"];//initial rent
$managementpercentage=$_POST["managementpercentage"]/100;//initial rent
$percentdown=$_POST["percentdown"]/100;//initial rent
$taxPay=$_POST["taxes"]/12;//4654/12;
$insPay=$_POST["insurance"]/12;//420/12;

$netRent=(1-$managementpercentage)*$rent;//.92
$year=0;

$TotInvest=$moneyDown=$priceOfHouse*($percentdown);
$monthlyAppreciation=$_POST["appreciation"]/100/12;//0.00457;//7%apr 00583, 6%.005, 5% .00417
$reportLength=$_POST["reportlength"];

$housePayment=$taxPay+$insPay+$amortArr["schedule"][0]["payment"];
$beginingMonthOffset=1+$_POST["offset"];

$valueOfHouse=$priceOfHouse;
$yearlast=0;

$assocArrNamesarray=array('date', 'YearMonth', 'MortTaxIns', 'NetInc', 'TotalInvested', 'HouseVal', 'RentNet',
        'BalOwed', 'Equity');

foreach (range(1,$reportLength*12) as $monthIn) {
    
    $thisRowObj=new stdClass();
    
    $year=floor(($monthIn-1)/12);
    $netIncome=$netRent-$housePayment;
    $TotInvest=$TotInvest-$netIncome;
    
    $thisMonth=$monthIn+$beginingMonthOffset;
    
   //[{ "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false }, 
    foreach ($assocArrNamesarray as $value) {
        switch ($value) {
            case "date":
                $thisRowObj->$value=date('M Y', strtotime("+$thisMonth months"));
                break;
            case "YearMonth":
                $thisRowObj->$value=$year."_".$monthIn;
                break;
            case "MortTaxIns":
                $thisRowObj->$value="$".number_format(round($housePayment, 2),2);
                break;
            case "NetInc":
                $thisRowObj->$value="$".number_format(round($netIncome, 2),2);
                break;
            case "TotalInvested":
                $thisRowObj->$value="$".number_format(round($TotInvest, 2),2);
                break;
            case "HouseVal":
                $thisRowObj->$value="$".number_format(round($valueOfHouse, 2),2);
                break;
            case "Rent":
                $thisRowObj->$value="$".number_format(round($netRent, 2),2);
                break;
            case "BalOwed":
                //echo $amortArr["schedule"][$monthIn-1]["balance"]." ";
                 $thisRowObj->$value="$".number_format(round(($amortArr["schedule"][$monthIn-1]["balance"]), 2),2);
                //echo $thisRowObj->$value;echo "\n";
                break;
            case "Equity":
                $thisRowObj->$value="$".number_format(round(($valueOfHouse-$amortArr["schedule"][$monthIn-1]["balance"]), 2),2);
                break;
            
            default:
                break;
        }
    }
    $outputObj->output[]=$thisRowObj;
    
    $yearlast=$year;
       
    $netRent=$netRent+$netRent*$monthlyAppreciation;
    $valueOfHouse=$valueOfHouse+$valueOfHouse*$monthlyAppreciation;
    $taxPay=$taxPay+$taxPay*$monthlyAppreciation;
    $insPay=$insPay+$insPay*$monthlyAppreciation;
    $housePayment=$taxPay+$insPay+$amortArr["schedule"][$monthIn-1]["payment"];
    
}
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
                        'payment' 	=> "$".number_format(round(($this->term_pay), 2),2),
                        'interest' 	=> "$".number_format(round(($interest), 2),2),
                        'principal' 	=> "$".number_format(round(($this->principal), 2),2),
                        'balance' 	=> "$".number_format(round(($this->balance), 2),2),
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
                        $response->errMsg.="'$name' can must be interger values only<br />";
                    }
                break;
            case "isNumericFloat":
                    if(!is_numeric($value)){//allow decimals
                        $response->errFlag=true;
                        $response->errMsg.="'$name' must be an interger or decimal value only<br />";
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


