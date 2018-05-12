<?php

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/EvalLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________

$UserLogic = new UserLogic();
$Examlogic = new ExamLogic();
$EvalLogic = new EvalLogic();
$CommonLogic = new CommonLogic();

$arrInsSbj = $UserLogic->getSubjectByInstructor($_SESSION["usrCode"]);
$arrCtdExams = $Examlogic->getCreatedExams($_SESSION["usrCode"]);
$arrPubExams = $Examlogic->getPublishedExams($_SESSION["usrCode"]);
$arrCmpExams = $Examlogic->getCompletedExams($_SESSION["usrCode"]);
$arrExmStat = $EvalLogic->getExamGradeSummery($_SESSION["usrCode"]);
?>

   <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <P>&nbsp;</P>
       </div>
   </div>

    <div class="row">
       <div class="col-md-2">
           <a href="#mdlCreateExam" data-toggle="modal" class="btn btn-block btn-md btn-primary">
               <span class="glyphicon glyphicon-file"></span>
               &nbsp;New Exam
           </a>
       </div>
   </div>

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
              <a href="#divCreated" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;&nbsp;Created
              </a>
          </li>
          
          <li>
              <a href="#divPublished" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-flag"></span>&nbsp;&nbsp;&nbsp;Published
              </a>
          </li>
          
          <li>
              <a href="#divCompleted" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-saved"></span>&nbsp;&nbsp;&nbsp;Completed
              </a>
          </li>
          
          <li>
              <a href="#divStat" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;&nbsp;Statistics
              </a>
          </li>
          
        </ul>
    </div>
</div>
<!-- /Tabs ----------------------------------------------------------------- -->


<!-- Tab panes ------------------------------------------------------------- -->

