
<?php
/**
 *  Sign Up Student page submit data here
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

// create array

$arrStudent["usr_nic"] = $_POST["txtNIC"];
$arrStudent["usr_reg_no"] = $_POST["txtReg"];
$arrStudent["usr_password"] = $_POST["txtPwd"];
$arrStudent["usr_first_name"] = $_POST["txtFname"];
$arrStudent["usr_middle_name"] = $_POST["txtMname"];
$arrStudent["usr_last_name"] = $_POST["txtLname"];
$arrStudent["usr_gender"] = $_POST["rdoGender"];
$arrStudent["usr_dob"] = $_POST["txtDOBYear"] . "-" . $_POST["txtDOBMonth"] . "-" . $_POST["txtDOBDate"];
$arrStudent["usr_email"] = $_POST["txtEmail"];
$arrStudent["usr_crs_code"] = $_POST["drpDegree"];


// get optional subjects chosen by student
$arrSubjects;

if (isset($_POST["chgSubject"]))
{
    $arrSubjects = $_POST["chgSubject"];
}


// checks

if($arrStudent["usr_nic"] != "" &&
    $arrStudent["usr_reg_no"] != "" &&
    $arrStudent["usr_password"] != "" &&
    $arrStudent["usr_first_name"] != "" &&
    $arrStudent["usr_last_name"] != "" &&
    $arrStudent["usr_gender"] != "" &&
    checkdate($_POST["txtDOBMonth"], $_POST["txtDOBDate"], $_POST["txtDOBYear"]) &&
    $arrStudent["usr_email"] != "")
{
    // add student
    $UserLogic = new UserLogic();

    if($UserLogic->addStudent($arrStudent, $arrSubjects))
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
