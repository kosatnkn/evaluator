<?php

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

$UserLogic = new UserLogic();

$arrField = $UserLogic->getField();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up a Student</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
<!-- Header ------------------------------------------------------------------------------------------------------------------- -->
<div class = "navbar navbar-default navbar-fixed-top">
    <div class = "container">
        <span class= "navbar-brand pull-left"><span class="custom-badge-std"><span class="glyphicon glyphicon-fire"></span></span>&nbsp;&nbsp;Sign Up a Student</span>
        <span class = "navbar-brand pull-right">SuMMit</span>
    </div>
</div>
<!-- /Header ------------------------------------------------------------------------------------------------------------------ -->

<!-- Content ------------------------------------------------------------------------------------------------------------------ -->

<div class = "container container-main">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            
            <form method="post" action="signupstd.link.php">
                
                <div class="row">
                <div class="form-group">
                    <h4 class="page-header custom-page-header"><strong>Basic Details</strong></h4>
                </div>
                </div>

                <div class="row">
                <!-- NIC No -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtNIC"><small>NIC No </small></label>
                </div>

                <div class="col-md-4 form-group">
                    <input type="text" class="form-control input-sm" id="txtNIC" name="txtNIC" maxlength="10">
                </div>
                <!-- /NIC No ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- REG No -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtREG"><small>Registration No </small></label>
                </div>

                <div class="col-md-4 form-group">
                    <input type="text" class="form-control input-sm" id="txtReg" name="txtReg" maxlength="10">
                </div>
                <!-- /REG No ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Password -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtPwd"><small>Password </small></label>
                </div>

                <div class="col-md-4 form-group">
                    <input type="password" class="form-control input-sm" id="txtPwd" name="txtPwd">
                </div>
                <!-- /Password ------------------------------------------------------------------------- -->
                

                <!-- Re-enter Password -------------------------------------------------------------------------- -->
                        
                <div class="col-md-4 form-group">
                    <input type="password" class="form-control input-sm" placeholder="Re-enter Password" id="txtRpwd" name="txtRpwd">
                </div>
                <div class="col-md-2 form-group">
                    <label for="txtRpwd">&nbsp;</label>
                </div>

                <!-- /Re-enter Password ------------------------------------------------------------------------- -->
                </div>
                
                <!-- Vertical Spacing -------------------------------------------------------------- -->
                <div class="form-group">&nbsp;</div>
                <!-- /Vertical Spacing ------------------------------------------------------------- -->

                <div class="row">
                <!-- First Name -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtFname"><small>First Name </small></label>
                </div>

                <div class="col-md-10 form-group">
                    <input type="text" class="form-control input-sm" id="txtFname" name="txtFname">
                </div>
                <!-- /First Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Middle Name ------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtMname"><small>Middle Name </small></label>
                </div>

                <div class="col-md-10 form-group">
                    <input type="text" class="form-control input-sm" id="txtMname" name="txtMname">
                </div>
                <!-- /Middle Name ------------------------------------------------------------------------ -->
                </div>
                
                <div class="row">
                <!-- Last Name --------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtLname"><small>Last Name </small></label>
                </div>

                <div class="col-md-10 form-group">
                    <input type="text" class="form-control input-sm" id="txtLname" name="txtLname">
                </div>
                <!-- /Last Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Gender --------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="rdoGender"><small>Gender </small></label>
                </div>

                <div class="col-md-10 form-group">
                <div class="radio-inline">
                    <label>
                        <input type="radio" name="rdoGender" id="rdoGender" name="rdoGender" value="M">
                        Male
                    </label>
                </div>
                <div class="radio-inline">
                    <label>
                        <input type="radio" name="rdoGender" id="rdoGender" name="rdoGender" value="F">
                        Female
                    </label>
                </div>
                </div>
                <!-- /Gender ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- DOB --------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtDOB"><small>Birth Day </small></label>
                </div>

                <div class="col-md-2 form-group">
                    <input type="text" class="form-control input-sm" placeholder="YYYY" id="txtDOBYear" name="txtDOBYear" maxlength="4">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control input-sm" placeholder="MM" id="txtDOBMonth" name="txtDOBMonth" maxlength="2">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control input-sm" placeholder="DD" id="txtDOBDate" name="txtDOBDate" maxlength="2">
                </div>
                <div class="col-md-4 form-group">
                    <label for="txtRpwd">&nbsp;</label>
                </div>
                <!-- /DOB ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Email --------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtEmail"><small>Email </small></label>
                </div>

                <div class="col-md-10 form-group">
                    <input type="email" class="form-control input-sm" id="txtEmail" name="txtEmail">
                </div>
                <!-- /Email ------------------------------------------------------------------------- -->
                </div>


                <!-- Vertical Spacing -------------------------------------------------------------- -->
                        <div class="form-group">&nbsp;</div>
                <!-- /Vertical Spacing ------------------------------------------------------------- -->


                <div class="row">
                    <div class="form-group">
                        <h4 class="page-header custom-page-header"><strong>Academic Details</strong></h4>
                    </div>
                </div>

                <div class="row">
                <!-- Field -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpField"><small>Field </small></label>
                </div>

                <div class="col-md-6 form-group">
                    
                        <select class="form-control input-sm" id="drpField" name="drpField">
                            <option>-- Select a  Field --</option>
                            <?php
                                while($field = mysql_fetch_array($arrField))
                                {
                                    print('<option value="' . $field["fld_code"] . '">' . $field["fld_name"] . '</option>');
                                }
                            ?>  
                        </select>

                </div>
                
                <div class="col-md-4 form-group">&nbsp;</div>
                <!-- /Field ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Degree -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpDegree"><small>Degree </small></label>
                </div>

                <div class="col-md-6 form-group">
                    <div id="divDrpDegree">
                        
                        <!-- drpDegree loads here -->
                        <select class="form-control input-sm" id="drpDegree" name="drpDegree" disabled="disabled">
                            <option>-- Select a  Degree --</option>
                        </select>

                    </div>
                </div>
                
                <div class="col-md-4 form-group">&nbsp;</div>
                <!-- /Degree ------------------------------------------------------------------------- -->
                </div>
                
                <!-- Vertical Spacing -------------------------------------------------------------- -->
                        <div class="form-group">&nbsp;</div>
                <!-- /Vertical Spacing ------------------------------------------------------------- -->
                
                <div class="row">
                <!-- Subjects -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                <label><small>Subjects </small></label>
                </div>

                <div class="col-md-10 form-group">
   
                    <div class="row">

                        <div id="divSubjects">
                            
                            <div class="col-md-8 form-group"
                                <p>Select a Degree from above to view Subjects</p>
                            </div>

                        </div>
                    </div>
                    
                </div>
                <!-- /Subjects ------------------------------------------------------------------------- -->
                </div>

                <div class="row">
                    <div class="form-group page-header">
                        &nbsp;
                    </div>
                </div>
                
                <div class="row">
                <div class="form-group">
                    <div class="col-md-8 form-group">
                        &nbsp;
                    </div>
                    <div class="col-md-2 form-group">
                        <button type="reset" class="form-control btn btn-block btn-default">Cancel</button>
                    </div>
                    <div class="col-md-2 form-group">
                        <button type="button" id="btnSubmit" name="btnSubmit" class="form-control btn btn-block btn-primary">Save</button>
                    </div>
                </div>
                </div>
            </form>
            
        </div>	
    </div>
</div>
<!-- /Content ----------------------------------------------------------------------------------------------------------------- -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/signupstd.js"></script>
  </body>
</html>
