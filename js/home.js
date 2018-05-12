//______________________________________________________________________________
//
// Administrator Section Functions
//______________________________________________________________________________

function viewStudent(strStdID)
{ 
    $.post("home.alink.php",
                {ret_type:"v_std", id:strStdID},
                function(result)
                {
                    $("#mdlUser_content").html(result);
                });
}

function approveStudent(strStdID)
{
    if(confirm("Are you ure you want to approve this Student"))
    {
        $.post("home.alink.php",
                {ret_type:"a_std", id:strStdID},
                function(result)
                {
                    if(result === "true")
                    {
                        $("#divAprveStd" + strStdID).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-success\">Approved</a>");

                        $("#trStd" + strStdID).fadeOut('slow', function (){
                            $(this).remove();
                    });

                    }
                    else
                    {
                        $("#divAprveStd" + strStdID).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-danger\">Error</a>");
                    }
                });
    }
}

function viewInstructor(strInsID)
{
    $.post("home.alink.php",
            {ret_type:"v_ins", id:strInsID},
            function(result)
            {
                $("#mdlUser_content").html(result);
            });
}

function approveInstructor(strInsID)
{
    if(confirm("Are you ure you want to approve this Instructor"))
    {
        $.post("home.alink.php",
                {ret_type:"a_ins", id:strInsID},
                function(result)
                {
                    if(result === "true")
                    {
                        $("#divAprveIns" + strInsID).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-success\">Approved</a>");

                        $("#trIns" + strInsID).fadeOut('slow', function (){
                            $(this).remove();
                        });
                    }
                    else
                    {
                        $("#divAprveIns" + strInsID).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-danger\">Error</a>");
                    }
                });
                    
    }
}



//______________________________________________________________________________
//
// Wait Section Functions
//______________________________________________________________________________

function changePwd(strUsrID)
{
    var txtOpwd = $("#txtOpwd");
    var txtNpwd = $("#txtNpwd");
    var txtRpwd = $("#txtRpwd");
    
    if(txtOpwd.val() != "" && txtNpwd.val() != "" && txtRpwd.val() != "")
    {
        if(txtNpwd.val() == txtRpwd.val())
        {
            $.post("home.alink.php",
                        {ret_type:"u_pwd", id:strUsrID, 
                         opwd:txtOpwd.val(), npwd:txtNpwd.val()},
                        function(result)
                        {
                            if(result == "true")
                            {
                                $("#divPwdMsg").html("<div class=\"alert alert-success\">Password Updated Successfully</div>");
                                
                                txtOpwd.val("");
                                txtNpwd.val("");
                                txtRpwd.val("");
                            }
                            else
                            {
                                 $("#divPwdMsg").html("<div class=\"alert alert-danger\">Error Updating Pasword.<br>Did you enter the 'Old Password' correctly?</div>");
                            }
                        });
        }
        else
        {
            $("#divPwdMsg").html("<div class=\"alert alert-warning\">New password and re-entered new password is not the same</div>");
        }
    }
    else
    {
        $("#divPwdMsg").html("<div class=\"alert alert-warning\">You haven't entered data properly</div>");
    }
}

function clearPwd()
{
    $("#txtOpwd").val("");
    $("#txtNpwd").val("");
    $("#txtRpwd").val("");
    $("#divPwdMsg").html("");
}