<div class="tab-content">
    
    <div class="tab-pane active" id="divCreated">
    
    <!-- Created Exams --------------------------------------------------------- -->
    
    <div class="row">
        &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12 custom-page-header">
            <h4>Created Exams</h4>
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

                if(mysql_num_rows($arrCtdExams))
                {
                    $strExm = "";

                    while($row = mysql_fetch_array($arrCtdExams))
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

                        $data .=
                        "<tr id=\"trCtdExm" . $row["exm_no"] . "\">
                          <td width=\"70%\">" . $row["exm_name"] . "&nbsp;&nbsp;&nbsp;$strExm
                          &nbsp;&nbsp;&nbsp;<small><em>(" . $row["crs_name"] . ")</em></small></td>
                        
                          <td width=\"6%\">";
                          
                        
                        if($row["exm_questions"] > 0)
                        {
                          $data.= "<span class=\"label label-success pull-right\" title=\"Questions\">" . $row["exm_questions"] . "</span>";
                        }   
                          
                          
                        $data .= "</td>

                          <td width=\"8%\"><a href=\"#\" 
                          class=\"btn btn-block btn-xs btn-default\"
                          onclick=\"editExam_Click('" . $row["exm_no"] . "')\">Edit</a></td>

                          <td width=\"8%\" id=\"divDelEx" . $row["exm_no"] . "\"><a href=\"#\" 
                          class=\"btn btn-block btn-xs btn-default\"
                          onclick=\"deleteExam_Click('" . $row["exm_no"] . "')\">Delete</a></td>

                          <td width=\"8%\" id=\"divPubEx" . $row["exm_no"] . "\">";
                          
                        if($row["exm_questions"] > 0)
                        {
                          $data .= "<a href=\"#\" 
                          class=\"btn btn-block btn-xs btn-primary\"
                          onclick=\"publishExam_Click('" . $row["exm_no"] . "')\">Publish</a>";
                        }
                        else
                        {
                            $data .= "<a href=\"#\" 
                            class=\"btn btn-block btn-xs btn-primary\" disabled>
                            Publish
                            </a>";
                        }
                              
                          $data .= "</td>
                        </tr>";
                    }
                }
                else
                {
                    $data = "<div align=\"center\"><span class=\"label label-default\">&nbsp;&nbsp;&nbsp;No Data Available&nbsp;&nbsp;&nbsp;</span></div>";
                }

                print($data);

            ?>
            </table>
        </div>
    </div>
    
    <!-- /Created Exams -------------------------------------------------------- -->

    </div>
    
    
    <div class="tab-pane" id="divPublished">
    <!-- Published Exams ------------------------------------------------------- -->
    
    <div class="row">
        &nbsp;
    </div>
    
    <div class="row">
       <div class="col-md-12 custom-page-header">
           <h4>Published Exams</h4>
       </div>
    </div>

    <div class="row">
       &nbsp;
    </div>

    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-hover">

                <?php

                $data = "<tr class=\"active\">
                            <th width=\"72%\"></th>
                            <th width=\"8%\"><small>Published On</small></th>
                            <th width=\"8%\"><small>&nbsp;&nbsp;&nbsp;Due On</small></th>
                            <th width=\"7%\"></th>
                            <th width=\"5%\"></th>
                          </tr>";

                if(mysql_num_rows($arrPubExams))
                {
                    $strExm = "";

                    while($row = mysql_fetch_array($arrPubExams))
                    {
						$strDuration = $CommonLogic->createTimeString($row["exm_duration"]);
						
                        if($row["exm_type"] == "Q")
                        {
                            $strExm = "<span class=\"label label-default\">Quiz ($strDuration)</span>";
                        }
                        elseif($row["exm_type"] == "M")
                        {
                            $strExm = "<span class=\"label label-warning\">Mid Semester ($strDuration)</span>";
                        }
                        elseif($row["exm_type"] == "E")
                        {
                            $strExm = "<span class=\"label label-danger\">End Semester ($strDuration)</span>";
                        }

                        $data .=
                        "<tr id=\"trPubExm" . $row["exm_no"] . "\">
                            <td width=\"72%\">" . $row["exm_name"] . "&nbsp;&nbsp;&nbsp;$strExm
                            &nbsp;&nbsp;&nbsp;<small><em>(" . $row["crs_name"] . ")</em></small></td>
                                
                            <td width=\"8%\"><em>" . $row["exm_pub_date"] . "</em></td>
                            <td width=\"8%\"><em>" . $row["exm_due_date"] . "</em></td>

                            <td width=\"7%\" id=\"divCancelExm" . $row["exm_no"] . "\"><a href=\"#\" 
                            class=\"btn btn-block btn-xs btn-default\"
                            onclick=\"cancelExam_Click('" . $row["exm_no"] . "')\">Cancel</a></td>

                            <td width=\"5%\"><span class=\"label label-success\" title=\"" . $row["exm_taken"] . " Students taken exam\">"
                                . $row["exm_taken"] . "</span></td>
                          </tr>";
                    }
                }
                else
                {
                    $data = "<div align=\"center\"><span class=\"label label-default\">"
                            . "&nbsp;&nbsp;&nbsp;No Data Available&nbsp;&nbsp;&nbsp;</span></div>";
                }

                print($data);

                ?>

            </table>
        </div>
    </div>

    <!-- /Published Exams ------------------------------------------------------ -->
        
    </div>

    
    <div class="tab-pane" id="divCompleted">
    <!-- Completed Exams ------------------------------------------------------- -->

    <div class="row">
       &nbsp;
    </div>
    
    <div class="row">
       <div class="col-md-12 custom-page-header">
           <h4>Completed Exams</h4>
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

                if(mysql_num_rows($arrCmpExams) > 0)
                {
                    $strExm = "";

                    while($row = mysql_fetch_array($arrCmpExams))
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
                        
                        // show a label for aborted exams
                        $strAborted = "";
                        
                        if($row["exm_status"] == "A")
                        {
                            $strAborted = "<span class=\"label label-danger\">Cancelled</span>";
                        }
                        else
                        {
                            $strAborted = "";
                        }

                        $data .=
                        "<tr>
                            <td width=\"85%\">" . $row["exm_name"] . "&nbsp;&nbsp;&nbsp;$strExm"
                                . "&nbsp;&nbsp;&nbsp;<small><em>(" . $row["crs_name"] . ")</em></small>"
                                . "&nbsp;&nbsp;&nbsp;$strAborted
                            </td>

                            <td width=\"10%\"><a href=\"#\" 
                            class=\"btn btn-block btn-xs btn-primary\"
                            onclick=\"evalExam_Click('" . $row["exm_no"] . "')\">Evaluate</a></td>

                            <td width=\"5%\"><span class=\"label label-success\" title=\"" . $row["exm_taken"] . " Students have taken this exam\">" 
                                . $row["exm_taken"] . "</span>
                            </td>
                          </tr>";
                    }
                }
                else
                {
                    $data = "<div align=\"center\"><span class=\"label label-default\">"
                            . "&nbsp;&nbsp;&nbsp;No Data Available&nbsp;&nbsp;&nbsp;</span></div>";
                }

                print($data);

                ?>

            </table>
        </div>
    </div>

    <!-- /Completed Exams ------------------------------------------------------ -->        
        
    </div>
    
    
    <div class="tab-pane" id="divStat">
        
    <!-- Statistics ------------------------------------------------------------ -->
    
    <div class="row">
    &nbsp;
    </div>

    <div class="row">
        <div class="col-md-12 custom-page-header">
           <h4>Statistics</h4>
        </div>
    </div>

    <div class="row">
    &nbsp;
    </div>

    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-hover">
				
			<?php	
				
				$data = "<tr class=\"active\">
                            <th width=\"55%\"></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-danger\"><strong>C-</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-warning\"><strong>C</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-warning\"><strong>C+</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-success\"><strong>B-</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-success\"><strong>B</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-success\"><strong>B+</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-primary\"><strong>A-</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-primary\"><strong>A</strong></span></div></th>
                            <th width=\"5%\"><div align=\"center\"><span class=\"btn btn-xs btn-block btn-info\"><strong>A+</strong></span></div></th>
                          </tr>";

                if(mysql_num_rows($arrExmStat) > 0)
                {
                    $strExm = "";

                    while($row = mysql_fetch_array($arrExmStat))
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
                        
                        // create grade labels
                        $strCMinus = ($row["tke_c_minus"] != 0) ? "<span class=\"btn btn-block btn-xs btn-danger\">" . $row["tke_c_minus"] . "</span>" : "-";
                        $strC = ($row["tke_c"] != 0) ? "<span class=\"btn btn-block btn-xs btn-warning\">" . $row["tke_c"] . "</span>" : "-";
                        $strCPlus = ($row["tke_c_plus"] != 0) ? "<span class=\"btn btn-block btn-xs btn-warning\">" . $row["tke_c_plus"] . "</span>" : "-";
                        $strBMinus = ($row["tke_b_minus"] != 0) ? "<span class=\"btn btn-block btn-xs btn-success\">" . $row["tke_b_minus"] . "</span>" : "-";
                        $strB = ($row["tke_b"] != 0) ? "<span class=\"btn btn-block btn-xs btn-success\">" . $row["tke_b"] . "</span>" : "-";
                        $strBPlus = ($row["tke_b_plus"] != 0) ? "<span class=\"btn btn-block btn-xs btn-success\">" . $row["tke_b_plus"] . "</span>" : "-";
                        $strAMinus = ($row["tke_a_minus"] != 0) ? "<span class=\"btn btn-block btn-xs btn-primary\">" . $row["tke_a_minus"] . "</span>" : "-";
                        $strA = ($row["tke_a"] != 0) ? "<span class=\"btn btn-block btn-xs btn-primary\">" . $row["tke_a"] . "</span>" : "-";
                        $strAPlus = ($row["tke_a_plus"] != 0) ? "<span class=\"btn btn-block btn-xs btn-info\">" . $row["tke_a_plus"] . "</span>" : "-";
                        
                        
                        $data .= "
                        <tr>
							<td width=\"55%\">" 
							  . $row["exm_name"] . "&nbsp;&nbsp;&nbsp;$strExm
							  &nbsp;&nbsp;&nbsp;<small><em>(" . $row["crs_name"] . ")</em></small>
							</td>
							
                            <td width=\"5%\"><div align=\"center\">$strCMinus</div></td>
                            <td width=\"5%\"><div align=\"center\">$strC</div></td>
                            <td width=\"5%\"><div align=\"center\">$strCPlus</div></td>
                            <td width=\"5%\"><div align=\"center\">$strBMinus</div></td>
                            <td width=\"5%\"><div align=\"center\">$strB</div></td>
                            <td width=\"5%\"><div align=\"center\">$strBPlus</div></td>
                            <td width=\"5%\"><div align=\"center\">$strAMinus</div></td>
                            <td width=\"5%\"><div align=\"center\">$strA</div></td>
                            <td width=\"5%\"><div align=\"center\">$strAPlus</div></td>
						</tr>";
                        
					}
				}
				else
                {
                    $data = "<div align=\"center\"><span class=\"label label-default\">"
                            . "&nbsp;&nbsp;&nbsp;No Data Available&nbsp;&nbsp;&nbsp;</span></div>";
                }

                print($data);
                
                
             ?>   
                
            </table>
        </div>
    </div>

    <!-- /Statistics ----------------------------------------------------------- -->
        
        
    </div>
