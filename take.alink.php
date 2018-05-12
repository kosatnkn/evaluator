<?php
/**
 *  Ajax calls from Take Exam are handled from here
 */
 
 //imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________


switch ($_POST["ret_type"]) 
{
	case "s_ans": // save answer
		$ExamLogic = new ExamLogic();
    
        $data;

        if($ExamLogic->saveExamAnswer($_POST["id"], $_POST["exm"], $_POST["qst"], $_POST["ans"]))
        
        {
            $data = "true";
        }
        else
        {
            $data = "false";
        }

        print $data;
		break;
		
	case "v_pre": // load previous question
		$ExamLogic = new ExamLogic();
		
		$arrQst = $ExamLogic->loadQuestionForExam($_POST["exm"], $_POST["pre"], $_POST["id"]);
		
		if($arrQst["qst_remain"] > 0)
		{
			// draw question
			if($arrQst["qst_type"] == "M")
			{
				// draw MCQ
				print(drawMCQ($_POST["exm"], $arrQst));
			}
			else
			{
				// draw Structured
				print(drawStd($_POST["exm"], $arrQst));
			}
		}
		else
		{
			// exam complete
			print(drawTimesUp());
		}
		
		break;
		
	case "v_nxt": // load next question
		$ExamLogic = new ExamLogic();
		
		$arrQst = $ExamLogic->loadQuestionForExam($_POST["exm"], $_POST["nxt"], $_POST["id"]);
		
		if($arrQst["qst_remain"] > 0)
		{
			// draw question
			if($arrQst["qst_type"] == "M")
			{
				// draw MCQ
				print(drawMCQ($_POST["exm"], $arrQst));
			}
			else
			{
				// draw Structured
				print(drawStd($_POST["exm"], $arrQst));
			}
		}
		else
		{
			// exam complete
			print(drawTimesUp());
		}
		
		break;
	
	default:
        break;
}


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