function editDetails(strUsrID)
{
    var txtFname = $("#txtFname");
    var txtMname = $("#txtMname");
    var txtLname = $("#txtLname");
    var rdoGender = $("#rdoGender:checked");
    var txtEmail = $("#txtEmail");
    
    var strMessage = "";
    
    if(txtFname.val() == "")
    {
        strMessage += "<p>You cannot keep 'First Name' empty</p>";
    }
    
    if(txtLname.val() == "")
    {
        strMessage += "<p>You cannot keep 'Last Name' empty</p>";
    }
    
    if(txtEmail.val() == "")
    {
        strMessage += "<p>You cannot keep 'Email' empty</p>";
    }
    
    
    if(txtFname.val() != "" && txtLname.val() != "" && txtEmail.val() != "")
    {
        // save data
        $.post("home.alink.php",
                {ret_type:"u_usr", id:strUsrID,
                fname:txtFname.val(), mname:txtMname.val(),
                lname:txtLname.val(), gdr:rdoGender.val(),
                email:txtEmail.val()},
                function(result)
                {
                    if(result == "true")
                    {
                        $("#divDtlMsg").html("<div class=\"alert alert-success\">User details updated successfully</div>");
                    }
                    else
                    {
                        $("#divDtlMsg").html("<div class=\"alert alert-danger\">Error updating user details</div>");
                    }
                });
    }
    else
    {
        $("#divDtlMsg").html("<div class=\"alert alert-warning\">" + strMessage + "</div>");
    }
}

// drpField ________________________________________________________________
function drpField_Change(selectedVal)
{
    $("#divDrpDegree").html("<strong><small>Loading...</small></strong>");

    $.post("signupstd.alink.php",
            {ret_type:"crs", fld_code:selectedVal},
            function(result)
            {
                $("#divDrpDegree").html(result);
                $("#divSubjects").html("<p>Select a Degree from above to view Subjects</p>");
            });
}

// drpDegree _______________________________________________________________
function drpDegree_Change(selectedVal)
{
    $("#divSubjects").html("<strong><small>Loading...</small></strong>");

    $.post("home.alink.php",
            {ret_type:"v_crs", crs_code:selectedVal},
            function(result)
            {
                $("#divSubjects").html(result);
            });
}

function editCourse(strStdID)
{
    var drpDegree = $("#drpDegree");
    
    var chgSubject = $("input:checkbox[name=chgSubject]:checked");
    
    var strSubjectList = "";
    
    chgSubject.each(function()
    {
        strSubjectList += $(this).val() + "|";
    });
    
    if(strSubjectList != "")
    {
        // save data
        $.post("home.alink.php",
                {ret_type:"u_crs", id:strStdID,
                 crs_code:drpDegree.val(), sbjs:strSubjectList},
                function(result)
                {
                    if(result == "true")
                    {
                        $("#divCrsMsg").html("<div class=\"alert alert-success\">Courses updated successfully</div>");
                    }
                    else
                    {
                        $("#divCrsMsg").html("<div class=\"alert alert-danger\">Error updating Courses</div>");
                    }
                });
    }
    else
    {
        $("#divCrsMsg").html("<div class=\"alert alert-warning\">You need to select one or more subjects</div>");
    }
}

// drpDpmnt ________________________________________________________________
function drpDpmnt_Change(selectedVal)
{
    $.post("home.alink.php",
                {ret_type:"v_sbj", fld_code:selectedVal},
                function(result)
                {
                    $("#divInsSub").html(result);
                });
}

function editSubjects(strInsID)
{
    var drpDpmnt = $("#drpDpmnt");
    
    var strSubjects = "";
    var strSubjectCourse = ""; // temp
    
    // get subjects that are checked in an array
    var arrChgSubject = $("input:checkbox[name=chgSubject]:checked");
    var arrChgCourses;
    
    // iterate through the array
    arrChgSubject.each(function()
    {
        strSubjectCourse = $(this).val();
        
        // for each item find the checked array of corrosponding courses
        arrChgCourses = $("input:checkbox[name=chg" + $(this).val() + "]:checked");
        
        // if array has elements
        if(arrChgCourses.length > 0)
        {
            //iterate through that array and add courses
            arrChgCourses.each(function()
            {
                strSubjectCourse += "," + $(this).val();
            });
            
            strSubjects += strSubjectCourse + "|";
        }
    });
    
    if(strSubjects != "")
    {
        // save data
        $.post("home.alink.php",
                {ret_type:"u_sbj", id:strInsID,
                 crs_code:drpDpmnt.val(), sbjs:strSubjects},
                function(result)
                {
                    if(result == "true")
                    {
                        $("#divSbjMsg").html("<div class=\"alert alert-success\">Subjects updated successfully</div>");
                        $("#divInsSub").html("");
                        $("#drpDpmnt").val("0");
                    }
                    else
                    {
                        $("#divSbjMsg").html("<div class=\"alert alert-danger\">Error updating Subjects</div>");
                        $("#divInsSub").html("");
                        $("#drpDpmnt").val("0");
                    }
                });
    }
    else
    {
        $("#divSbjMsg").html("<div class=\"alert alert-warning\">You need to select one or more subjects</div>");
    }
}

