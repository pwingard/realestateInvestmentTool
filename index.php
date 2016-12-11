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
        <title>Real Estate Investment Tool</title>
        <!-- Bootstrap core CSS -->
        <!--<link rel="stylesheet" href="bootstrap.min.css">-->
        <!-- Custom styles for this template -->
        <link href="/narrow.css" rel="stylesheet">
        <style>
            input:focus::-webkit-input-placeholder 
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
                <h3 class="text-muted">REIT</h3><small>Real Estate Investment Tool</small>
            </div>

            <div class="col-lg-12">  

                <form id="formwhatever" action="" method="post" name="form" enctype="multipart/form-data">                  

                    <p>The Real Estate Investment Tool is for helping real estate investors gauge probable 
                        investment values on rental properties. Tool inputs property value, mortgage parameters, 
                        and anticipated appreciation rates and outputs an amortization-like schedule showing 
                        return on investment over time.
                    </p>

                    <p id="errP">&nbsp;Numbers Only
                            <kbd> No letters, symbols, or commas </kbd>&nbsp;<span class="text-danger">* </span>Required
                    </p>
    <!--error output-->                       
                    <div class="panel panel-default" id="errDiv" >
                        <div class="panel-body">
                            <span id="errOutput" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="panel panel-info">
    <!--Property address-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Property Address<span class="text-danger"> *</span></h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text" id="address" placeholder="123 Easy St">
                        </div>
    <!--price-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Full Price of Unit<span class="text-danger"> *</span></h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text" id="purchaseprice"  placeholder="335000" >
                        </div>
    <!--percent down-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Down Payment %</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="percentdown" placeholder="20">
                        </div>
    <!--term-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Term<span class="text-danger"> *</span></h3>
                            <small>The term in years of loan</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="term" placeholder="30">
                        </div>
    <!--interest rate-->
                         <div class="panel-heading">
                            <h3 class="panel-title">APR<span class="text-danger"> *</span></h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="interestRate" placeholder="4.1">
                        </div>
    <!--initial rent-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Rent (monthly)<span class="text-danger"> *</span></h3>
                            <small>Subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="rent" placeholder="2100">
                        </div>
    <!--Monthly Management/Maintenance Percentage-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Management & Maintenance</h3>
                            <small>A monthly percentage of the gross rents and subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="managementpercentage" placeholder="8">
                        </div>
    <!--Yearly Insurance-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Insurance</h3>
                            <small>Annual dollar amount subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="insurance" placeholder="420">
                        </div>
    <!--Yearly Taxes-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Taxes</h3>
                            <small>Annual dollar amount subject to appreciation</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="taxes" placeholder="4654">
                        </div>
    <!--Appreciation-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Appreciation</h3>
                            <small>Annual percentage rate</small>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="appreciation" placeholder="5.5">
                        </div>
    <!--Report Length (Years)-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Report Length (Years)</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="reportlength" placeholder="6">
                        </div>
    <!--Beginning Offset (Months)-->
                        <div class="panel-heading">
                            <h3 class="panel-title">Beginning Offset (Months)</h3>
                        </div>
                        <div class="panel-body">
                            <input class="form-control" type="text"  id="offset" placeholder="0">
                        </div>
    <!--button row-->                        
                        <div class="row">
        <!--run-->                 
                            <div class="col-lg-3">
                                <div class="panel-body">
                                     <input type="submit" class="btn btn-md btn-primary" value="Run" id="submit">   
                                </div>
                            </div>
        <!--view Projection--> 
        <span id="ready"">
                            <div class="col-lg-3" >
                                <div class="panel-body" >
                                    <!-- Trigger the modal with a button -->
                                    <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#myModal">View Projections</button>
                                </div>   
                            </div>
        <!--view amort sched-->      
                            <div class="col-lg-3">
                                <div class="panel-body">
                                    <!--Trigger the modal with a button--> 
                                    <!--<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#myModal">View Amortization</button>-->
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
    <!--output Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Projected Investment</h4>
                      </div>
                        <div class="modal-body">
                                <div id="notes">
                                </div>
                                <div id="jsGrid">
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
