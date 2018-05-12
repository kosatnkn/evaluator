$(document).ready(function()
{
    var txtID = $("#txtID");
    var txtPwd = $("#txtPwd");
    var btnSubmit = $("#btnSubmit");


    txtID.blur(function()
    {
        if(txtID.val() == "")
        {
            txtID.addClass("custom-input-error");
        }
    });
    
    txtID.focus(function()
    {
        if(txtID.val() == "")
        {
            txtID.removeClass("custom-input-error");
        }
    });
    
    txtPwd.blur(function()
    {
        if(txtPwd.val() == "")
        {
            txtPwd.addClass("custom-input-error");
        }
    });
    
    txtPwd.focus(function()
    {
        if(txtPwd.val() == "")
        {
            txtPwd.removeClass("custom-input-error");
        }
    });
    
    btnSubmit.click(function()
    {
        if(txtID.val() != "" && txtPwd.val() != "")
        {
            $("form").submit();
        }
    });
    
});