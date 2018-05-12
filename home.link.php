<?php
session_start();
/**
 *  Home page submit data here
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/ExamLogic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/CommonLogic.php');
//_____________________________________________________________________________


if($_POST["hdnType"] == "NewExam")
{
    $_SESSION["exm_code"] = "";
    
    // add new exam
    $ExamLogic = new ExamLogic();
    $CommonLogic = new CommonLogic();
    
    $arrExam["usr_nic"] = $_SESSION["usrCode"];
    $arrExam["sbj_code"] = $_POST["drpInsSbj"];
    $arrExam["crs_code"] = $_POST["drpInsSbjCrs"];
    $arrExam["exm_type"] = $_POST["rdoExam"];
    $arrExam["exm_duration"] = $CommonLogic->timeInMinutes($_POST["txtDHrs"], $_POST["txtDMin"]);
    
    $intExmCode = $ExamLogic->addExam($arrExam);
    
    if($intExmCode == false)
    {
        // if operation fails return to home.php
        @header("Location:home.php");
    }
    else
    {
        // move on to create.php
        $_SESSION["exm_code"] = $intExmCode;
        @header("Location:create.php");
    }
}
elseif($_POST["hdnType"] == "EditExam")
{
    // move on to create.php
    $_SESSION["exm_code"] = $_POST["exmNo"];
    @header("Location:create.php");
}
elseif($_POST["hdnType"] == "StartExam")
{
	$ExamLogic = new ExamLogic();
	
	$_SESSION["exm_code"] = $_POST["hdnExmNo"];
	
    // add empty records to exm_take
    // add empty records to exm_tke_answer
    
    if($ExamLogic->prepareExam($_SESSION["exm_code"], $_SESSION["usrCode"]))
    {
		// move on to take.php
		@header("Location:take.php");
	}
	else
	{
		// move on to home.php
		@header("Location:home.php");
	}
}
elseif($_POST["hdnType"] == "EvalExam")
{
    // move on to create.php
    $_SESSION["exm_code"] = $_POST["exmNo"];
    @header("Location:evaluate.php");
}




?>
