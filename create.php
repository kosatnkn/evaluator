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
    require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
    //_____________________________________________________________________________

    $UserLogic = new UserLogic();
    $ExamLogic = new ExamLogic();
    $CommonLogic = new CommonLogic();
    
    $arrExam = $ExamLogic->getExamDetails($_SESSION["exm_code"]);
    $arrQuestions = $ExamLogic->getQuestions($_SESSION["exm_code"]);
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Exam</title>

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

        <div class="col-md-3">
            <a href="#mdlAddMCQ" data-toggle="modal" class="btn navbar-btn btn-block btn-primary btn-sm">
                <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;Add Multiple Choice Question
            </a>
        </div>

        <div class="col-md-3">
            <a href="#mdlAddStr" data-toggle="modal" class="btn navbar-btn btn-block btn-primary btn-sm">
                <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;Add Structured Question
            </a>
        </div>

        <div class="col-md-6">
            <a href="#mdlDuration" id="btnDurTrigger" data-toggle="modal" class="btn navbar-btn btn-warning pull-right"
               data-toggle="tooltip" data-placement="bottom" title="Click to change exam duration">
                <span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;<?php print ($strExmTyp); ?>
                <em>( <span id="divDuration"> <?php print ($strExmDur); ?> </span> )</em>
            </a>
        </div>

    </div>
        
</div>

</div>
    
<!-- /Header ------------------------------------------------------------------------------------------------------------------ -->


<!-- Content ------------------------------------------------------------------------------------------------------------------ -->
<div class="container custom-container-create">

<div id="divExamPaper">
    
<?php
    
