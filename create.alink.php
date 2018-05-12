<?php

// ajax calls from create.php is handled from here

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________



switch ($_POST["ret_type"]) 
{
    case "u_dur": // update exam duration
        $ExamLogic = new ExamLogic();
        $CommonLogic = new CommonLogic();
        
        $intDuration = $ExamLogic->editExmDuration($_POST["exm"], 
                                    $CommonLogic->timeInMinutes($_POST["durH"], $_POST["durM"]));
        
        if($intDuration)
        {
            print $CommonLogic->createTimeString($intDuration);
        }
        else
        {
            print "Error";
        }
        
        break;
        
    case "a_mcq": // add MCQ
        $ExamLogic = new ExamLogic();
        
        $arrAnswer;
        
        if($_POST["ans1"] != "")
        {
            $arrAnswer[] = $_POST["ans1"];
        }
        if($_POST["ans2"] != "")
        {
            $arrAnswer[] = $_POST["ans2"];
        }
        if($_POST["ans3"] != "")
        {
            $arrAnswer[] = $_POST["ans3"];
        }
        if($_POST["ans4"] != "")
        {
            $arrAnswer[] = $_POST["ans4"];
        }

        
        // save question
        if($ExamLogic->addMCQQuestion($_POST["exm"], $_POST["qst"], 
                $arrAnswer, $_POST["crt"], $_POST["mks"]))
        {
            // load question
            $arrMCQ = $ExamLogic->getLatestMCQ($_POST["exm"]);
            
            // print question
            print(drawMCQ($_POST["exm"], $arrMCQ));
        }
        else
        {
            print("false");
        }

        break;
    
    case "d_mcq": // delete MCQ Question
        $ExamLogic = new ExamLogic();
        
        if($ExamLogic->deleteMCQQuestion($_POST["exm"], $_POST["qst"]))
        {
            print "true";
        }
        else
        {
            print "false";
        }
        
        break;
        
    case "a_str": // add Structured question
        $ExamLogic = new ExamLogic();
        
        // save question
        if($ExamLogic->addStdQuestion($_POST["exm"], $_POST["qst"], 
                $_POST["ans"], $_POST["mks"]))
        {
            // load question
            $arrStr = $ExamLogic->getLatestStd($_POST["exm"]);
            
            // print question
            print(drawStd($_POST["exm"], $arrStr));
        }
        else
        {
            print("false");
        }
        
        break;
   
    case "d_str": // delete Structured Question
    $ExamLogic = new ExamLogic();

    if($ExamLogic->deleteStrQuestion($_POST["exm"], $_POST["qst"]))
    {
        print "true";
    }
    else
    {
        print "false";
    }

        break;   
      
    case "v_qst":
        $ExamLogic = new ExamLogic();
        
        $arrQst = $ExamLogic->loadQuestion($_POST["exm"], $_POST["qst"]);
        
        $data;

        if($arrQst["qst_type"] == "M") // draw MCQ
        {
            $ans1 = "";
            $ans2 = "";
            $ans3 = "";
            $ans4 = "";
            
            $ansChkd1 = "";
            $ansChkd2 = "";
            $ansChkd3 = "";
            $ansChkd4 = "";
            
            $ans1 = mysql_result($arrQst["qst_answers"], 0, "mcq_answer");
            $ans2 = mysql_result($arrQst["qst_answers"], 1, "mcq_answer");

            if(mysql_result($arrQst["qst_answers"], 0, "mcq_is_ans") == 1)
            {
                $ansChkd1 = "checked=\"checked\"";
            }

            if(mysql_result($arrQst["qst_answers"], 1, "mcq_is_ans") == 1)
            {
                $ansChkd2 = "checked=\"checked\"";
            }

            
            if(mysql_num_rows($arrQst["qst_answers"]) == 3)
            {
                $ans3 = mysql_result($arrQst["qst_answers"], 2, "mcq_answer");

                if(mysql_result($arrQst["qst_answers"], 2, "mcq_is_ans") == 1)
                {
                    $ansChkd3 = "checked=\"checked\"";
                }
            }
            
            if(mysql_num_rows($arrQst["qst_answers"]) == 4)
            {
                $ans3 = mysql_result($arrQst["qst_answers"], 2, "mcq_answer");

                if(mysql_result($arrQst["qst_answers"], 2, "mcq_is_ans") == 1)
                {
                    $ansChkd3 = "checked=\"checked\"";
                }
                
                $ans4 = mysql_result($arrQst["qst_answers"], 3, "mcq_answer");

                if(mysql_result($arrQst["qst_answers"], 3, "mcq_is_ans") == 1)
                {
                    $ansChkd4 = "checked=\"checked\"";
                }
            }
        
            
            $data = "<div class=\"modal-body\">
                
                <div calass=\"container\">
                    
                    <div class=\"row\">
                        <div id=\"divEMCQMsg\" align=\"center\" class=\"col-md-6 col-md-offset-3\"></div>
                    </div>

                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <textarea id=\"txtEMQst\" rows=\"3\" class=\"form-control input-sm\"
                                      placeholder=\"Add the Question\">" . $arrQst["qst_question"] . "</textarea>
                        </div>
                    </div>

                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <div class=\"input-group\">
                                <span class=\"input-group-addon\">
                                <input type=\"radio\" id=\"rdoEMCQAns\" name=\"rdoEMCQAns\" value=\"1\" $ansChkd1>
                                </span>
                                <textarea rows=\"1\" id=\"txtEMAns1\" placeholder=\"Answer 1\" class=\"form-control input-sm\">$ans1</textarea>
                            </div>
                        </div>
                    </div>
                        
                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <div class=\"input-group\">
                                <span class=\"input-group-addon\">
                                <input type=\"radio\" id=\"rdoEMCQAns\" name=\"rdoEMCQAns\" value=\"2\" $ansChkd2>
                                </span>
                                <textarea rows=\"1\" id=\"txtEMAns2\" placeholder=\"Answer 2\" class=\"form-control input-sm\">$ans2</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <div class=\"input-group\">
                                <span class=\"input-group-addon\">
                                <input type=\"radio\" id=\"rdoEMCQAns\" name=\"rdoEMCQAns\" value=\"3\" $ansChkd3>
                                </span>
                                <textarea rows=\"1\" id=\"txtEMAns3\" placeholder=\"Answer 3\" class=\"form-control input-sm\">$ans3</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <div class=\"input-group\">
                                <span class=\"input-group-addon\">
                                <input type=\"radio\" id=\"rdoEMCQAns\" name=\"rdoEMCQAns\" value=\"4\" $ansChkd4>
                                </span>
                                <textarea rows=\"1\" id=\"txtEMAns4\" placeholder=\"Answer 4\" class=\"form-control input-sm\">$ans4</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-2 col-md-offset-9\">
                            <textarea rows=\"1\" id=\"txtEMCQMarks\" class=\"form-control input-sm custom-input-info\" 
                                   placeholder=\"Marks\" maxlength=\"3\">" . $arrQst["qst_marks"] . "</textarea>
                        </div>
                    </div>
                
                </div>
                
            </div>
            
            <div class=\"modal-footer\">
                
                <button type=\"button\" class=\"btn btn-default btn-sm\" id=\"btnEMCQClose\" data-dismiss=\"modal\">Close</button>
                
                <button type=\"button\" class=\"btn btn-primary btn-sm\" id=\"btnEMCQSave\" data-loading-text=\"Updating Question...\"
                        onclick=\"editMCQ(" . $_POST["exm"] . ", " . $_POST["qst"] . ")\">Update Question</button>
            
            </div>";
        }
        elseif($arrQst["qst_type"] == "S") // draw Structured
        {
            $data = "
            <div class=\"modal-body\">
                
                <div calass=\"container\">
                    
                    <div class=\"row\">
                        <div id=\"divEStrMsg\" align=\"center\" class=\"col-md-6 col-md-offset-3\"></div>
                    </div>

                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <textarea id=\"txtESQst\" rows=\"3\" class=\"form-control input-sm\"
                                      placeholder=\"Add the Question\">" . $arrQst["qst_question"] . "</textarea>
                        </div>
                    </div>

                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-10 col-md-offset-1\">
                            <textarea id=\"txtESAns\" rows=\"3\" class=\"form-control input-sm\"
                                      placeholder=\"Add the Answer\">" . $arrQst["qst_answer"] . "</textarea>
                        </div>
                    </div>
                    
                    <div class=\"row\">
                        &nbsp;
                    </div>
                    
                    <div class=\"row\">
                        <div class=\"col-md-2 col-md-offset-9\">
                            <textarea rows=\"1\" id=\"txtEStrMarks\" class=\"form-control input-sm custom-input-info\" 
                                   placeholder=\"Marks\" maxlength=\"3\">" . $arrQst["qst_marks"] . "</textarea>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
            <div class=\"modal-footer\">
                <a href=\"#\" class=\"btn btn-default btn-sm\" id=\"btnEStrClose\" data-dismiss=\"modal\">Close</a>
                <a href=\"#\" class=\"btn btn-primary btn-sm\"  id=\"btnEStrSave\" data-loading-text=\"Updating Question...\"
                   onclick=\"editStr(" . $_POST["exm"] . ", " . $_POST["qst"] . ")\">Update Question</a>
            </div>";
        }
        
        print $data;
        
        break;
    
    case "u_mcq":
        $ExamLogic = new ExamLogic();
        
        $arrAnswer;
        
        if($_POST["ans1"] != "")
        {
            $arrAnswer[] = $_POST["ans1"];
        }
        if($_POST["ans2"] != "")
        {
            $arrAnswer[] = $_POST["ans2"];
        }
        if($_POST["ans3"] != "")
        {
            $arrAnswer[] = $_POST["ans3"];
        }
        if($_POST["ans4"] != "")
        {
            $arrAnswer[] = $_POST["ans4"];
        }

        
        // edit question
        if($ExamLogic->editMCQQuestion($_POST["exm"], $_POST["qstn"], $_POST["qst"], 
                $arrAnswer, $_POST["crt"], $_POST["mks"]))
        {
            // load question
            $arrMCQ = $ExamLogic->getMCQ($_POST["exm"], $_POST["qstn"]);
            
            // print question
            print(drawMCQ($_POST["exm"], $arrMCQ));
        }
        else
        {
            print("false");
        }
      
        break;
    
    case "u_str":
        $ExamLogic = new ExamLogic();
        
        // save question
        if($ExamLogic->editStdQuestion($_POST["exm"], $_POST["qstn"], $_POST["qst"], 
                                        $_POST["ans"], $_POST["mks"]))
        {
            // load question
            $arrStr = $ExamLogic->getStd($_POST["exm"], $_POST["qstn"]);
            
            // print question
            print(drawStd($_POST["exm"], $arrStr));
        }
        else
        {
            print("false");
        }
        
        break;
        
    default:
        break;
}

