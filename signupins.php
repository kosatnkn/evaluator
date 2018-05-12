<?php

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

$UserLogic = new UserLogic();

$arrTitle = $UserLogic->getInstructorTitle();
$arrField = $UserLogic->getField();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up an Instructor</title>

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
        <span class= "navbar-brand pull-left"><span class="custom-badge-ins"><span class="glyphicon glyphicon-leaf"></span></span>&nbsp;&nbsp;Sign Up an Instructor</span>
        <span class = "navbar-brand pull-right">Evaluator</span>
    </div>
</div>
<!-- /Header ------------------------------------------------------------------------------------------------------------------ -->

<!-- Content ------------------------------------------------------------------------------------------------------------------ -->

<div class = "container container-main">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form method="post" action="signupins.link.php">
                
                <div class="row">
                <div class="form-group">
                    <h4 class="page-header custom-page-header">Basic Details</h4>
                </div>
                </div>

                <div class="row">
                <!-- NIC No -------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="txtNIC"><small>NIC No: </small></label>
                        </div>

                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control input-sm" id="txtNIC" name="txtNIC" maxlength="10">
                        </div>
                <!-- /NIC No ------------------------------------------------------------------------- -->
                </div>
                
               <div class="row">
                <!-- Password -------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="txtPwd"><small>Password: </small></label>
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
                <!-- Title -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpTitle"><small>Title: </small></label>
                </div>

                <div class="col-md-4 form-group">
                    <div class="form-group">

                        <select class="form-control input-sm" id="drpTitle" name="drpTitle">
                            <option>-- Select a  Title --</option>
                            <?php
                                while($title = mysql_fetch_array($arrTitle))
                                {
                                    print('<option value="' . $title["tit_code"] . '">' . $title["tit_desc"] . '</option>');
                                }
                            ?>  
                        </select>

                    </div>
                </div>

                <div class="col-md-6 form-group"></div>
        <!-- /Title ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- First Name -------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="txtFname"><small>First Name: </small></label>
                        </div>

                        <div class="col-md-10 form-group">
                                        <input type="text" class="form-control input-sm" id="txtFname" name="txtFname">
                        </div>
                <!-- /First Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Middle Name ------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="txtMname"><small>Middle Name: </small></label>
                        </div>

                        <div class="col-md-10 form-group">
                                        <input type="text" class="form-control input-sm" id="txtMname" name="txtMname">
                        </div>
                <!-- /Middle Name ------------------------------------------------------------------------ -->
                </div>
                
                <div class="row">
                <!-- Last Name --------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="txtLname"><small>Last Name: </small></label>
                        </div>

                        <div class="col-md-10 form-group">
                                        <input type="text" class="form-control input-sm" id="txtLname" name="txtLname">
                        </div>
                <!-- /Last Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Gender --------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="rdoGender"><small>Gender: </small></label>
                        </div>

                        <div class="col-md-10 form-group">
                                <div class="radio-inline">
                                        <label>
                                                <input type="radio" name="rdoGender" id="rdoGender" value="M">
                                                Male
                                        </label>
                                </div>
                                        <div class="radio-inline">
                                                <label>
                                                <input type="radio" name="rdoGender" id="rdoGender" value="F">
                                                Female
                                                </label>
                                        </div>
                                </div>
                <!-- /Gender ------------------------------------------------------------------------- -->
                </div>
                
               <div class="row">
                <!-- DOB --------------------------------------------------------------------------- -->
                    <div class="col-md-2 form-group">
                        <label for="txtDOB"><small>Birth Day: </small></label>
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
                <!-- Marital Status --------------------------------------------------------------------------- -->
                        <div class="col-md-2 form-group">
                                <label for="rdoMarital"><small>Marital Status: </small></label>
                        </div>

                        <div class="col-md-10 form-group">
                                <div class="radio-inline">
                                        <label>
                                                <input type="radio" name="rdoMarital" id="rdoMarital" name="rdoMarital" value="S">
                                                Single
                                        </label>
                                </div>
                                <div class="radio-inline">
                                        <label>
                                                <input type="radio" name="rdoMarital" id="rdoMarital" name="rdoMarital" value="M">
                                                Married
                                        </label>
                                </div>
                                <div class="radio-inline">
                                        <label>
                                                <input type="radio" name="rdoMarital" id="rdoMarital" name="rdoMarital" value="W">
                                                Widowed
                                        </label>
                                </div>
                                <div class="radio-inline">
                                        <label>
                                                <input type="radio" name="rdoMarital" id="rdoMarital" name="rdoMarital" value="D">
                                                Divorsed
                                        </label>
                                </div>
                        </div>
                <!-- /Marital Status ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Email --------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label for="txtEmail"><small>Email: </small></label>
                </div>

                <div class="col-md-10 form-group">
                    <input type="email" class="form-control input-sm" id="txtEmail" name="txtEmail">
                </div>
                <!-- /Email ------------------------------------------------------------------------- -->
                </div>


                <!-- Vertical Spacing -------------------------------------------------------------- -->
                <div class="form-group">&nbsp;</div>
                <!-- /Vertical Spacing ------------------------------------------------------------- -->


                <div class="form-group">
                        <h4 class="page-header custom-page-header">Academic Details</h4>
                </div>
                
                <div class="row">     
                <!-- Department -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpField"><small>Department </small></label>
                </div>

                <div class="col-md-4 form-group">
                    <div class="form-group">

                        <select class="form-control input-sm" id="drpField" name="drpField">
                            <option value="0">-- Select a Department --</option>
                            <?php
                                while($field = mysql_fetch_array($arrField))
                                {
                                    print('<option value="' . $field["fld_code"] . '">' . $field["fld_name"] . '</option>');
                                }
                            ?>  
                        </select>

                    </div>
                </div>
                <!-- /Department ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                    <div class="col-md-10 col-md-offset-2 form-group alert alert-warning" align="center">
                        <p>After selecting a Subject, also select the Course you intend to teach that Subject in</p>
                    </div>
                </div>
                
                <!-- SubjectList ------------------------------------------------------------------------- -->
                <input type="hidden" id="hdnSubjects" name="hdnSubjects" value=""/>
                <!-- /SubjectList ------------------------------------------------------------------------ -->
                
            </form>
                
                <div class="row">     
                <!-- Subjects -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label><small>Subjects </small></label>
                </div>

                <div class="col-md-6 form-group">
                    
                    <div class="form-group" id="divSubjects">

                        Select a Department from above to view Subjects

                    </div>
                    
                </div>
                
                <div class="col-md-4 form-group">&nbsp;</div>
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

        </div>	
    </div>
</div>

<!-- /Content ----------------------------------------------------------------------------------------------------------------- -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/signupins.js"></script>
  </body>
</html>