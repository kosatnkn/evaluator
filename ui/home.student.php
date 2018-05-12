<?php

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/EvalLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________

$Examlogic = new ExamLogic();
$EvalLogic = new EvalLogic();
$CommonLogic = new CommonLogic();

// do this to avoid loading exams that are not suppose to load
$_SESSION["exm_code"] = "";

$arrExamsToTake = $Examlogic->getTakingExams($_SESSION["usrCode"]);
$arrExamResults = $EvalLogic->getEvaluatedExams($_SESSION["usrCode"]);

?>


    <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <P>&nbsp;</P>
       </div>
    </div>‍

    <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <P>&nbsp;</P>
       </div>
    </div>


<!-- Tabs ------------------------------------------------------------------ -->
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            
          <li class="active">
              <a href="#divExams" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-bookmark"></span>&nbsp;&nbsp;&nbsp;Exams
              </a>
          </li>
          
          <li>
              <a href="#divGrades" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;&nbsp;Grades
              </a>
          </li>
          
        </ul>
    </div>
</div>
<!-- /Tabs ----------------------------------------------------------------- -->


<!-- Tab panes ------------------------------------------------------------- -->

<div class="tab-content">
    
    <div class="tab-pane active" id="divExams">
    
    <!-- Exams ------------------------------------------------------------- -->

    <div class="row">
        &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12 custom-page-header">
            <h4>Available Exams</h4>
        </div>
    </div>
    
    <div class="row">
    &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-hover">
                
                <?php
                
                $data = "";
                
                if(mysql_num_rows($arrExamsToTake) > 0)
                {   
					$data = "<tr class=\"active\">
                            <th width=\"80%\"></th>
                            <th width=\"10%\"><div align=\"center\"><small>Due On</small></div></th>
                            <th width=\"10%\"><small></th>
                          </tr>";
                          
                    $strExm = "";

                    while($row = mysql_fetch_array($arrExamsToTake))
                    {
                        $strDuration = $CommonLogic->createTimeString($row["exm_duration"]);
                        
                        if($row["exm_type"] == "Q")
                        {
                            $strExm = "<span class=\"label label-default\">Quiz (" . $strDuration . ")</span>";
                        }
                        elseif($row["exm_type"] == "M")
                        {
                            $strExm = "<span class=\"label label-warning\">Mid Semester (" . $strDuration . ")</span>";
                        }
                        elseif($row["exm_type"] == "E")
                        {
                            $strExm = "<span class=\"label label-danger\">End Semester (" . $strDuration . ")</span>";
                        }
                        
                        $intDaysToDue = $CommonLogic->getDateDiff($row["exm_due_date"], date('Y-m-d'));
                        $strDueDate = "";
                        
                        // due date
                        if($intDaysToDue == 1)
                        {
                            $strDueDate = "<span class=\"label label-danger\">Tomorrow</span>";
                        }
                        elseif($intDaysToDue > 1 && $intDaysToDue < 5)
                        {
                            $strDueDate = "<span class=\"label label-warning\">" . $row["exm_due_date"] . "</span>";
                        }
                        else
                        {
                            $strDueDate = $row["exm_due_date"];
                        }

                        $data .= "
                            <tr>
                            <td width=\"80%\">" . $row["exm_name"] . "&nbsp;&nbsp;&nbsp;$strExm
                                &nbsp;&nbsp;&nbsp;<small><em>(" . $row["crs_name"] . ")</em></small>
								<input type=\"hidden\" id=\"hdnSEName" . $row["exm_no"] . "\" value=\"" . $row["exm_name"] . "\">
								<input type=\"hidden\" id=\"hdnSECrs" . $row["exm_no"] . "\" value=\"" . $row["crs_name"] . "\">
								<input type=\"hidden\" id=\"hdnSEDur" . $row["exm_no"] . "\" value=\"$strDuration\">
								<input type=\"hidden\" id=\"hdnSEType" . $row["exm_no"] . "\" value=\"" . $row["exm_type"] . "\">
                            </td>";
                            
                        
                        if($row["exm_is_taken"] == "0") // exam not taken
                        {    
							$data .= 
								"<td width=\"10%\">
								<div id=\"divExmDue" . $row["exm_no"] . "\" align=\"center\">$strDueDate</div>
								</td>
								<td width=\"10%\">
									<a href=\"#\" class=\"btn btn-block btn-xs btn-primary\"
									onclick=\"btnTakeExm_Click(" . $row["exm_no"] . ")\">Take Exam</a>
								</td>
								</tr>";
						}
						else
						{
							$data .= 
								"<td width=\"10%\">
									&nbsp;
								</td>
								<td width=\"10%\">
									<a href=\"#\" class=\"btn btn-block btn-xs btn-success\" disabled>Exam Taken</a>
								</td>
								</tr>";
						}
                    }
                }
                else
                {
					$data = "<div align=\"center\">
					<span class=\"label label-default\">
					&nbsp;&nbsp;&nbsp;No Data Available&nbsp;&nbsp;&nbsp;
					</span>
					</div>";
				}
                
                print($data);
                
                ?>
                
            </table>
        </div>
   </div>

    <!-- /Exams ------------------------------------------------------------ -->

