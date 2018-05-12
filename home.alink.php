<?php

// ajax calls from home.php is handled from here

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
//_____________________________________________________________________________

switch ($_POST["ret_type"]) 
{
    case "v_std": // view student
    $UserLogic = new UserLogic();

    $arrStudent = $UserLogic->getStudent($_POST["id"]);
    $resSubjects = $UserLogic->getStdCourseSubjects($_POST["id"]);

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
        
    $data = "<h3 align=\"center\">" . $arrStudent["usr_fname"] . " " . $arrStudent["usr_mname"] . " " . $arrStudent["usr_lname"] . "</h3>
           <p>ID No:&nbsp;&nbsp;<strong>" . $arrStudent["usr_nic"] . "</strong></p>
           <p>REG No:&nbsp;<strong>" . $arrStudent["usr_reg_no"] . "</strong></p>
           <p>Gender:&nbsp;<strong>" . $arrStudent["usr_gender"] . "</strong></p>
           <p>Email:&nbsp;&nbsp;<strong>" . $arrStudent["usr_email"] . "</strong></p>
           <br>
           <h4>$strCourse</h4>
           <br>
           $strSubjects1
           <br>
           $strSubjects2";

    print $data;

    break;

    case "a_std": // approve student
    $UserLogic = new UserLogic();

    $data;

    if($UserLogic->approveUser($_POST["id"]))
    {
        $data = "true"; //'<a href="#" class="btn btn-block btn-xs btn-success">Approved</a>';
    }
    else
    {
        $data = "false"; //'<a href="#" class="btn btn-block btn-xs btn-danger">Error</a>';
    }

    print $data;

    break;

    case "v_ins": // view instructor
    $UserLogic = new UserLogic();

    $arrInstructor = $UserLogic->getInstructor($_POST["id"]);
    $resSubjects = $UserLogic->getInsDepartmentSubject($_POST["id"]);

    $strSubjects = "";
    
    if(mysql_num_rows($resSubjects) > 0)
    {
        while($subject = mysql_fetch_array($resSubjects))
        {
            $strSubjects .= $subject["sbj_name"] . "<br>";
        }
    }
    else
    {
        $strSubjects .= "Sorry! No data is available";
    }

    $data = "<h3 align=\"center\">" . $arrInstructor["usr_fname"] . " " . $arrInstructor["usr_mname"] . " " . $arrInstructor["usr_lname"] . "</h3>
           <br>
           <p>ID No:&nbsp;&nbsp;<strong>" . $arrInstructor["usr_nic"] . "</strong></p>
           <br>
           <p>Gender:&nbsp;<strong>" . $arrInstructor["usr_gender"] . "</strong></p>
           <p>Email:&nbsp;&nbsp;<strong>" . $arrInstructor["usr_email"] . "</strong></p>
           <br><br>
           <h4>Subjects</h4>
           <br>
           $strSubjects";

    print $data;

    break;
    
    case "a_ins": // approve intructor
        $UserLogic = new UserLogic();
        
        $data;
        
        if($UserLogic->approveUser($_POST["id"]))
        {
            $data = "true"; //'<a href="#" class="btn btn-block btn-xs btn-success">Approved</a>';
        }
        else
        {
            $data = "false"; //'<a href="#" class="btn btn-block btn-xs btn-danger">Error</a>';
        }
        
        print $data;
        
        break;
    
    case "u_pwd":
        $UserLogic = new UserLogic();
        
        if($UserLogic->changePassword($_POST["id"], 
                                        $_POST["opwd"], 
                                        $_POST["npwd"]))
        {
            print("true");
        }
        else
        {
            print("flase");
        }
        
        break;
     
    case "u_usr":
        $UserLogic = new UserLogic();
        
        $arrUser["usr_nic"] = $_POST["id"];
        $arrUser["usr_first_name"] = $_POST["fname"];
        $arrUser["usr_middle_name"] = $_POST["mname"];
        $arrUser["usr_last_name"] = $_POST["lname"];
        $arrUser["usr_gender"] = $_POST["gdr"];
        $arrUser["usr_email"] = $_POST["email"];
                
        if($UserLogic->editUser($arrUser))
        {
            print("true");
        }
        else
        {
            print("flase");
        }
        
        break;
    
    case "v_crs":
        $UserLogic = new UserLogic();
        
        $resSubjects = $UserLogic->getSubjectsByCourse($_POST["crs_code"]);

        $data = "";
        $semester1 = "<div class=\"col-md-6 form-group\">
                                <div class=\"panel panel-default\">
                                    <div class=\"panel-heading\" align=\"center\">
                                        <span>Semester I</span>
                                    </div>
                                    <div class=\"panel-body\">";
                                        
        $semester2 = "<div class=\"col-md-6 form-group\">
                                <div class=\"panel panel-default\">
                                    <div class=\"panel-heading\" align=\"center\">
                                        <span>Semester II</span>
                                    </div>
                                    <div class=\"panel-body\">";
                                        
        
        if(mysql_num_rows($resSubjects) > 0)
        {
            while($subject = mysql_fetch_array($resSubjects))
            {
                if($subject["sbj_semester"] == 1)
                {
                    if($subject["sbj_compulsary"])
                    {
                        $semester1 .= "<input type=\"checkbox\" name=\"chgSubject\" checked=\"checked\" disabled=\"disabled\""
                                . "value=\"" . $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . 
                                $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                    else
                    {
                        $semester1 .= "<input type=\"checkbox\" name=\"chgSubject\" value=\"" . 
                                $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                }
                elseif ($subject["sbj_semester"] == 2)
                {
                    if($subject["sbj_compulsary"])
                    {
                        $semester2 .= "<input type=\"checkbox\" name=\"chgSubject\" checked=\"checked\" disabled=\"disabled\""
                                . "value=\"" . $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . 
                                $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                    else
                    {
                        $semester2 .= "<input type=\"checkbox\" name=\"chgSubject\" value=\"" . 
                                $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                }
            }
            
            $semester1 .= "</div></div></div>";
            $semester2 .= "</div></div></div>";

            $data = $semester1 . $semester2;
        }
        else
        {
            $data = "<p>Sorry! No Subjects to display for this Course at the moment</p>";
        }
        
        print $data;
        
        break;
        
    case "u_crs":
        $UserLogic = new UserLogic();
        
        $arrStudent["usr_nic"] = $_POST["id"];
        $arrStudent["usr_crs_code"] = $_POST["crs_code"];
        
        $arrSubjects = explode('|', $_POST["sbjs"]);
        array_pop($arrSubjects);
        
        if($UserLogic->updateSubjects($arrStudent, $arrSubjects))
        {
            print("true");
        }
        else
        {
            print("flase");
        }
        
        break;
    
    case "v_sbj":
        $UserLogic = new UserLogic();
        
        $resSubject = $UserLogic->getSubjectByField($_POST["fld_code"]);

        $data = "";
        
        if(mysql_num_rows($resSubject) > 0)
        {
            while($subject = mysql_fetch_array($resSubject))
            {
                $data .= "<div class=\"row alert alert-info\" name=\"div" . $subject["sbj_code"] ."\">"
                        . "<input type=\"checkbox\" name=\"chgSubject\" value=\"" .  $subject["sbj_code"] . "\" />"
                        . "&nbsp;&nbsp;" . $subject["sbj_code"] . " - " . $subject["sbj_name"] 
                        . "<small><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;in following Courses</strong></small>"
                            
                            . "<div class=\"row\">"
                                . "<div class=\"col-md-10 col-md-offset-1 alert alert-success\" name=\"div" . $subject["sbj_code"] ."\">";
                
                // load corrosponding courses according to subjects
                
                $resCourse = $UserLogic->getCourseBySubject($subject["sbj_code"]);
                
                if(mysql_num_rows($resCourse) > 0)
                {
                    while($course = mysql_fetch_array($resCourse))
                    {
                        $data .=  "<input type=\"checkbox\" name=\"chg" . $subject["sbj_code"] . "\" value=\"" .  $course["crs_code"] . "\" />"
                                . "&nbsp;&nbsp;" . $course["crs_name"];
                    }
                }
                        
                $data .=         "</div>"
                            . "</div>"
                        . "</div>";
            }
        }
        else
        {
            $data = "Sorry! No subjects have been allocated for this Department yet";
        }
        
        print $data;
        
        break;
    
    case "u_sbj":
        $UserLogic = new UserLogic();
        
        $arrInstructor["usr_nic"] = $_POST["id"];
        
        $arrSubjectCourse = array();

        $arrSubjects = explode('|', $_POST["sbjs"]);
        array_pop($arrSubjects);

        $arrCourses;

        for ($i=0; $i < count($arrSubjects); $i++)
        {
            $arrCourses = explode(',', $arrSubjects[$i]);

            for ($j=1; $j < count($arrCourses); $j++)
            {
                $arrSubjectCourse[] = array($arrCourses[0], $arrCourses[$j]);
            }    
        }

        if($UserLogic->updateInsSub($arrInstructor, $arrSubjectCourse))
        {
            print("true");
        }
        else
        {
            print("flase");
        }
        
        break;
    
    case "v_cbs":
        $UserLogic = new UserLogic();
        
        $resCourses = $UserLogic->getCourseBySubject($_POST["sbj"]);
        
        $data = "<select class=\"form-control input-sm\" id=\"drpInsSbjCrs\" name=\"drpInsSbjCrs\"
                onchange=\"drpInsSbjCrs_Change(this.options[this.selectedIndex].value, '" . $_POST["id"] . "')\">
                <option value =\"-1\"> -- Select a  Course -- </option>";
                 
        while($course = mysql_fetch_array($resCourses))
        {
            $data .= "<option value=\"" . $course["crs_code"] . "\">" . $course["crs_name"] . "</option>";
        }
                             
        $data .= "</select>";
        
        print $data;
        
        break;
        
    case "v_etyp":
        
        $data = "";
        
        if($_POST["sbj"] == "-1")
        {
            $data = "Select a Course from above";
        }
        else
        {
            $ExamLogic = new ExamLogic();
            $resExType = $ExamLogic->getAlredyHeldExamTypes($_POST["sbj"], 
                                                $_POST["crs"], $_POST["id"]);

            if(mysql_num_rows($resExType) > 0)
            {
                $blnMidSem = false;
                $blnEndSem = false;
                
                while($type = mysql_fetch_array($resExType))
                {
                    if($type["exm_type"] == "M") // mid semester completed
                    {
                        $blnMidSem = true;
                    }
                    elseif($type["exm_type"] == "E") // end semester completed
                    {
                        $blnEndSem = true;
                    }
                }
                
                
                if($blnEndSem) // End done
                {
                    // show only Q (E and M disabled)
                    $data = 
                    "<input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"Q\" checked=\"checked\">&nbsp;Quiz<br>
                     <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"M\" disabled=\"disabled\" >&nbsp;Mid Semester
                     &nbsp;&nbsp;&nbsp;<span class=\"label label-warning\">Already Created</span><br>
                     <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"E\" disabled=\"disabled\" >&nbsp;End Semester
                     &nbsp;&nbsp;<span class=\"label label-warning\">Already Created</span><br>";
                }
                else // End not done
                {
                    if($blnMidSem) // Mid done
                    {
                        // show Q and E (M disabled)
                        $data = 
                        "<input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"Q\">&nbsp;Quiz<br>
                         <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"M\" disabled=\"disabled\">&nbsp;Mid Semester
                         &nbsp;&nbsp;&nbsp;<span class=\"label label-warning\">Already Created</span><br>
                         <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"E\">&nbsp;End Semester";
                    }
                    else
                    {
                        // create full rdoExmType
                        $data = 
                        "<input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"Q\">&nbsp;Quiz<br>
                        <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"M\">&nbsp;Mid Semester<br>
                        <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"E\">&nbsp;End Semester";
                    }
                }
            }
            else
            {
                // create full rdoExmType
                $data = 
                "<input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"Q\">&nbsp;Quiz<br>
                <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"M\">&nbsp;Mid Semester<br>
                <input type=\"radio\" name=\"rdoExam\" id=\"rdoExam\" value=\"E\">&nbsp;End Semester";
            }
        }
        
        print $data;
        
        break;
       
    case "d_exm": // delete exam
        $ExamLogic = new ExamLogic();
    
        $data;

        if($ExamLogic->deleteExam($_POST["exm"]))
        {
            $data = "true";
        }
        else
        {
            $data = "false";
        }

        print $data;
        
        break;
    
    case "p_exm":
        $ExamLogic = new ExamLogic();
        
        if(@checkdate($_POST["mon"], $_POST["day"], $_POST["year"]))
        {
            $strDueDate = $_POST["year"] . "-" . $_POST["mon"] . "-" . $_POST["day"];
            
            $intDteDiff = strtotime($strDueDate) - strtotime(date('Y-m-d'));
            
            if($intDteDiff > 0)
            {
                if($ExamLogic->publishExam($_POST["exm"], $strDueDate))
                {
                    print("true");
                }
                else
                {
                    print("false");
                }
            }
            else
            {
                print("<div class=\"alert alert-danger\"><p>Enter a day in the future from today</p> <p><strong>(" . date('Y-m-d') . ")<strong></p></div>");
            }
        }
        else
        {
            print("<div class=\"alert alert-danger\">Not a valid date</div>");
        }

        break;
    
    case "c_exm":
        $ExamLogic = new ExamLogic();
    
        $data;

        if($ExamLogic->cancelExam($_POST["exm"]))
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