function drawMCQ($intExmNo, $arrMCQ)
{
    $data = "
    <div class=\"row\" id=\"divMCQ" . $arrMCQ["qst_no"] . "\">
    <div class=\"col-md-10 col-md-offset-1\">
        
        <div class=\"panel panel-default\">
        
            <div class=\"panel-heading\">
                <div class=\"row\">
                    <div class=\"col-md-10\">"
            
                    . $arrMCQ["qst_question"] .
            
                    "</div>
                    <div class=\"col-md-2\" align=\"right\">
                        <span class=\"label label-danger\"><small>" . $arrMCQ["qst_marks"] . "</small></span>&nbsp;&nbsp;&nbsp;
                        <span class=\"btn-link\" onclick=\"showMCQ($intExmNo, " . $arrMCQ["qst_no"] . ")\">
                            <small><span class=\"glyphicon glyphicon-pencil\">&nbsp;</span></small>
                        </span>
                        <span class=\"btn-link\" onclick=\"deleteMCQ($intExmNo, " . $arrMCQ["qst_no"] . ")\">
                            <small><span class=\"glyphicon glyphicon-remove\"></span></small>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class=\"panel-body\">";
    
    $intAnsNo = 1;
    
    while($answer = mysql_fetch_array($arrMCQ["qst_answers"]))
    {
        $data .= "<div class=\"row\">
                    <div class=\"col-md-1 custom-page-header\">
                    <h4 class=\"pull-right\">" . $intAnsNo . "</h4></div>
                    <div class=\"col-md-10\">
                        <div class=\"panel-heading";
        
        if($answer["mcq_is_ans"] == 1)
        {
            $data .= " alert-info";
        }
                
        $data .=        "\">"
                        . $answer["mcq_answer"] .
                        "</div>
                    </div>
                </div>";
        
        $intAnsNo++;
    }
    
    $data .= "</div>
        
        </div>
            
    </div>
    </div>";
    
    return $data;
}

