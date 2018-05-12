<?php

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

$UserLogic = new UserLogic();

$strContent = "";

if($_SESSION["usrType"] == "S")
{
    $arrUser = $UserLogic->getStudent($_SESSION["usrCode"]);
    $resSubjects = $UserLogic->getStdCourseSubjects($_SESSION["usrCode"]);
    
    $arrField = $UserLogic->getField();

    $strCourse = "";
    $strSubjects1 = "<strong>Semester I</strong><br>";
    $strSubjects2 = "<strong>Semester II</strong><br>";

    if(mysql_num_rows($resSubjects) > 0)
    {
        while($subject = mysql_fetch_array($resSubjects))
        {
            $strCourse = $subject["crs_name"];

            if($subject["csb_semester"] == 1)
            {
                $strSubjects1 .= $subject["sbj_name"] . "<br>";
            }
            elseif ($subject["csb_semester"] == 2)
            {
                $strSubjects2 .= $subject["sbj_name"] . "<br>";
            }
        }
    }
    else
    {
        $strCourse = "Sorry! No data is available";
        $strSubjects1 .= "Sorry! No data is available";
        $strSubjects2 .= "Sorry! No data is available";
    }
    
    // create content
    
    $strContent = "<br><h3>" . $arrUser["usr_fname"] . " " . $arrUser["usr_mname"] . " " . $arrUser["usr_lname"] . "</h3>
           <br>
           <p>ID No:&nbsp;&nbsp;<strong>" . $arrUser["usr_nic"] . "</strong></p>
           <p>REG No:&nbsp;<strong>" . $arrUser["usr_reg_no"] . "</strong></p>
           <br>
           <p>Gender:&nbsp;<strong>" . $arrUser["usr_gender"] . "</strong></p>
           <p>Email:&nbsp;&nbsp;<strong>" . $arrUser["usr_email"] . "</strong></p>
           <br><br>
           <h3>$strCourse</h3>
           <br>
           $strSubjects1
           <br>
           $strSubjects2";
}
else
{
    $arrUser = $UserLogic->getInstructor($_SESSION["usrCode"]);
    $resSubjects = $UserLogic->getInsDepartmentSubject($_SESSION["usrCode"]);
    
    $arrDepartment = $UserLogic->getField();
    
    $strSubjects = "";
    
    if(mysql_num_rows($resSubjects) > 0)
    {
        $sbjCode = "";
        
        while($subject = mysql_fetch_array($resSubjects))
        {
            if($sbjCode != $subject["sbj_code"])
            {
                $sbjCode = $subject["sbj_code"];
                $strSubjects .= "</ul><strong>" . $subject["sbj_name"] ."</strong><br><ul>";
            }
            
            $strSubjects .= "<li>" . $subject["crs_name"] . "</li>";
        }
    }
    else
    {
        $strSubjects .= "Sorry! No data is available";
    }
    
    // create content
    
    $strContent = "<br><h3>" . $arrUser["usr_fname"] . " " . $arrUser["usr_mname"] . " " . $arrUser["usr_lname"] . "</h3>
           <br>
           <p>ID No:&nbsp;&nbsp;<strong>" . $arrUser["usr_nic"] . "</strong></p>
           <br>
           <p>Gender:&nbsp;<strong>" . $arrUser["usr_gender"] . "</strong></p>
           <p>Email:&nbsp;&nbsp;<strong>" . $arrUser["usr_email"] . "</strong></p>
           <br><br>
           <h3>Subjects</h3>
           <br>
           $strSubjects";
}


?>

<div class="container">
   <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <P>&nbsp;</P>
       </div>
   </div>
    
   <h3 align="center"> Welcome! </h3> 
   
   <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <P>&nbsp;</P>
       </div>
   </div>
   
   <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <p align="center" class="alert alert-info">You will have to wait until your account is approved</p>
       </div>
   </div>
   
   <div class="row">
        <div class="col-md-2 col-md-offset-3">
            <a href="#mdlDetails" data-toggle="modal" class="btn btn-block btn-primary"><small>Edit Registration Info.</small></a>
       </div>
       
       <!-- change here depending on the user type -->
       <div class="col-md-2">
           <a href="#<?php $_SESSION["usrType"] == "S" ? print'mdlCourse' : print 'mdlSubject'?>" 
              data-toggle="modal" class="btn btn-block btn-primary"><small>Edit Course Details</small></a>
       </div>
       
       <div class="col-md-2">
           <a href="#mdlPwd" data-toggle="modal" class="btn btn-block btn-primary"><small>Change Password</small></a>
       </div>
   </div>
   
   <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <?php
                print($strContent);
           ?>
       </div>
   </div>
</div>



<!-- modals -->

