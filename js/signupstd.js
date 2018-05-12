$(document).ready(function()
{
    var txtNIC = $("#txtNIC");
    var txtReg = $("#txtReg");
    
    var txtPwd = $("#txtPwd");
    var txtRpwd = $("#txtRpwd");
    
    var txtFname = $("#txtFname");
    var txtLname = $("#txtLname");
    
    var txtDOBYear = $("#txtDOBYear");
    var txtDOBMonth = $("#txtDOBMonth");
    var txtDOBDate = $("#txtDOBDate");
    
    var txtEmail = $("#txtEmail");
    
    var drpField = $("#drpField");
    var drpDegree = $("#drpDegree");
    
    var btnSubmit = $("#btnSubmit");
    
    
    // txtNIC __________________________________________________________________
    txtNIC.blur(function()
    {
        if(txtNIC.val() != "")
        {
            // see wether nic is valid
            // ajax and see wether it exists
            txtNIC.addClass("custom-input-success");
        }
        else
        {
            txtNIC.removeClass("custom-input-success");
            txtNIC.addClass("custom-input-error");
        }
    });
    
    txtNIC.focus(function()
    {
        txtNIC.removeClass("custom-input-error");
        txtNIC.removeClass("custom-input-success");
    });
    
    
    // txtReg __________________________________________________________________
    txtReg.blur(function()
    {
        if(txtReg.val() != "")
        {
            // ajax and see wether reg exists
            txtReg.addClass("custom-input-success");
        }
        else
        {
            txtReg.removeClass("custom-input-success");
            txtReg.addClass("custom-input-error");
        }
    });
    
    txtReg.focus(function()
    {
        txtReg.removeClass("custom-input-error");
        txtReg.removeClass("custom-input-success");
    });
    
    
    // txtPwd and txtRpwd ______________________________________________________
    txtRpwd.blur(function()
    {
        if(txtPwd.val() != "" && txtRpwd.val() != "")
        {
            if(txtPwd.val() == txtRpwd.val())
            {
                txtPwd.addClass("custom-input-success");
                txtRpwd.addClass("custom-input-success");
            }
            else
            {
                txtPwd.removeClass("custom-input-success");
                txtRpwd.removeClass("custom-input-success");
                
                txtPwd.addClass("custom-input-error");
                txtRpwd.addClass("custom-input-error");
            }
        }
        else
        {
            txtPwd.addClass("custom-input-error");
            txtRpwd.addClass("custom-input-error");
        }
    });
    
    txtPwd.focus(function()
    {
        txtPwd.removeClass("custom-input-error");
        txtPwd.removeClass("custom-input-success");
    });
    
    txtRpwd.focus(function()
    {
        txtRpwd.removeClass("custom-input-error");
        txtRpwd.removeClass("custom-input-success");
    });
    
    
    // First, Middle and Last name _____________________________________________
    
    txtFname.blur(function()
    {
        if(txtFname.val() == "")
        {
            txtFname.addClass("custom-input-error");
        }
    });
    
    txtFname.focus(function()
    {
        if(txtFname.val() == "")
        {
            txtFname.removeClass("custom-input-error");
        }
    });
    
    
    txtLname.blur(function()
    {
        if(txtLname.val() == "")
        {
            txtLname.addClass("custom-input-error");
        }
    });
    
    txtLname.focus(function()
    {
        if(txtLname.val() == "")
        {
            txtLname.removeClass("custom-input-error");
        }
    });
    
    
    // Birth day _______________________________________________________________
    
    txtDOBYear.blur(function()
    {
        if(txtDOBYear.val() == "")
        {
            txtDOBYear.addClass("custom-input-error");
        }
    });
    
    txtDOBYear.focus(function()
    {
        if(txtDOBYear.val() == "")
        {
            txtDOBYear.removeClass("custom-input-error");
        }
    });
    
    
    txtDOBMonth.blur(function()
    {
        if(txtDOBMonth.val() == "")
        {
            txtDOBMonth.addClass("custom-input-error");
        }
    });
    
    txtDOBMonth.focus(function()
    {
        if(txtDOBMonth.val() == "")
        {
            txtDOBMonth.removeClass("custom-input-error");
        }
    });
    
    
    txtDOBDate.blur(function()
    {
        if(txtDOBDate.val() == "")
        {
            txtDOBDate.addClass("custom-input-error");
        }
        else
        {
            //check date
            
        }
    });
    
    txtDOBDate.focus(function()
    {
        if(txtDOBDate.val() == "")
        {
            txtDOBDate.removeClass("custom-input-error");
        }
    });
    
    
    // Email ___________________________________________________________________
    
    txtEmail.blur(function()
    {
        if(txtEmail.val() == "")
        {
            txtEmail.addClass("custom-input-error");
        }
    });
    
    txtEmail.focus(function()
    {
        if(txtEmail.val() == "")
        {
            txtEmail.removeClass("custom-input-error");
        }
    });
    
    
    // drpField ________________________________________________________________
    
    drpField.change(function()
    {
        $("#divDrpDegree").html("<strong><small>Loading...</small></strong>");
        
        $.post("signupstd.alink.php",
                {ret_type:"crs", fld_code:drpField.val()},
                function(result)
                {
                    $("#divDrpDegree").html(result);
                    $("#divSubjects").html("<p>Select a Degree from above to view Subjects</p>");
                });
    });    
    
    
    // btnSubmit _______________________________________________________________
    btnSubmit.click(function()
    {   
		var rdoGender = $("#rdoGender:checked");

        if(txtNIC.val() != "" && txtReg.val() != ""
        && txtPwd.val() != "" && txtRpwd.val() != "" && txtPwd.val() == txtRpwd.val()
        && txtFname.val() != "" && txtLname.val() != "" && txtEmail.val() != ""
        && drpDegree.val() != "" && rdoGender.val() !== undefined)
        {
            $("form").submit();
        }
        else
        {
            alert("It looks like that you haven't entered your details correctly.\n Please check again.");
        }
    });
    
});

// drpDegree _______________________________________________________________
function drpDegree_Change(selectedVal)
{
    $("#divSubjects").html("<strong><small>Loading...</small></strong>");

    $.post("signupstd.alink.php",
            {ret_type:"sbj", crs_code:selectedVal},
            function(result)
            {
                $("#divSubjects").html(result);
            });
}
