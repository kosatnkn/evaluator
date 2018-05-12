<?php
/**
 *  Ajax calls from Evaluate are handled from here
 */
 
 //imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/EvalLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________


switch ($_POST["ret_type"]) 
{
    case "e_mcq": // evaluate MCQ
        $EvalLogic = new EvalLogic();

        $data;

        if($EvalLogic->evaluateMCQ($_POST["exm"]))
        {
            $data = "true";
            
            // check wether the paper is purely MCQ
            if($EvalLogic->isMCQOnly($_POST["exm"]))
            {
				// update exm_take
				if($EvalLogic->updateMCQOnlyPaper($_POST["exm"]))
				{
					$data = "true";
				}
				else
				{
					$data = "false";
				}
			}
			
			// check wether the paper is purely structured
            if($EvalLogic->isStrOnly($_POST["exm"]))
            {
				// update exm_take
				if($EvalLogic->updateStrOnlyPaper($_POST["exm"]))
				{
					$data = "true";
				}
				else
				{
					$data = "false";
				}
			}
        }
        else
        {
            $data = "false";
        }

        print $data;

        break;

    case "v_str": // view Structured
        $EvalLogic = new EvalLogic();

        $data = "";

        $result = $EvalLogic->getStrQuestions($_POST["exm"], $_POST["id"]);

        if(mysql_num_rows($result) > 0)
        {
            while($row = mysql_fetch_array($result))
            {
                $data .= "
                <div class=\"row\">
                <div class=\"col-md-12\">

                    <div class=\"panel panel-default\">

                        <div class=\"panel-heading\">
                            <div class=\"row\">
                                <div class=\"col-md-12\">"
                                 . $row["qst_question"] .   
                                "</div>
                            </div>
                        </div>

                        <div class=\"panel-body\">
                        
                            <div class=\"row\">
                                <div class=\"col-md-12 alert-info\"><br>"
                                . $row["str_answer"] .
                                "<br>&nbsp;</div>
                            </div>

                            <div class=\"row\">
                                <div class=\"alert\">"
                                . $row["std_answer"] .
                                "</div>
                            </div>

                        </div>
                        
                        <div class=\"panel-footer\">
                        <div class=\"row\">
                        
                        <input type=\"hidden\" id=\"hdnMax". $row["tke_qst_psudo_no"] . "\" value=\"". $row["qst_marks"] . "\"> 
                        <input type=\"hidden\" id=\"hdnStdID\" value=\"". $row["tke_std_id"] . "\"> 
                        <input type=\"hidden\" id=\"hdnExmNo\" value=\"". $row["tke_exm_no"] . "\"> 
                        
                            <div class=\"col-md-2 col-md-offset-10\">
                              
                              <div class=\"input-group\">
                                    <textarea class=\"form-control input-sm\" id=\"txtMrk". $row["tke_qst_psudo_no"] . "\" 
                                        maxlength=\"3\" rows=\"1\"
                                        onblur=\"updateMarks('". $row["tke_std_id"] . "', ". $row["tke_exm_no"] . ", ". $row["tke_qst_psudo_no"] . ")\">". $row["tke_marks"] . "</textarea>
                                    
                                <span class=\"input-group-addon\">
                                     <strong>&nbsp;<span class=\"custom-badge-ins\">". $row["qst_marks"] . "</span></strong>
                                </span>
                                
                               </div>
                               
                            </div>
                        </div>
                        </div>

                    </div>

                </div>
                </div>";
            }
        }
        else
        {
			$data = "<div class=\"alert alert-warning\" align=\"center\">
						<p><h4>There are no 'Structured' questions for this paper</h4></p>
						<p>Click 'Complete Evaluation' to acknowledge</p>
						<input type=\"hidden\" id=\"hdnStdID\" value=\"". $_POST["id"] . "\"> 
                        <input type=\"hidden\" id=\"hdnExmNo\" value=\"". $_POST["exm"] . "\"> 
					</div>";
		}
        
        print $data;

        break;
    
    case "u_str":
        $EvalLogic = new EvalLogic();

        if($EvalLogic->updateStrMarks($_POST["id"], $_POST["exm"], $_POST["qst"], $_POST["mks"]))
        {
            print("true");
        }
        else
        {
            print("false");
        }
        
        break;
        
    case "c_str":
		$EvalLogic = new EvalLogic();
		
		$data;

        if($EvalLogic->completeStrEval($_POST["exm"], $_POST["id"]))
        {
            $data = "true";
        }
        else
        {
            $data = "false";
        }

        print $data;
		
		break;

	case "p_res":
		$EvalLogic = new EvalLogic();
		
		$data;

        if($EvalLogic->prepareResults($_POST["exm"]))
        {
            $data = "true";
        }
        else
        {
            $data = "false";
        }

        print $data;
		
		break;
		
	case "r_res":
		$EvalLogic = new EvalLogic();
		
		$data;

        if($EvalLogic->releaseResults($_POST["exm"]))
        {
            $data = "true";
        }
        else
        {
            $data = "false";
        }

        print $data;
		
		break;
        
    default:
        break;
}

?>