</div>

<!-- /Tab panes ------------------------------------------------------------ -->



<!-- modals -->

<!-- Create Exam ----------------------------------------------------------- -->
<div class="modal fade" id="mdlCreateExam" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Create New Exam</strong></h5>
            </div>
            
            <div class="modal-body">
                
                <div class="row">
                    <div id="divCreateExamMsg" align="center" class="col-md-8 col-md-offset-2"></div>
                </div>
                
            <form method="post" action="home.link.php" id="frmCreateExam" name="frmCreateExam">
                <div class="row">
                <!-- Subjects -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="drpInsSbj"><small>Subject </small></label>
                </div>

                <div class="col-md-9 form-group">
                     <select class="form-control input-sm" id="drpInsSbj" name="drpInsSbj" 
                             onchange="drpInsSbj_Change(this.options[this.selectedIndex].value, '<?php print($_SESSION["usrCode"]); ?>')">
                            <option value ="-1">-- Select a  Subject --</option>
                            <?php
                                while($subject = mysql_fetch_array($arrInsSbj))
                                {
                                    print('<option value="' . $subject["sbj_code"] . '">' . $subject["sbj_name"] . '</option>');
                                }
                            ?>  
                     </select>
                    
                </div>
                <!-- /Subjects ------------------------------------------------------------------------- -->
                </div>

                <div class="row">
                <!-- Course -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="drpCourse"><small>Course </small></label>
                </div>

                <div class="col-md-9 form-group">
                    
                    <div id="divDrpCourse">
                        Select a Subject from above
                    </div>
                </div>
                
                <!-- /Course ------------------------------------------------------------------------- -->
                </div>

                
                <div class="row">
                <!-- Exam Type -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="rdoExam"><small>Exam Type </small></label>
                </div>

                <div class="col-md-9 form-group">
                    
                    <div id="divRdoExamType">
                        Select a Course from above
                    </div>
                </div>
                
                <!-- /Exam Type ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Duration -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label><small>Exam Duration </small></label>
                </div>

                <div class="col-md-2 form-group">
                    <input type="text" class="form-control input-sm" id="txtDHrs" name="txtDHrs" placeholder="hrs" maxlength="1">
                </div>
                
                <div class="col-md-1 form-group">
                    <strong>:</strong>
                </div>
                
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control input-sm" id="txtDMin" name="txtDMin" placeholder="mins" maxlength="2">
                </div>
                
                <div class="col-md-4 form-group">
                    &nbsp;
                </div>
                <!-- /Duration ------------------------------------------------------------------------- -->
                </div>
                
                <input type="hidden" name="hdnType" value="NewExam">
            </form>
                
            </div>
            
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal" onclick="createExmCancel_Click()"><small>Cancel</small></a>
                <a class="btn btn-primary" onclick="createExam_Click()"><small>Create</small></a>
            </div>
        </div>
    </div>
