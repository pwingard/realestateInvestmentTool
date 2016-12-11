    $(document).ready(function(){
        
        $('#errDiv').hide();
        $('#ready').hide();
        $("#submit").show('fast');
        $("#submit").click(function () {
            $('#ready').hide();
            var address=$('#address').val();
            var purchaseprice=$('#purchaseprice').val();
            var percentdown=$('#percentdown').val();
            var term=$('#term').val();
            var interestRate=$('#interestRate').val();
            var rent=$('#rent').val();
            var managementpercentage=$('#managementpercentage').val();
            var taxes=$('#taxes').val();
            var insurance=$('#insurance').val();
            var appreciation=$('#appreciation').val();
            var reportlength=$('#reportlength').val();
            var offset=$('#offset').val();
             
            $.ajax({
                url: "rentals_Dec.php",
                type: "POST",
                dataType:'json',
                
                data: {
                    address: address, 
                    purchaseprice: purchaseprice, 
                    percentdown: percentdown,
                    term: term,
                    interestRate: interestRate,
                    rent: rent,
                    managementpercentage: managementpercentage,
                    insurance: insurance,
                    taxes: taxes,
                    appreciation: appreciation,
                    reportlength: reportlength,
                    offset: offset
                },
                //async: false,//keep loading gif from showing up
                success: function (data) {
                    console.log(data.errFlag);
                    if(data.errFlag===true){
                        $('#errDiv').show();
                        $('html,body').animate({
                            scrollTop: $("#errP").offset().top
                         });
                        $('#errOutput').html(data.errMsg);
                    }else{
                        console.log(data);
                        var loanAmt=commaSeparateNumber(data.amortInputsArr.loan_amount.toFixed(2));
                        var totIntPaid=commaSeparateNumber(data.amortSummaryArr.total_interest.toFixed(2));
                        var totPaid=commaSeparateNumber(data.amortSummaryArr.total_pay.toFixed(2));
                        
                        $('#ready').show();
                        $('#notes').html(
                                            "Loan Amt. $"+loanAmt 
                                            +" &bull; Interst Rate " +data.amortInputsArr.interest
                                            +"% &bull; Term  " +data.amortInputsArr.term_years
                                            +" years &bull; Mort. Payment $" +data.amortScheduleArr[0].payment
                                            +"<br />Interest Total $" +totIntPaid
                                            +" &bull; Total Paid $" +totPaid
                                            +"<br /><a href=\"download.php\">Download</a> as CSV"
                                        );
                        makeGrid(data.output);
                        makeGrid(data.amortScheduleArr);
                        /*
                         * BalOwed:"$267,620.70"
                            Equity:"$67,379.30"
                            HouseVal :"$335,000.00"
                            MortTaxIns:"$1,717.80"
                            NetInc:"$214.20"
                            TotalInvested:"$66,785.80"
                            YearMonth:"0_1"
                            date:"Feb 2017"
                         */
                    }
                },
                error: function () {
                    alert( "Posting failed." );
                    $('#message').html("");
                },
            });
        
        return false;
        });
    });
    
function makeGrid(data){
    if(data[0].BalOwed) {//appreciation data, otherwise amortization data
        //console.log("yo BalOwed");
        $("#jsGridAppr").jsGrid({
            width: "100%",
            height: "400px",
            inserting: false,
            editing: false,
            sorting: false,
            paging: false,

            data: data,
            /*
             * (appreciation schedule)
             * BalOwed:"$267,620.70"
            Equity:"$67,379.30"
            HouseVal :"$335,000.00"
            MortTaxIns:"$1,717.80"
            NetInc:"$214.20"
            TotalInvested:"$66,785.80"
            YearMonth:"0_1"
            date:"Feb 2017"
            */

            fields: [
                    { title: "Date", name: "date", type: "text", width: 85, validate: "required" },
                    { title: "Value", name: "HouseVal", type: "text", width: 100, validate: "required" },
                    { title: "Owed", name: "BalOwed", type: "text", width: 100, validate: "required" },
                    { title: "Equity", name: "Equity", type: "text", width: 100, validate: "required" },
                    { title: "Rents", name: "MortTaxIns", type: "text", width: 100, validate: "required" },
                    { title: "Income", name: "NetInc", type: "text", width: 100, validate: "required" },
                    { title: "Investment", name: "TotalInvested", type: "text", width: 100, validate: "required" },
                ]
        });
    }else{
        $("#jsGridAmort").jsGrid({
            width: "100%",
            height: "400px",
            inserting: false,
            editing: false,
            sorting: false,
            paging: false,

            data: data,
            /*
                (amortization sched)
                Array[360]
                Object
                balance:267620.695032
                interest:915.666666667
                payment:1294.97163455
                principal:379.304967888
            */

            fields: [
                    { title: "No.", name: "paymentNo", type: "text", width: 50, validate: "required" },
                    { title: "Balance", name: "balance", type: "text", width: 135, validate: "required" },
                    { title: "Interest", name: "interest", type: "text", width: 135, validate: "required" },
                    { title: "Payment", name: "payment", type: "text", width: 135, validate: "required" },
                    { title: "Principle", name: "principal", type: "text", width: 135, validate: "required" },
//                    { title: "Income", name: "NetInc", type: "text", width: 100, validate: "required" },
//                    { title: "Investment", name: "TotalInvested", type: "text", width: 100, validate: "required" },
                ]
        });
    }
}
    
function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
}

 


