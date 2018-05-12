<?php
	session_start();
    
    if($_SESSION["usrCode"] == "")
    {
        // redirect to index.php
        @header("Location:index.php");
    }
	
	if($_SESSION["exm_code"] == "")
    {
		// redirect to home
		@header("Location:home.php");
	}
	
//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________

$ExamLogic = new ExamLogic();
$CommonLogic = new CommonLogic();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Take Exam</title>

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
		
		<a href = "" class = "navbar-brand">SuMMit</a>

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
<div class = "container custom-container-take">

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div id="divShowQst">
			
			<?php
			
				// load first question
				$arrQst = $ExamLogic->loadQuestionForExam($_SESSION["exm_code"], 1, $_SESSION["usrCode"]);
				
				if($arrQst["qst_remain"] > 0)
				{
						// draw question
						if($arrQst["qst_type"] == "M")
						{
							// draw MCQ
							print(drawMCQ($_SESSION["exm_code"], $arrQst));
						}
						else
						{
							// draw Structured
							print(drawStd($_SESSION["exm_code"], $arrQst));
						}
				}
				else
				{
					// exam complete
					print(drawTimesUp());
				}
				
			?>
			
		</div>
	</div>
</div>

</div>
<!-- /Content ----------------------------------------------------------------------------------------------------------------- -->


<!-- Footer ------------------------------------------------------------------------------------------------------------------ -->
<div class = "navbar navbar-inverse navbar-fixed-bottom">
	<div class = "container">
	
	<!-- Next and Prev values -->
	<input type="hidden" id="hdnPrev" value="0">
	<input type="hidden" id="hdnCurrent" value="1">
	<input type="hidden" id="hdnNext" value="2">
	<!-- -------------------- -->
	
		<div class="row">
			<div class="col-md-2">
				<h4>
					<div id="divQst">
						<p class="navbar-text"><strong>1 of 5</strong></p>
					</div>
				</h4>
			</div>
			
			<div class="col-md-4">
				<h4>
					<div id="divTime">
						<p class="navbar-text">
						<strong>time string</strong>
						<em><small>&nbsp;&nbsp;&nbsp;remaining</small></em>
						</p>
					</div>
				</h4>
			</div>
			
		<!-- User and Exam Details ----------------------------- -->
		
			<input type="hidden" id="hdnStdID" value="<?php print($_SESSION["usrCode"]); ?>">
			<input type="hidden" id="hdnExmNo" value="<?php print($_SESSION["exm_code"]); ?>">
			
		<!-- /User and Exam Details ---------------------------- -->
			
			<div class="col-md-6">
				<div class="col-md-3">
					<button type="button" id="btnComplete" class="btn btn-block btn-danger navbar-btn" 
					data-loading-text="Saving..." 
					onclick="btnComplete_Click()">Complete
					</button>
				</div>
				
				<div class="col-md-3">
					<button type="button" id="btnSave" class="btn btn-block btn-default navbar-btn" 
					data-loading-text="Saving..." 
					onclick="btnSave_Click()">Save
					</button>
				</div>
				
				<div class="col-md-3">
					<button type="button" id="btnPrev" class="btn btn-block btn-primary navbar-btn" disabled 
					data-loading-text="Previous" 
					onclick="btnPrev_Click()">
						<span class="glyphicon glyphicon-chevron-left"></span>
						Previous
					</button>
				</div>
				
				<div class="col-md-3">
					<button type="button" id="btnNext" class="btn btn-block btn-primary navbar-btn" 
					data-loading-text="Next" 
					onclick="btnNext_Click()">
						Next
						<span class="glyphicon glyphicon-chevron-right"></span>
						</button>
				</div>
			</div>
			
		</div>
	
	</div>
</div>
<!-- /Footer ----------------------------------------------------------------------------------------------------------------- -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/take.js"></script>
  </body>
</html>

<?php