function drawStd($intExmNo, $arrStr)
{
    $data = "
    <div class=\"row\" id=\"divStr" . $arrStr["qst_no"] . "\">
    <div class=\"col-md-10 col-md-offset-1\">
        
        <div class=\"panel panel-default\">
        
            <div class=\"panel-heading\">
                <div class=\"row\">
                    <div class=\"col-md-10\">"
                     . $arrStr["qst_question"] .   
                    "</div>
                    <div class=\"col-md-2\" align=\"right\">
                        <span class=\"label label-danger\"><small>" . $arrStr["qst_marks"] . "</small></span>&nbsp;&nbsp;&nbsp;
                        <span class=\"btn-link\" onclick=\"showStr($intExmNo, " . $arrStr["qst_no"] . ")\">
                            <small><span class=\"glyphicon glyphicon-pencil\">&nbsp;</span></small>
                        </span>
                        <span class=\"btn-link\" onclick=\"deleteStr($intExmNo, " . $arrStr["qst_no"] . ")\">
                            <small><span class=\"glyphicon glyphicon-remove\"></span></small>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class=\"panel-body\">
            
                <div class=\"panel-heading alert-info\">"
                . $arrStr["qst_answer"] .
                "</div>
                
            </div>
            </div>
            
    </div>
    </div>";
    
    return $data;
}

?>