<div class="modal fade" id="mdlDetails" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Edit Details</strong></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="divDtlMsg" align="center" class="col-md-8 col-md-offset-2"></div>
                </div>
                
                <div class="row">
                <!-- First Name -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="txtFname"><small>First Name </small></label>
                </div>

                <div class="col-md-9 form-group">
                    <input type="text" class="form-control input-sm" id="txtFname" name="txtFname" value="<?php print($arrUser["usr_fname"]) ?>">
                </div>
                <!-- /First Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Middle Name -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="txtMname"><small>Middle Name </small></label>
                </div>

                <div class="col-md-9 form-group">
                    <input type="text" class="form-control input-sm" id="txtMname" name="txtMname" value="<?php print($arrUser["usr_mname"]) ?>">
                </div>
                <!-- /Middle Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Last Name -------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="txtLname"><small>Last Name </small></label>
                </div>

                <div class="col-md-9 form-group">
                    <input type="text" class="form-control input-sm" id="txtLname" name="txtLname" value="<?php print($arrUser["usr_lname"]) ?>">
                </div>
                <!-- /Last Name ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Gender --------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="rdoGender"><small>Gender </small></label>
                </div>

                <div class="col-md-9 form-group">
                <div class="radio-inline">
                    <label>
                        <input type="radio" name="rdoGender" id="rdoGender" value="M" <?php $arrUser["usr_gender"] == "Male" ? print('checked="checked"') : print(''); ?> >
                        Male
                    </label>
                </div>
                <div class="radio-inline">
                    <label>
                        <input type="radio" name="rdoGender" id="rdoGender" value="F" <?php $arrUser["usr_gender"] == "Female" ? print('checked="checked"') : print(''); ?>>
                        Female
                    </label>
                </div>
                </div>
                <!-- /Gender ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Email --------------------------------------------------------------------------- -->
                <div class="col-md-3 form-group">
                    <label for="txtEmail"><small>Email </small></label>
                </div>

                <div class="col-md-9 form-group">
                    <input type="text" class="form-control input-sm" id="txtEmail" name="txtEmail" value="<?php print($arrUser["usr_email"]) ?>">
                </div>
                <!-- /Email ------------------------------------------------------------------------- -->
                </div>
                
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal" onclick="reloadPage()"><small>Close</small></a>
                <a class="btn btn-primary" onclick="editDetails('<?php print($_SESSION["usrCode"]); ?>')"><small>Save Details</small></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlCourse" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5><strong>Edit Course</strong></h5>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div id="divCrsMsg" align="center" class="col-md-8 col-md-offset-2"></div>
                </div>
                
                <div class="row">
                <!-- Field -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpField"><small>Field </small></label>
                </div>

                <div class="col-md-6 form-group">
                    
                        <select class="form-control input-sm" id="drpField" name="drpField" onchange="drpField_Change(this.options[this.selectedIndex].value)">
                            <option>-- Select a  Field --</option>
                            <?php
                                while($field = mysql_fetch_array($arrField))
                                {
                                    print('<option value="' . $field["fld_code"] . '">' . $field["fld_name"] . '</option>');
                                }
                            ?>  
                        </select>

                </div>
                
                <div class="col-md-4 form-group">&nbsp;</div>
                <!-- /Field ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Degree -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpDegree"><small>Degree </small></label>
                </div>

                <div class="col-md-6 form-group">
                    <div id="divDrpDegree">
                        
                        <!-- drpDegree loads here -->
                        <select class="form-control input-sm" id="drpDegree" name="drpDegree" disabled="disabled">
                            <option>-- Select a  Degree --</option>
                        </select>

                    </div>
                </div>
                
                <div class="col-md-4 form-group">&nbsp;</div>
                <!-- /Degree ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">
                <!-- Subjects -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                    <label><small>Subjects </small></label>
                </div>

                <div class="col-md-10 form-group">
   
                    <div class="row">

                        <div id="divSubjects">
                            
                            <div class="col-md-8 form-group"
                                <p>Select a Degree from above to view Subjects</p>
                            </div>

                        </div>
                    </div>
                    
                </div>
                <!-- /Subjects ------------------------------------------------------------------------- -->
                </div>
                
            </div>
            
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal" onclick="reloadPage()"><small>Close</small></a>
                <a class="btn btn-primary" onclick="editCourse('<?php print($_SESSION["usrCode"]); ?>')"><small>Save Course</small></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlSubject" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h5>Edit Subject</h5>
            </div>
            <div class="modal-body">
                <div class="row">     
                    <div id="divSbjMsg" align="center" class="col-md-8 col-md-offset-2"></div>
                </div>
            
                <div class="row">     
                <!-- Department -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label for="drpDpmnt"><small>Department </small></label>
                </div>

                <div class="col-md-8 form-group">
                    <div class="form-group">

                        <select class="form-control input-sm" id="drpDpmnt" name="drpDpmnt" onchange="drpDpmnt_Change(this.options[this.selectedIndex].value)">
                            <option value="0">-- Select a Department --</option>
                            <?php
                                while($dep = mysql_fetch_array($arrDepartment))
                                {
                                    print('<option value="' . $dep["fld_code"] . '">' . $dep["fld_name"] . '</option>');
                                }
                            ?>  
                        </select>

                    </div>
                </div>
                <!-- /Department ------------------------------------------------------------------------- -->
                </div>
                
                <div class="row">     
                <!-- Subjects -------------------------------------------------------------------------- -->
                <div class="col-md-2 form-group">
                        <label><small>Subjects </small></label>
                </div>
                
                <div class="col-md-8 form-group">
                    
                    <div class="form-group" id="divInsSub">

                        Select a Department from above to view Subjects

                    </div>
                    
                </div>
                <!-- /Subjects ------------------------------------------------------------------------- -->
                </div>
            
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal" onclick="reloadPage()"><small>Close</small></a>
                <a class="btn btn-primary" onclick="editSubjects('<?php print($_SESSION["usrCode"]); ?>')"><small>Save Subjects</small></a>
            </div>
        </div>
    </div>
</div>