</div>
   
    <div class="tab-pane" id="divGrades">
    
    <!-- Grades ------------------------------------------------------------ -->

    <div class="row">
        &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12 custom-page-header">
            <h4>Grades</h4>
        </div>
    </div>
    
    <div class="row">
    &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-hover">
				
				<?php
				
				$data = "";
                
                if(mysql_num_rows($arrExamResults) > 0)
                {  
					$strExm = "";

                    while($row = mysql_fetch_array($arrExamResults))
                    {
                        if($row["exm_type"] == "Q")
                        {
                            $strExm = "<span class=\"label label-default\">Quiz</span>";
                        }
                        elseif($row["exm_type"] == "M")
                        {
                            $strExm = "<span class=\"label label-warning\">Mid Semester</span>";
                        }
                        elseif($row["exm_type"] == "E")
                        {
                            $strExm = "<span class=\"label label-danger\">End Semester</span>";
                        }
                        
                    $data .=
						"<tr>
						  <td width=\"90%\">" 
							  . $row["exm_name"] . "&nbsp;&nbsp;&nbsp;$strExm
							  &nbsp;&nbsp;&nbsp;<small><em>(" . $row["crs_name"] . ")</em></small>
						  </td>
						  
						<td width=\"10%\">";
					
					$data .= $CommonLogic->drawGrade($row["tke_percentage"]);
					
					$data .= "</td>
						</tr>";
						
					}
				}
				else
                {
					$data = "<div align=\"center\">
					<span class=\"label label-default\">
					&nbsp;&nbsp;&nbsp;No Data Available&nbsp;&nbsp;&nbsp;
					</span>
					</div>";
				}
                
                print($data);
				
                ?>
                
            </table>
        </div>
   </div>


    <!-- /Grades ----------------------------------------------------------- -->

</div>
    
</div>

<!-- /Tab panes ------------------------------------------------------------ -->


<!-- Modals -->
<div class="modal fade" id="mdlStartEx" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5>Start Exam</h5>
            </div>
			
            <div class="modal-body">
			
				<div class="row">     
					<div align="center" class="col-md-10 col-md-offset-1 alert alert-warning">
						<p><h4>You are about to start an Exam Answering session!</h4></p>
						<p>Once you've started you have to complete the exam. You cannot go back and come again.</p>
						<p>Please make sure that you have the free time needed for the exam 
						and a decent Internet connection before you start.</p>
					</div>
                </div>
				
                <div class="row">     
                    <div align="center" class="col-md-10 col-md-offset-1 alert alert-info">
						<div id="divStartExDetails">Exam Details</div>
					</div>
                </div>
            
                <div class="row">     
					<div class="col-md-10 col-md-offset-1">
						<div class="col-md-6">
							<button type="button" class="btn btn-block btn-lg btn-default" data-dismiss="modal"><h4>Cancel</h4></button>
						</div>
						<div class="col-md-6">
							<form method="post" action="home.link.php">
								<input type="hidden" id="hdnType" name="hdnType" value="StartExam">
								<input type="hidden" id="hdnExmNo" name="hdnExmNo" value="">
							
								<button type="submit" class="btn btn-block btn-lg btn-primary"><h4>Start</h4></button>
							</form>
						</div>
					</div>
                </div>
            
            </div>
			
        </div>
    </div>
</div>