function drawMCQ($intExmNo, $arrQst)
{
	$CommonLogic = new CommonLogic();
	
	$intAnswer = 0;
	$strRemain = $CommonLogic->createTimeString($arrQst["qst_remain"]);
	
	$intPrev = $arrQst["qst_no"] - 1;
	
	$intNext = 0;
	
	if($arrQst["qst_no"] == $arrQst["qst_count"])
	{
		$intNext = 0;
	}
	else
	{
		$intNext = $arrQst["qst_no"] + 1;
	}
	
	if($arrQst["qst_answer"] != "")
	{
		$intAnswer = $arrQst["qst_answer"];
	} 
	
        
    $data = "
    <div class=\"row\">
    <div class=\"col-md-12\">
        <input type=\"hidden\" id=\"hdnQstCount\" value=\"" . $arrQst["qst_count"] . "\">
        <input type=\"hidden\" id=\"hdnQstRemaining\" value=\"" . $strRemain . "\">
        <input type=\"hidden\" id=\"hdnQstPrev\" value=\"" . $intPrev . "\">
        <input type=\"hidden\" id=\"hdnQstCurrent\" value=\"" . $arrQst["qst_no"] . "\">
        <input type=\"hidden\" id=\"hdnQstNext\" value=\"" . $intNext . "\">
        <input type=\"hidden\" id=\"hdnQstType\" value=\"" . $arrQst["qst_type"] . "\">
        <input type=\"hidden\" id=\"hdnStatus\" value=\"O\">
        
        <div class=\"panel panel-default\">
        
            <div class=\"panel-heading\">
                <div class=\"row\">
                    <div class=\"col-md-10\">"
                     . $arrQst["qst_question"] .   
                    "</div>
                    <div class=\"col-md-2\" align=\"right\">
                        <span class=\"label label-danger\"><small>" 
                        . $arrQst["qst_marks"] . "&nbsp;Marks</small></span>&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            
            <div class=\"panel-body\">";
            
            
			while($answer = mysql_fetch_array($arrQst["qst_answers"]))
			{
				$data .= "<div class=\"row\">
							<div class=\"col-md-12\">
								<div class=\"radio\">
								  <label>";
				
				if($arrQst["qst_answer"] != "" && ($answer["mcq_ans_no"] == $arrQst["qst_answer"]))
				{
					$data .= "<input type=\"radio\" name=\"rdoAnswer\" id=\"rdoAnswer\" value=\"" . $answer["mcq_ans_no"] . "\" checked>";
				}
				else
				{
					$data .= "<input type=\"radio\" name=\"rdoAnswer\" id=\"rdoAnswer\" value=\"" . $answer["mcq_ans_no"] . "\">";
				}				  
				
									
				$data .=		$answer["mcq_answer"] .
								  "</label>
								</div>
							</div>
						  </div>";
			}
            
            
   $data .="</div>
            </div>
            
    </div>
    </div>";
    
    return $data;
}

function drawStd($intExmNo, $arrQst)
{
	$CommonLogic = new CommonLogic();
	
	$strAnswer = "";
	$strRemain = $CommonLogic->createTimeString($arrQst["qst_remain"]);
	
	$intPrev = $arrQst["qst_no"] - 1;
	
	$intNext = 0;
	
	if($arrQst["qst_no"] == $arrQst["qst_count"])
	{
		$intNext = 0;
	}
	else
	{
		$intNext = $arrQst["qst_no"] + 1;
	}
	
	if($arrQst["qst_answer"] != "")
	{
		$strAnswer = $arrQst["qst_answer"];
	} 
	
    $data = "
    <div class=\"row\">
    <div class=\"col-md-12\">
        <input type=\"hidden\" id=\"hdnQstCount\" value=\"" . $arrQst["qst_count"] . "\">
        <input type=\"hidden\" id=\"hdnQstRemaining\" value=\"" . $strRemain . "\">
        <input type=\"hidden\" id=\"hdnQstPrev\" value=\"" . $intPrev . "\">
        <input type=\"hidden\" id=\"hdnQstCurrent\" value=\"" . $arrQst["qst_no"] . "\">
        <input type=\"hidden\" id=\"hdnQstNext\" value=\"" . $intNext . "\">
        <input type=\"hidden\" id=\"hdnQstType\" value=\"" . $arrQst["qst_type"] . "\">
        <input type=\"hidden\" id=\"hdnStatus\" value=\"O\">
        
        <div class=\"panel panel-default\">
        
            <div class=\"panel-heading\">
                <div class=\"row\">
                    <div class=\"col-md-10\">"
                     . $arrQst["qst_question"] .   
                    "</div>
                    <div class=\"col-md-2\" align=\"right\">
                        <span class=\"label label-danger\"><small>" 
                        . $arrQst["qst_marks"] . "&nbsp;Marks</small></span>&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            
            <div class=\"panel-body\">
                <textarea id=\"txtStrAns\" rows=\"10\" class=\"form-control\">" . $strAnswer . "</textarea>
            </div>
            </div>
            
    </div>
    </div>";
    
    return $data;
}
 
 function drawTimesUp()
 {
    $data = "<div class=\"row\">
                <div class=\"col-md-12\">

                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <div class=\"alert alert-danger\" align=\"center\">

                            <input type=\"hidden\" id=\"hdnStatus\" value=\"C\">

                            <div class=\"row\">
                                <div class=\"col-md-12\">
                                    <h4>Time's Up!</h4>
                                    <p>Time allocated for this session is over. Click 'Complete' to return to Homepage.</p>
                                </div>
                            </div>

                            <div class=\"row\">
                                <div class=\"col-md-6 col-md-offset-3\">	
                                    <button type=\"button\" id=\"btnCompleteAlt\" class=\"btn btn-block btn-danger navbar-btn\" 
                                    onclick=\"btnComplete_Click()\">Complete
                                    </button>
                                </div>
                            </div>

                            </div>
                    </div>
                    </div>

                </div>
                </div>";

	return $data;
 }

?>
