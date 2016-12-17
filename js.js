    $(document).ready(function(){
        
        $('#errDiv').hide();
        $('#ready').hide();
        //hides the report buttons if the inputs are changed
        $('input').change(function(){
                if ($(this).val()){
                    $('#ready').hide();
                }
              });
        $("#submit").show('fast');
        $("#submit").click(function () {
            $('#ready').hide();
            var prprtyAddr=$('#prprtyAddr').val();
            var fxPrchPrcDlrAmt=$('#fxPrchPrcDlrAmt').val();
            var fxdDwnPmtPrct=$('#fxdDwnPmtPrct').val();
            var termYrs=$('#termYrs').val();
            var APR=$('#APR').val();
            var strtMnthlyRent=$('#strtMnthlyRent').val();
            var mnthlyMngmtPrct=$('#mnthlyMngmtPrct').val();
            var mnthlyMntnNCpImprPrct=$('#mnthlyMntnNCpImprPrct').val();
            var mnthlyVacColLossPrct=$('#mnthlyVacColLossPrct').val();
            var fxdClsngCstDlrAmt=$('#fxdClsngCstDlrAmt').val();
            var annlTxsDlrAmt=$('#annlTxsDlrAmt').val();
            var annlInsDlrAmt=$('#annlInsDlrAmt').val();
            var annlApprcPrct=$('#annlApprcPrct').val();
            var yrsToSale=$('#yrsToSale').val();
            var mthsToCls=$('#mthsToCls').val();
             
            $.ajax({
                url: "roi.php",
                type: "POST",
                dataType:'json',
                
                data: {
                    prprtyAddr: prprtyAddr, 
                    fxPrchPrcDlrAmt: fxPrchPrcDlrAmt, 
                    fxdDwnPmtPrct: fxdDwnPmtPrct,
                    termYrs: termYrs,
                    APR: APR,
                    strtMnthlyRent: strtMnthlyRent,
                    mnthlyMngmtPrct: mnthlyMngmtPrct,
                    mnthlyMntnNCpImprPrct: mnthlyMntnNCpImprPrct,
                    mnthlyVacColLossPrct: mnthlyVacColLossPrct,
                    fxdClsngCstDlrAmt: fxdClsngCstDlrAmt,
                    annlInsDlrAmt: annlInsDlrAmt,
                    annlTxsDlrAmt: annlTxsDlrAmt,
                    annlApprcPrct: annlApprcPrct,
                    yrsToSale: yrsToSale,
                    mthsToCls: mthsToCls
                },
                //async: false,//keep loading gif from showing up
                success: function (data) {
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
                        
                        if(data.roi<0) {
                            var thisClass="class=\"text-danger\"";
                        }else {
                           thisClass="class=\"text-success\""; 
                        }
                        
                        $('#ready').show();
                        $('#title').append("'"+data.post.prprtyAddr+"'");
                        $('#notes').html( "<span "+thisClass+"> "+data.roi+"%</span> ROI over "+data.post.yrsToSale+" years"
//                                            "Prch. Price $"+data.post.fxPrchPrcDlrAmt 
//                                            +" &bull; Loan Amt. $"+loanAmt 
//                                            +" &bull; Interst Rate " +data.amortInputsArr.interest
//                                            +"% &bull; Term  " +data.amortInputsArr.term_years
//                                            +" years &bull; Mort. Payment $" +data.amortScheduleArr[0].payment
//                                            +"<br />Interest Total $" +totIntPaid
//                                            +" &bull; Total Paid $" +totPaid
                                            +"<span style=\"float:right;margin-right: 5px;\">Download as <a href=\"download.php\">CSV</a></span>"
                                            +"<br /><small style=\"float:right;margin-right: 5px;\">&copy; "+year+" Pete Wingard</small>"
                                        );
                        makeGrid(data.output, data.post);
                        makeGrid(data.amortScheduleArr, data.post);
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
    
function makeGrid(data, post){

    if(data[0].annlGrossRent) {//roi data, otherwise amortization data
        $("#jsGridAppr").jsGrid({
            width: "100%",
            height: "auto",
            inserting: false,
            editing: false,
            sorting: false,
            paging: false,

            data: data,
            /*
                annlGrossRent:Array[6]√
                annlInsFee:Array[6]√
                annlMainNCapImprvAcc:Array[6]√
                annlMngmtFee:Array[6]√
                annlMortPay:Array[6]
                annlTaxFee:Array[6]√
                annlVacNCollecLossAcc:Array[6]√
                cashFlow:Array[6]
                roi:-72.61
                year:Array[6]√
            */

            fields: [
                    { title: "Year", name: "year", type: "text", width: 55, validate: "required" },
                    { title: "Rents", name: "annlGrossRent", type: "text", width: 100, validate: "required" },
                    { title: "Mortgage", name: "annlMortPay", type: "text", width: 100, validate: "required" },
                    { title: "Taxes", name: "annlTaxFee", type: "text", width: 100, validate: "required" },
                    { title: "Insurance", name: "annlInsFee", type: "text", width: 100, validate: "required" },
                    { title: "Management "+ post.mnthlyMngmtPrct+"%", name: "annlMngmtFee", type: "text", width: 150, validate: "required" },
                    { title: "Main/CapImpr "+ post.mnthlyMntnNCpImprPrct+"%", name: "annlMainNCapImprvAcc", type: "text", width: 160, validate: "required" },
                    { title: "Vac/ColLosses "+ post.mnthlyVacColLossPrct+"%", name: "annlVacNCollecLossAcc", type: "text", width: 160, validate: "required" },
                    { title: "Cash Flow", name: "cashFlow", type: "text", width: 100, validate: "required" },
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

 


