<?php
/**
 *  Sign Up Student page submit data here
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

switch ($_POST["ret_type"]) 
{
    case "crs": // course
        $UserLogic = new UserLogic();
        
        $resCourses = $UserLogic->getCourseByField($_POST["fld_code"]);

        $data = "<select class=\"form-control input-sm\" id=\"drpDegree\" name=\"drpDegree\" onchange=\"drpDegree_Change(this.options[this.selectedIndex].value)\">
                <option>-- Select a Course --</option>";
                            
        while($course = mysql_fetch_array($resCourses))
        {
            $data .= "<option value=\"$course[crs_code]\">$course[crs_name]</option>";
        }
                              
        $data .= "</select>";
        
        print $data;

        break;
      
    case "sbj": //subjects
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
                        $semester1 .= "<input type=\"checkbox\" name=\"chgSubject[]\" checked=\"checked\" disabled=\"disabled\""
                                . "value=\"" . $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . 
                                $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                    else
                    {
                        $semester1 .= "<input type=\"checkbox\" name=\"chgSubject[]\" value=\"" . 
                                $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                }
                elseif ($subject["sbj_semester"] == 2)
                {
                    if($subject["sbj_compulsary"])
                    {
                        $semester2 .= "<input type=\"checkbox\" name=\"chgSubject[]\" checked=\"checked\" disabled=\"disabled\""
                                . "value=\"" . $subject["sbj_code"] . "\" />&nbsp;&nbsp;" . 
                                $subject["sbj_code"] . " - " . $subject["sbj_name"] . "<br />";
                    }
                    else
                    {
                        $semester2 .= "<input type=\"checkbox\" name=\"chgSubject[]\" value=\"" . 
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

    default:
        break;
}

?>
