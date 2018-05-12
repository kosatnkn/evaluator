<?php
session_start();

$_SESSION["usrCode"] = "";
$_SESSION["usrName"] = "";
$_SESSION["usrType"] = "";
$_SESSION["usrAppr"] = "";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Evaluator</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="index-background">

<!-- Header ------------------------------------------------------------------------------------------------------------------ -->
	<div class = "navbar navbar-default navbar-static-top navbar-custom">
		<div class = "container">

			<a href = "#" class = "navbar-brand"><h1 class="index-text">Evaluator</h1><small class="index-text">&nbsp;The online evaluator</small></a>

		</div>
	</div>
<!-- /Header ----------------------------------------------------------------------------------------------------------------- -->


	<div class = "container">
	
		<div class="row">
			<div class="col-md-4 col-md-offset-8">
                <form method="post" action="index.link.php" >
					<div class="form-group">
						<h3 class="index-text">Sign In</h3>
					</div>
					
					<div class="form-group">
                        <input type="text" class="form-control" placeholder="ID Number" id="txtID" name="txtID" maxlength="10">
					</div>
					
					<div class="form-group">
						<input type="password" class="form-control" placeholder="Password" id="txtPwd" name="txtPwd">
					</div>
					
					<div class="form-group">
						<div class="row">
							<div class="col-md-8">
								<div class="checkbox index-text">
									<label>
										<input type="checkbox"> Remember me
									</label>
								</div>
							</div>
							<div class="col-md-4">
                                <button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-md btn-block pull-right">Sign In</button>
							</div>
						</div>
					</div>
					
				</form>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4 col-md-offset-8">
				<div class="page-header"></div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4 col-md-offset-8">
			
				<h3 class="index-text">Sign Up</h3>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<p class="text-justify index-text"><small>If you are new here, please sign up.</small></p>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
                            <a href="signupstd.php" class="btn btn-success btn-md btn-block"><span class="glyphicon glyphicon-fire"></span>&nbsp;&nbsp;I am a Student&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
                            <a href="signupins.php" class="btn btn-danger btn-md btn-block"><span class="glyphicon glyphicon-leaf"></span>&nbsp;&nbsp;I am an Instructor</a>
						</div>
					</div>
				</div>
			</div>
		</div>


	</div>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>