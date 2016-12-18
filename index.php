<?php
//pre-populate values for testing or use placeholders
if(isset($_GET["pp"]) && $_GET["pp"]==1) {
    define('SETVAL', TRUE);
}
else {
    define('SETVAL', FALSE);
}

function setVal($val){
  if(SETVAL) return "value=\"$val\"";
    else 
        return "placeholder=\"$val\"";  
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/images/fave.ico">
        <!--<script type='text/javascript' src='/twc_includes/js/jquery-1.8.3.min.js'></script>-->
        
        <link rel="stylesheet" href="bootstrap.min.css">
        <script src="/twc_includes/js/jquery-3.1.1.min.js"></script>
        <!--bootstrap 3.3.6 won't work-->
        <script src="/twc_includes/js/bootstrap-3.3.7 2/dist/js/bootstrap.min.js"></script>
        
        <script type='text/javascript' src='js.js'></script>
        <link type="text/css" rel="stylesheet" href="/twc_includes/js/jsgrid-1.5.2/jsgrid.min.css" />
        <link type="text/css" rel="stylesheet" href="/twc_includes/js/jsgrid-1.5.2/jsgrid-theme.min.css" />
        <script type="text/javascript" src="/twc_includes/js/jsgrid-1.5.2/jsgrid.min.js"></script>
        <title>RE12C The Real Estate Investment Tool</title>
        <!-- Bootstrap core CSS -->
        <!--<link rel="stylesheet" href="bootstrap.min.css">-->
        <!-- Custom styles for this template -->
        <link href="/narrow.css" rel="stylesheet">
        <style>
            input:focus::-webkit-input-value 
                {
                    color: transparent;
                }
        </style>
    </head>
    <body>
        <div class="container">
  <!--heading--> 
            <div class="header clearfix">
                <nav>
                    <ul class="nav nav-pills pull-right">
                        <li role="presentation"><a href="../../index.html">Examples</a></li>
                        <li role="presentation"><a href="skills.html">Tech Skills</a></li>
                        <li role="presentation"><a href="contact/contact.html">Contact</a></li>
                    </ul>
                </nav>
                <h3 class="text-muted">RE12C</h3><small>The Real Estate Investment Tool</small>
            </div>

            <div class="col-lg-12">  

                <form id="formwhatever" action="" method="post" name="form" enctype="multipart/form-data">                  

                    <p>RE12C The Real Estate Investment Tool helps investors gauge potential returns on 
                        investment (ROI) when considering purchasing rental properties. The tool inputs property value, 
                        mortgage parameters, rental expenses, and anticipated real estate appreciation 
                        rates and outputs a schedule of cash flow with ROI, and equity over a selectable period. 
                        It also generates an amortization schedule.
                    </p>
                    <div class="row">
                        <div class="panel" >
                            <div class="col-lg-4 panel-body" id="errP"><kbd>&nbsp;Numbers Only&nbsp;</kbd>&nbsp;
                            </div>
                            <div class="col-lg-8 panel-body"><span class="text-danger">&nbsp;*&nbsp;</span>Indicates Required Field
                            </div>
    <!--error output-->       
                            <div class="panel-body" id="errDiv" >
                                <span id="errOutput" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
    <!--Property address-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Property Address</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text" id="prprtyAddr" <?php echo setVal("203 Poplar St");?>>
                        </div>
    <!--price-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Purchase Price of Property<span class="text-danger"> *</span></h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text" id="fxPrchPrcDlrAmt"  <?php echo setVal("335000");?> >
                        </div>
    <!--percent down-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Down Payment %</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="fxdDwnPmtPrct" <?php echo setVal("20");?>>
                        </div>
    <!--term-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Term<span class="text-danger"> *</span></h3>
                            <small>The term in years of loan</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="termYrs" <?php echo setVal("30");?>>
                        </div>
    <!--interest rate-->
                         <div class="panel-heading">
                            <h3 class="panel-title">APR<span class="text-danger"> *</span></h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="APR" <?php echo setVal("4.1");?>>
                        </div>
    <!--initial rent-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Rent (monthly)<span class="text-danger"> *</span></h3>
                            <small>Subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="strtMnthlyRent" <?php echo setVal("2100");?>>
                        </div>
    <!--Monthly Management Percentage-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Monthly Management Percentage</h3>
                            <small>A monthly percentage of the gross rents</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="mnthlyMngmtPrct" <?php echo setVal("4");?>>
                        </div>
    <!--Monthly Maintenance and Capitol Improvements Percentage-->
                    <div class="panel-heading">
                        <h3 class="panel-title">Monthly Maintenance and Capitol Improvements Percentage</h3>
                        <small>A monthly percentage set aside from gross rents</small>
                    </div>
                    <div class="panel-body">
                        <input class="form-control" type="text"  id="mnthlyMntnNCpImprPrct" <?php echo setVal("6");?>>
                    </div>
    <!--Monthly Vacancy/Collection Losses Percentage-->
                    <div class="panel-heading">
                        <h3 class="panel-title">Monthly Vacancy and Collection Losses Percentage</h3>
                        <small>An anticipated monthly percentage lost deducted from gross rents</small>
                    </div>
                    <div class="panel-body">
                        <input class="form-control" type="text"  id="mnthlyVacColLossPrct" <?php echo setVal("3");?>>
                    </div>
    <!--Closing Costs-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Closing Costs</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="fxdClsngCstDlrAmt" <?php echo setVal("9000");?>>
                        </div>
    <!--Yearly Insurance-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Insurance</h3>
                            <small>Annual dollar amount subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="annlInsDlrAmt" <?php echo setVal("420");?>>
                        </div>
    <!--Yearly Taxes-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Taxes</h3>
                            <small>Annual dollar amount subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="annlTxsDlrAmt" <?php echo setVal("4654");?>>
                        </div>
    <!--Appreciation-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Property Appreciation Rate</h3>
                            <small>Estimated average annual appreciation rate for the property</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="annlApprcPrct" <?php echo setVal("5.5");?>>
                        </div>
    <!--Years Until Sale-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Report Time Frame</h3>
                            <small>The period over which to calculate ROI</small>
                        </div>
                            
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="yrsToSale" <?php echo setVal("10");?>>
                        </div>
<!--    Months to Closing
                        <div class="panel-heading">
                            <h3 class="panel-title">Months Before Closing Loan</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="mthsToCls" <?php //echo setVal("1");?>>
                        </div>-->
    <!--button row-->                        
                        <div class="row">
        <!--run-->                 
                            <div class="col-lg-3">
                                <div class="panel-body">
                                     <input type="submit" class="btn btn-md btn-primary" value="Run" id="submit">   
                                </div>
                            </div>

        <!--view Amortization--> 
        <span id="ready">
                    <!--view amort sched-->      
                            <div class="col-lg-3">
                                <div class="panel-body">
                                    <!--Trigger the modal with a button--> 
                                    <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#myModalAmort">View Amortization</button>
                                </div>   
                            </div>
        <!--view Appreciation--> 
                            <div class="col-lg-3" >
                                <div class="panel-body" >
                                    <!-- Trigger the modal with a button -->
                                    <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#myModalAppr">View ROI</button>
                                </div>   
                            </div>

        </span>
        <!--    empty-->
                            <div class="col-lg-3">
                                <!--empty-->
                            </div>
                        </div>
                   </div> <!-- end  div class="panel panel-info"-->
                </form>
    <!--output Modal ROI-->
                <div class="modal fade" id="myModalAppr" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"id="title">Return on Investment for </h4>
                      </div>
                        <div class="modal-body">
                            <div id="notes">
                            </div>
                            <div id="jsGridAppr" margin-bottom:300px">
                            </div>
                            
                        </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>

                  </div>
                </div>
     <!--output Modal AMort -->
                <div class="modal fade" id="myModalAmort" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Amortization Schedule</h4>
                      </div>
                        <div class="modal-body">
                                <div id="notes">
                                </div>
                                <div id="jsGridAmort">
                                </div>
                        </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>

                  </div>
                </div>
                
    <!--output-->     

<!--                <div class="panel panel-default" id="outputdiv" >
                    <div class="panel-body">
                        <span id="output" class="text-danger"></span>
                    </div>
                </div>-->
    <!--footer-->   
                <footer class="footer">
                       <p>
                          <a href="https://github.com/pwingard/realestateInvestmentTool">Git</a>&nbsp;&middot;&nbsp;
                          <a href="https://www.linkedin.com/in/pwingard">LinkedIn</a>
                      </p>
                      <p>&copy; <span id="year"></span> | Peter Wingard</p>

                      <script type='text/javascript' >
                          var today = new Date();
                          var year = today.getFullYear();
                          $('#year').text(year);
                      </script>
                </footer>
            </div>
        </div> <!-- /container -->
    </body>
</html>
