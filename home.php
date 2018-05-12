<?php
    session_start();
    
    if($_SESSION["usrCode"] == "")
    {
        // redirect to index.php
        @header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

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
    <!-- Header --------------------------------------------------------------------------------------------------------------- -->
	<div class = "navbar navbar-default navbar-fixed-top">
		<div class = "container">
		
		<a href = "" class = "navbar-brand">Evaluator</a>

		<button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
                    <span class = "icon-bar"></span>
                    <span class = "icon-bar"></span>
                    <span class = "icon-bar"></span>
		</button>

		<div class = "collapse navbar-collapse navHeaderCollapse">
                    <ul class = "nav navbar-nav navbar-right">
                        
                        <li class="active dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php print($_SESSION["usrName"]);?>
                                &nbsp;&nbsp;
                                
                                <?php
                                
                                    if($_SESSION["usrType"] == "A")
                                    {
                                        print("<span class=\"custom-badge-admin\"><span class = \"glyphicon glyphicon-user\"></span></span>");
                                    }
                                    elseif($_SESSION["usrType"] == "I")
                                    {
                                        print("<span class=\"custom-badge-ins\"><span class = \"glyphicon glyphicon-leaf\"></span></span>");
                                    }
                                    else
                                    {
                                        print("<span class=\"custom-badge-std\"><span class = \"glyphicon glyphicon-fire\"></span></span>");
                                    }
                                    
                                ?>
                                
                                &nbsp;&nbsp;<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#mdlPwd" data-toggle="modal">Change Password</a></li>
                                <li><a href="index.php">Log Out</a></li>
                            </ul>
                        </li>
                       
                    </ul>
		</div>
		</div>
	</div>
<!-- /Header ------------------------------------------------------------------------------------------------------------------ -->


<!-- Content ------------------------------------------------------------------------------------------------------------------ -->
<div class = "container container-main">
<?php

if($_SESSION['usrAppr'] == "N")
{
    // not approved
    include_once 'ui/home.wait.php';
}
elseif($_SESSION['usrAppr'] == "Y")
{
    // approved
    if($_SESSION["usrType"] == "A")
    {
        // admin
        include_once 'ui/home.admin.php';
    }
    elseif($_SESSION["usrType"] == "S")
    {
        // student
        include_once 'ui/home.student.php';
    }
    elseif($_SESSION["usrType"] == "I")
    {
        // instructor
        include_once 'ui/home.instructor.php';
    }
    
}
?>
</div>

<!-- /Content ----------------------------------------------------------------------------------------------------------------- -->


<!-- Modals ---------------------------------------------------------------- -->

<div class="modal fade" id="mdlPwd" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Change Password</strong></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="divPwdMsg" align="center" class="col-md-8 col-md-offset-2">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <input type="password" class="form-control input-sm" placeholder="Old Password" id="txtOpwd" name="txtOpwd">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <input type="password" class="form-control input-sm" placeholder="New Password" id="txtNpwd" name="txtNpwd">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <input type="password" class="form-control input-sm" placeholder="Re-enter Password" id="txtRpwd" name="txtRpwd">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" onclick="clearPwd()"><small>Close</small></a>
                <a href="#" class="btn btn-primary" onclick="changePwd('<?php print($_SESSION["usrCode"]); ?>')"><small>Change Password</small></a>
            </div>
        </div>
    </div>
</div>

<!-- /Modals --------------------------------------------------------------- -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/home.js"></script>
  </body>
</html>