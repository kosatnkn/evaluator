<?php
session_start();
/**
 *  Index page submit data here
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

if($_POST["txtID"] != "" && $_POST["txtPwd"] != "")
{
    $UserLogic = new UserLogic();
    
    $result = $UserLogic->getLogin($_POST["txtID"], $_POST["txtPwd"]);
    
    if(mysql_num_rows($result) == 1)
    {
        // set SESSION variables
        $_SESSION["usrCode"] = mysql_result($result,0,"usr_nic");
        $_SESSION["usrName"] = mysql_result($result,0,"usr_name");
        $_SESSION["usrType"] = mysql_result($result,0,"usr_type");
        $_SESSION["usrAppr"] = mysql_result($result,0,"usr_approved");
        
        // redirect to home.php
        @header("Location:home.php");
    }
    else
    {
         // redirect to index.php
        @header("Location:index.php");
    }
    
}
else
{
    // redirect to index.php
    @header("Location:index.php");
}

?>
