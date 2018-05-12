<?php
/**
 *  Sign Up Instructor page submit data here
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

// create array

$arrInstructor["usr_nic"] = $_POST["txtNIC"];
$arrInstructor["usr_password"] = $_POST["txtPwd"];
$arrInstructor["usr_title"] = $_POST["drpTitle"];
$arrInstructor["usr_first_name"] = $_POST["txtFname"];
$arrInstructor["usr_middle_name"] = $_POST["txtMname"];
$arrInstructor["usr_last_name"] = $_POST["txtLname"];
$arrInstructor["usr_gender"] = $_POST["rdoGender"];
$arrInstructor["usr_dob"] = $_POST["txtDOBYear"] . "-" . $_POST["txtDOBMonth"] . "-" . $_POST["txtDOBDate"];
$arrInstructor["usr_marital"] = $_POST["rdoMarital"];
$arrInstructor["usr_email"] = $_POST["txtEmail"];


// get subjects and their corrosponding courses selected by instructor
$arrSubjectCourse = array();

$arrSubjects = explode('|', $_POST["hdnSubjects"]);
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


// checks
if($arrInstructor["usr_nic"] != "" &&
    $arrInstructor["usr_password"] != "" &&
    $arrInstructor["usr_first_name"] != "" &&
    $arrInstructor["usr_last_name"] != "" &&
    $arrInstructor["usr_gender"] != "" &&
    checkdate($_POST["txtDOBMonth"], $_POST["txtDOBDate"], $_POST["txtDOBYear"]) &&
    $arrInstructor["usr_marital"] != "" &&
    $arrInstructor["usr_email"] != "")
{
    // add instructor
    $UserLogic = new UserLogic();

    if($UserLogic->addInstructor($arrInstructor, $arrSubjectCourse))
    {
        include_once 'ui/signup.success.php';
    }
    else
    {
        include_once 'ui/signup.faliure.php';
    }
}
else
{
    include_once 'ui/signup.faliure.php';
}



?>
