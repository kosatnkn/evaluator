<?php
    session_start();
    
    if($_SESSION["usrCode"] == "")
    {
        // redirect to index.php
        @header("Location:index.php");
    }
    
    //imports______________________________________________________________________
    require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/EvalLogic.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
    //_____________________________________________________________________________

    $UserLogic = new UserLogic();
    $ExamLogic = new ExamLogic();
    $EvalLogic = new EvalLogic();
    $CommonLogic = new CommonLogic();
    
    $arrExam = $EvalLogic->getEvalExamDetails($_SESSION["exm_code"]);
    $arrEvalList = $EvalLogic->getEvalList($_SESSION["exm_code"]);
    $blnEvalComplete = $EvalLogic->isEvalComplete($_SESSION["exm_code"]);
    $blnPubReady = $EvalLogic->isPublishReady($_SESSION["exm_code"]);
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evaluate Exam</title>

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
        <div class="row">
            <a href = "" class = "navbar-brand">Evaluator</a>

            <button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
            </button>

            <div class = "collapse navbar-collapse navHeaderCollapse">
                <ul class = "nav navbar-nav navbar-right">
                    <li>
                        <a href="home.php"><span class="glyphicon glyphicon-home"></span></a>
                    </li>
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
                            <li><a href="index.php">Log Out</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
    </div>
    
    <div class="row" align="center">
        <?php
            
            $strExmDetails = "";
            $strExmTyp = "";
            $strExmDur = "";
            
            while($exam = mysql_fetch_array($arrExam))
            {
                $strSem = "";
                
                if($exam["csb_semester"] == "1")
                {
                    $strSem = "I";
                }
                else
                {
                    $strSem = "II";
                }
                
                $strExmDetails = "<h4>" . $exam["sbj_name"] . "&nbsp;&nbsp;&nbsp;
                                    <small><em>" . $exam["crs_name"] . "</em>&nbsp;(" . $exam["exm_year"] . 
                                    " - Semester " . $strSem . ")</small></h4>";
                
                if($exam["exm_type"] == "Q")
                {
                    $strExmTyp = "Quiz";
                }
                elseif($exam["exm_type"] == "M")
                {
                    $strExmTyp = "Mid Semester";
                }
                elseif($exam["exm_type"] == "E")
                {
                    $strExmTyp = "End Semester";
                }
                
                $strExmDur = $CommonLogic->createTimeString($exam["exm_duration"]);
            }
            
            print $strExmDetails;
        ?>
    </div>
     
    <div class="row">

        <div class="col-md-2">
            <a id="btnEvalMCQ" class="btn navbar-btn btn-block btn-primary btn-sm" 
            onclick="evalMCQ(<?php print($_SESSION["exm_code"]); ?>)"
            data-loading-text="Evaluating...">
                <span class="glyphicon glyphicon-check"></span>&nbsp;&nbsp;&nbsp;Evaluate All MCQ s
            </a>
        </div>

        <div class="col-md-4">
            &nbsp;
        </div>

        <div class="col-md-6 pull-right">
            <div class="col-md-4">
                &nbsp;
            </div>

<?php

	if($blnEvalComplete)
	{
		print("<div class=\"col-md-4\">
					<a id=\"btnPrepare\" class=\"btn navbar-btn btn-block btn-primary btn-sm\"
					onclick=\"prepareResults(" . $_SESSION["exm_code"] . ")\"
					data-loading-text=\"Preparing...\">
						<span class=\"glyphicon glyphicon-flash\"></span>&nbsp;&nbsp;&nbsp;Prepare Results
					</a>
				</div>");
	}
	else
	{
		print("<div class=\"col-md-4\">
					<a class=\"btn navbar-btn btn-block btn-default btn-sm\" disabled>
						<span class=\"glyphicon glyphicon-flash\"></span>&nbsp;&nbsp;&nbsp;Prepare Results
					</a>
				</div>");
	}
	
	if($blnPubReady)
	{
		 print("<div class=\"col-md-4\">
                <a id=\"btnPublish\" class=\"btn navbar-btn btn-block btn-success btn-sm\"
                onclick=\"publishResults(" . $_SESSION["exm_code"] . ")\"
					data-loading-text=\"Publishing\">
                    <span class=\"glyphicon glyphicon-open\"></span>&nbsp;&nbsp;&nbsp;Release Results
                </a>
            </div>");
	}
	else
	{
		print("<div class=\"col-md-4\">
                <a class=\"btn navbar-btn btn-block btn-default btn-sm\" disabled>
                    <span class=\"glyphicon glyphicon-open\"></span>&nbsp;&nbsp;&nbsp;Release Results
                </a>
            </div>");
	}
        
?>
		</div>
    </div>
        
</div>

</div>
    
<!-- /Header ------------------------------------------------------------------------------------------------------------------ -->


<!-- Content ------------------------------------------------------------------------------------------------------------------ -->
<div class="container custom-container-create">

<div id="divExmEval">

	<table width="100%" class="table table-hover">

<?php

	$data = "<tr class=\"active\">
                    <th width=\"5%\"></th>
                    <th width=\"61%\"></th>
                    <th width=\"8%\"><div align=\"center\"><small>MCQ</small></div></th>
                    <th width=\"8%\"><div align=\"center\"><small>Structured</small></div></th>
                    <th width=\"10%\"><div align=\"center\"><small>Total</small></div></th>
                    <th width=\"8%\"><div align=\"center\"><small>Grade</small></div></td>
                </tr>";
	
	while($row = mysql_fetch_array($arrEvalList))
        {
		$data .= "<tr id=\"trStd" . $row["tke_std_id"] ."\">";
              
        if($row["tke_qst_to_eval"] == 0 && $row["tke_str_marks"] != "") // all answers evaluated and summerized
        {
            $data .= "<td width=\"5%\">
                        <div align=\"center\">
                            <span class=\"custom-badge-eval\">
                                <span class = \"glyphicon glyphicon-check\"></span>
                            </span>
                        </div>
                        </td>";
        }
        else
        {
            $data .= "<td width=\"5%\">
                        <div align=\"center\">
                            <span class=\"custom-badge-not-eval\">
                                <span class = \"glyphicon glyphicon-unchecked\"></span>
                            </span>
                        </div>
                        </td>";
        }	


        $data .= "
            <td width=\"61%\"><small><em>" . $row["tke_std_id"] . "</em>&nbsp;&nbsp;|&nbsp;&nbsp;<strong>" . $row["std_name"]. "</strong></small></td>

            <td width=\"8%\"><div align=\"center\">" . $row["tke_mcq_marks"]. "</div></td>";

        
        if($row["tke_str_marks"] == "")
        {
            $data .= "<td width=\"8%\">
                        <div align=\"center\">
                            <a onclick=\"evalStr('" . $_SESSION["exm_code"] . "', '" . $row["tke_std_id"] . "')\" 
                                class=\"btn btn-block btn-xs btn-primary\"
                                title=\"Click here to evaluate Structured Questions\">Evaluate</a>
                        </div>
                      </td>";
        }
        else
        {
            $data .= "<td width=\"8%\"><div align=\"center\">" . $row["tke_str_marks"]. "</div></td>";
        }

		if($row["tke_tot_marks"] == "")
		{
			$data .= "
			<td width=\"10%\"><div align=\"center\"></div></td>
				<td width=\"8%\">
				</td>
			  </tr>";
		}
		else
		{
			$data .= "
				<td width=\"10%\"><div align=\"center\">" . $row["tke_tot_marks"]. "</div></td>

				<td width=\"8%\">";
				
			$data .= $CommonLogic->drawGrade($row["tke_percentage"]);
					
			$data .= "</td>

			  </tr>";
		  }
    }

print $data;

?>

	</table>
    
</div>
    
</div>

<!-- /Content ----------------------------------------------------------------------------------------------------------------- -->



<!-- Modals ---------------------------------------------------------------- -->


<!-- Eval Structured -------------------------------------------------------- -->
<div class="modal fade" id="mdlEvalStr" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Evaluate Structured Questions</strong></h5>
            </div>
            
            <div class="modal-body">
                
                <div calass="container">
                    
                    <div class="row">
                        <div id="divEStrMsg" align="center" class="col-md-6 col-md-offset-3"></div>
                    </div>

                    <div id="divStrQst">
                        Content loads here
                    </div>
                    
                </div>
                
            </div>
            
            <div class="modal-footer">
				<div class="col-md-6 alert alert-warning" align="center"><small>Click 'Complete Evaluation' to calculate summery</small></div>
				<div class="col-md-6">
					<a href="#" class="btn btn-default btn-sm" id="btnEStrClose" data-dismiss="modal">Close</a>
					
					<a href="#" class="btn btn-primary btn-sm"  id="btnEvalComp" data-loading-text="Saving..."
					   onclick="completeStrEval()">Complete Evaluation</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Eval Structured ------------------------------------------------------- -->


<!-- /Modals ---------------------------------------------------------------- -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/evaluate.js"></script>
  </body>
</html>