function reloadPage()
{
    location.reload(true);
}



//______________________________________________________________________________
//
// Student Section Functions
//______________________________________________________________________________

function btnTakeExm_Click(intExmNo)
{
	// update take exam modal
	var strExmName = $("#hdnSEName" + intExmNo).val();
	var strExmCrs = $("#hdnSECrs" + intExmNo).val();
	var strExmDur = $("#hdnSEDur" + intExmNo).val();
	
	var strExmType = $("#hdnSEType" + intExmNo).val();
	
	if(strExmType == "Q")
	{
		strExmType = "Quiz";
	}
	else if(strExmType == "M")
	{
		strExmType = "Mid Semester";
	}
	else if(strExmType == "E")
	{
		strExmType = "End Semester";
	}
	
	var strData = "<p>" + strExmCrs + "</p><p><h4>" + strExmName + "</h4></p><div class=\"col-md-6 pull-left\">" 
	+ strExmType + "</div><div class=\"col-md-6 pull-right\">" + strExmDur + "</div>";
	
	$("#divStartExDetails").html(strData);
	
	// set exam number
	$("#hdnExmNo").val(intExmNo);
	
	
	// show modal
	$("#mdlStartEx").modal('show');
}



//______________________________________________________________________________
//
// Instructor Section Functions
//______________________________________________________________________________

function drpInsSbj_Change(selectedVal, strInsID)
{
    $("#divDrpCourse").html("<strong><small>Loading...</small></strong>");

    $.post("home.alink.php",
            {ret_type:"v_cbs", sbj:selectedVal, id:strInsID},
            function(result)
            {
                $("#divDrpCourse").html(result);
            });
}

function drpInsSbjCrs_Change(selectedVal, strInsID)
{
    $("#divRdoExamType").html("<strong><small>Loading...</small></strong>");

    $.post("home.alink.php",
            {ret_type:"v_etyp", sbj:$("#drpInsSbj").val(),
             crs:selectedVal, id:strInsID},
            function(result)
            {
                $("#divRdoExamType").html(result);
            });
}

function createExmCancel_Click()
{
    $("#drpInsSbj").val("-1");
    $("#divRdoExamType").html("");
}

function createExam_Click()
{
    // create exam
    var strExamType = $("#rdoExam:checked").val();
    
    if($("#drpInsSbj").val() != "-1")
    {
        if($("#drpInsSbjCrs").val() != "-1")
        {
            if(strExamType == "Q" || strExamType == "M" || strExamType == "E")
            {
                if($("#txtDHrs").val() > 0 || $("#txtDMin").val() > 0)
                {
                    $("#frmCreateExam").submit();
                }
                else
                {
                    $("#divCreateExamMsg").html("<div class=\"alert alert-warning\">Specify a duration for the exam</div>");
                }
            }
            else
            {
                $("#divCreateExamMsg").html("<div class=\"alert alert-warning\">Select an Exam Type</div>");
            }
        }
        else
        {
            $("#divCreateExamMsg").html("<div class=\"alert alert-warning\">Select a Course</div>");
        }
    }
    else
    {
        $("#divCreateExamMsg").html("<div class=\"alert alert-warning\">Select a Subject</div>");
    }
}

