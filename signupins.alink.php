<?php
/**
 *  Sign Up Student page submit data here
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

switch ($_POST["ret_type"]) 
{
    case "sbj":
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

    default:
        break;
}

?>