</div>

<!-- Get Publish Date ------------------------------------------------------ -->
<div class="modal fade" id="mdlPubDate" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Publish Date</strong></h5>
            </div>
            
            <div class="modal-body">
                
                <div class="row">
                    <div id="divPubDteMsg" align="center" class="col-md-10 col-md-offset-1"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <input type="hidden" id="hdnPubDExmNo">
                            <div class="col-md-4">
                            <input type="number" class="form-control input-sm" 
                                   placeholder="yyyy" id="txtPYear" name="txtPYear" maxlength="4" min="2014" max="9999">
                            </div>
                            
                            <div class="col-md-4">
                            <input type="number" class="form-control input-sm" 
                                   placeholder="mm" id="txtPMon" name="txtPMon" maxlength="2" min="1" max="12">
                            </div>
                            
                            <div class="col-md-4">
                                <input type="number" class="form-control input-sm" 
                                   placeholder="dd" id="txtPDte" name="txtPDte" maxlength="2" min="1" max="31">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="#" class="btn btn-default btn-sm" id="btnPubDClose" data-dismiss="modal" onclick="clearPubDate()">Close</a>
                <a href="#" class="btn btn-primary btn-sm" onclick="publishExam()">Publish Exam</a>
            </div>
        </div>
    </div>
</div>