function editExam_Click(strExmNo)
{
    //$.post("home.link.php", {hdnType: "EditExam", exmNo: strExmNo});
    
    var frmEditExam = $('<form action="home.link.php" method="post">' +
                        '<input type="hidden" name="hdnType"' + 
                        'value="EditExam"/>' +
                        '<input type="hidden" name="exmNo"' + 
                        'value="' + strExmNo + '"/>' +
                        '</form>');
                        
    $(frmEditExam).appendTo("body").submit();
}

function deleteExam_Click(strExmNo)
{
    // delete exam
    if(confirm("Are you sure you want to delete this exam"))
    {
        $.post("home.alink.php",
                {ret_type:"d_exm", exm:strExmNo},
                function(result)
                {
                    if(result === "true")
                    {
                        $("#divDelEx" + strExmNo).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-warning\">Deleted</a>");
                    
                        $("#trCtdExm" + strExmNo).fadeOut('slow', function (){
                            $(this).remove();
                        });
                    }
                    else
                    {
                        $("#divDelEx" + strExmNo).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-danger\">Error</a>");
                    }
                });
    }
}

function publishExam_Click(intExmNo)
{
    // set hidden field of Pub Due Date model
    $("#hdnPubDExmNo").val(intExmNo);
    
    // show modal
    $("#mdlPubDate").modal('show');
    
}

function cancelExam_Click(strExmNo)
{
    // cancel exam
    if(confirm("Are you sure you want to cancel this exam"))
    {
        $.post("home.alink.php",
                {ret_type:"c_exm", exm:strExmNo},
                function(result)
                {
                    if(result === "true")
                    {
                        $("#divCancelExm" + strExmNo).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-warning\">Cancelled</a>");
                    
                        $("#trPubExm" + strExmNo).fadeOut('slow', function (){
                            $(this).remove();
                        });
                        
                        reloadPage();
                    }
                    else
                    {
                        $("#divCancelExm" + strExmNo).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-danger\">Error</a>");
                    }
                });
    }
}

function evalExam_Click(strExmNo)
{
    // evaluate exam
    var frmEvalExam = $('<form action="home.link.php" method="post">' +
                        '<input type="hidden" name="hdnType"' + 
                        'value="EvalExam"/>' +
                        '<input type="hidden" name="exmNo"' + 
                        'value="' + strExmNo + '"/>' +
                        '</form>');
                        
    $(frmEvalExam).appendTo("body").submit();
}

function clearPubDate()
{
    $("#divPubDteMsg").html("");
    $("#hdnPubDExmNo").val("");
    $("#txtPYear").val("");
    $("#txtPMon").val("");
    $("#txtPDte").val("");
}

function publishExam()
{
    // message div
    var divPubDteMsg = $("#divPubDteMsg");
    
    // get Exam No from Pub Due Date modal
    var intExmNo = $("#hdnPubDExmNo").val();
    
    // publish exam
    $.post("home.alink.php",
            {ret_type:"p_exm", exm:intExmNo, 
                year:$("#txtPYear").val(), mon:$("#txtPMon").val(), day:$("#txtPDte").val()},
            function(result)
            {
                if(result === "true")
                {
                    $("#divPubEx" + intExmNo).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-success\">Published</a>");

                    // hide row
                    $("#trCtdExm" + intExmNo).fadeOut('slow', function (){
                        $(this).remove();
                    });

                    // close modal
                    clearPubDate();
                    $("#mdlPubDate").modal('hide');

                    // reload page
                    reloadPage();
                }
                else
                {
                    if(result === "false")
                    {
                        $("#divPubEx" + intExmNo).html("<a href=\"#\" class=\"btn btn-block btn-xs btn-danger\">Error</a>");
                    }
                    else
                    {
                        $("#divPubDteMsg").html(result);
                    }
                }
            });    
}