while($question = mysql_fetch_array($arrQuestions))
{
    if($question["qst_type"] == "M")
    {
        // MCQ
        print("
        <div class=\"row\" id=\"divMCQ" . $question["qst_no"] . "\">
        <div class=\"col-md-10 col-md-offset-1\">

            <div class=\"panel panel-default\">

                <div class=\"panel-heading\">
                    <div class=\"row\">
                        <div class=\"col-md-10\">"
                        . $question["qst_question"] .
                        "</div>
                        <div class=\"col-md-2\" align=\"right\">
                            <span class=\"label label-danger\"><small>" . $question["qst_marks"] . "</small></span>&nbsp;&nbsp;&nbsp;
                            <span class=\"btn-link\" onclick=\"showMCQ(" . $_SESSION["exm_code"] . ", " . $question["qst_no"] . ")\">
                                <small><span class=\"glyphicon glyphicon-pencil\">&nbsp;</span></small>
                            </span>
                            <span class=\"btn-link\" onclick=\"deleteMCQ(" . $_SESSION["exm_code"] . ", " . $question["qst_no"] . ")\">
                                <small><span class=\"glyphicon glyphicon-remove\"></span></small>
                            </span>
                        </div>
                    </div>
                </div>

                <div class=\"panel-body\">");

                    
                    // get answers
                    $resAns = $ExamLogic->getMCQAnswer($_SESSION["exm_code"], $question["qst_no"]);
                    
                    $intAnsNo = 1;
                    $data = "";
    
                    while($answer = mysql_fetch_array($resAns))
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
                
                print $data;
                print("</div>

            </div>

        </div>
        </div>");
    }
    elseif($question["qst_type"] == "S")
    {
        $resAns = $ExamLogic->getStrAnswer($_SESSION["exm_code"], $question["qst_no"]);
        // Structured
        print("
        <div class=\"row\" id=\"divStr" . $question["qst_no"] . "\">
        <div class=\"col-md-10 col-md-offset-1\">

            <div class=\"panel panel-default\">

                <div class=\"panel-heading\">
                    <div class=\"row\">
                        <div class=\"col-md-10\">"
                        . $question["qst_question"] .
                        "</div>
                        <div class=\"col-md-2\" align=\"right\">
                            <span class=\"label label-danger\"><small>" . $question["qst_marks"] . "</small></span>&nbsp;&nbsp;&nbsp;
                            <span class=\"btn-link\" onclick=\"showStr(" . $_SESSION["exm_code"] . ", " . $question["qst_no"] . ")\">
                                <small><span class=\"glyphicon glyphicon-pencil\">&nbsp;</span></small>
                            </span>
                            <span class=\"btn-link\" onclick=\"deleteStr(" . $_SESSION["exm_code"] . ", " . $question["qst_no"] . ")\">
                                <small><span class=\"glyphicon glyphicon-remove\"></span></small>
                            </span>
                        </div>
                    </div>
                </div>

                <div class=\"panel-body\">

                    <div class=\"panel-heading alert-info\">"
                    . mysql_result($resAns, 0, "str_answer") .
                    "</div>

                </div>
                </div>

        </div>
        </div>");
    }
                        
}
    
?>
    
</div>
    
</div>

<!-- /Content ----------------------------------------------------------------------------------------------------------------- -->



<!-- Modals ---------------------------------------------------------------- -->


<!-- Change Duration ------------------------------------------------------- -->
<div class="modal fade" id="mdlDuration" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Edit Exam Duration</strong></h5>
            </div>
            
            <div class="modal-body">
                
                <div class="row">
                    <div id="divEditDur" align="center" class="col-md-10 col-md-offset-1"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="row">
                            
                            <div class="col-md-6">
                            <input type="text" class="form-control input-sm" 
                                   placeholder="hrs" id="txtHrs" name="txtHrs" maxlength="1">
                            </div>
                            
                            <div class="col-md-6">
                            <input type="text" class="form-control input-sm" 
                                   placeholder="mins" id="txtMin" name="txtMin" maxlength="2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="#" class="btn btn-default btn-sm" id="btnDurClose" data-dismiss="modal" onclick="clearDuration()">Close</a>
                <a href="#" class="btn btn-primary btn-sm" onclick="changeDuration(<?php print($_SESSION["exm_code"]); ?>)">Change</a>
            </div>
        </div>
    </div>
</div>
<!-- /Change Duration ------------------------------------------------------ -->


<!-- Add MCQ --------------------------------------------------------------- -->
<div class="modal fade" id="mdlAddMCQ" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Add Multiple Choice Question</strong></h5>
            </div>
            
            <div class="modal-body">
                
                <div calass="container">
                    
                    <div class="row">
                        <div id="divAMCQMsg" align="center" class="col-md-6 col-md-offset-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <textarea id="txtMQst" rows="3" class="form-control input-sm"
                                      placeholder="Add the Question"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="input-group">
                                <span class="input-group-addon"><input type="radio" id="rdoMCQAns" name="rdoMCQAns" value="1"></span>
                                <textarea rows="1" id="txtMAns1" placeholder="Answer 1" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="input-group">
                                <span class="input-group-addon"><input type="radio" id="rdoMCQAns" name="rdoMCQAns" value="2"></span>
                                <textarea rows="1" id="txtMAns2" placeholder="Answer 2" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="input-group">
                                <span class="input-group-addon"><input type="radio" id="rdoMCQAns" name="rdoMCQAns" value="3"></span>
                                <textarea rows="1" id="txtMAns3" placeholder="Answer 3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="input-group">
                                <span class="input-group-addon"><input type="radio" id="rdoMCQAns" name="rdoMCQAns" value="4"></span>
                                <textarea rows="1" id="txtMAns4" placeholder="Answer 4" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2 col-md-offset-9">
                            <input type="text" id="txtMCQMarks" class="form-control input-sm custom-input-info" 
                                   placeholder="Marks" maxlength="3">
                        </div>
                    </div>
                
                </div>
                
            </div>
            
            <div class="modal-footer">
                
                <button type="button" class="btn btn-default btn-sm" id="btnAMCQClose" data-dismiss="modal" onclick="clearAMCQ()">Close</button>
                
                <button type="button" class="btn btn-primary btn-sm" id="btnMCQSave" data-loading-text="Saving Question..."
                        onclick="addMCQ(<?php print($_SESSION["exm_code"]); ?>)">Save Question</button>
            
            </div>
        </div>
    </div>
</div>
<!-- /Add MCQ -------------------------------------------------------------- -->


<!-- Add Structured -------------------------------------------------------- -->
<div class="modal fade" id="mdlAddStr" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Add Structured Question</strong></h5>
            </div>
            
            <div class="modal-body">
                
                <div calass="container">
                    
                    <div class="row">
                        <div id="divAStrMsg" align="center" class="col-md-6 col-md-offset-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <textarea id="txtSQst" rows="3" class="form-control input-sm"
                                      placeholder="Add the Question"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <textarea id="txtSAns" rows="3" class="form-control input-sm"
                                      placeholder="Add the Answer"></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        &nbsp;
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2 col-md-offset-9">
                            <input type="text" id="txtStrMarks" class="form-control input-sm custom-input-info" 
                                   placeholder="Marks" maxlength="3">
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
            <div class="modal-footer">
                <a href="#" class="btn btn-default btn-sm" id="btnAStrClose" data-dismiss="modal" onclick="clearAStr()">Close</a>
                <a href="#" class="btn btn-primary btn-sm"  id="btnStrSave" data-loading-text="Saving Question..."
                   onclick="addStr(<?php print($_SESSION["exm_code"]); ?>)">Add Question</a>
            </div>
        </div>
    </div>
</div>
<!-- /Add Structured ------------------------------------------------------- -->


<!-- Edit Question --------------------------------------------------------- -->
<div class="modal fade" id="mdlEditQst" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Edit Question</strong></h5>
            </div>
            
      <div id="divEditQst">
          
            
            
     </div>
            
        </div>
    </div>
</div>
<!-- /Edit Question -------------------------------------------------------- -->

<!-- /Modals --------------------------------------------------------------- -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/create.js"></script>
  </body>
</html>