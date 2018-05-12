$(document).ready(function()
{
    $("#btnDurTrigger").tooltip();
});

function clearDuration()
{
    $("#txtHrs").val("");
    $("#txtMin").val("");
}

function changeDuration(intExmCode)
{
    var intHrs = parseInt($("#txtHrs").val());
    var intMin = parseInt($("#txtMin").val());
    
    if(intHrs > 0 || intMin > 0)
    {
        $("#divDuration").html("Loading...");
    
        $.post("create.alink.php",
                {ret_type:"u_dur", exm:intExmCode,
                    durH:intHrs, durM:intMin},
                function(result)
                {
                    $("#divDuration").html(result);
                });
                
        // trigger close button
        $("#btnDurClose").click();
    }
    else
    {
        $("#divEditDur").html("<div class=\"alert alert-warning\">Enter a valid number</div>");
    }
    
}


// MCQ
function clearAMCQ()
{
    $("#divAMCQMsg").html("<div class=\"alert alert-success\">OK! Add next question</div>");
    
    $("#txtMQst").val("");
    
    $("#txtMAns1").val("");
    $("#txtMAns2").val("");
    $("#txtMAns3").val("");
    $("#txtMAns4").val("");
    
    $("#txtMCQMarks").val("");
    
    $("#rdoMCQAns:checked").removeAttr('checked');
    
    // scroll to bottom of page
    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
}

function addMCQ(intExmNo)
{
    var strMQst = $("#txtMQst").val();
    
    var strMAns1 = $("#txtMAns1").val();
    var strMAns2 = $("#txtMAns2").val();
    var strMAns3 = $("#txtMAns3").val();
    var strMAns4 = $("#txtMAns4").val();

    var intMCQAns = $("#rdoMCQAns:checked").val();
    
    var intMCQMarks = parseInt($("#txtMCQMarks").val());
        
    var btnMCQSave = $("#btnMCQSave");
    

    var divAMCQMsg = $("#divAMCQMsg");

    // check
    if(strMQst === "") // empty question
    {
        divAMCQMsg.html("<div class=\"alert alert-warning\">You forgot to ask the Question</div>");
    }
    else
    {
        if(strMAns1 === "")
        {
            divAMCQMsg.html("<div class=\"alert alert-warning\">You cannot keep the first answer empty</div>");
        }
        else
        {
            if(strMAns2 === "")
            {
                divAMCQMsg.html("<div class=\"alert alert-warning\">You need to enter both Answer 1 and Answer 2</div>");
            }
            else
            {
                if(strMAns3 === "" && strMAns4 !== "")
                {
                    divAMCQMsg.html("<div class=\"alert alert-warning\">You cannot keep Answer 3 empty and give Answer 4</div>");
                }
                else
                {
                    if(intMCQAns === undefined) // didn't select the right answer
                    {
                        divAMCQMsg.html("<div class=\"alert alert-warning\">You need to select one answer</div>");
                    }
                    else
                    {
                        if($("#txtMAns" + intMCQAns).val() === "")
                        {
                            divAMCQMsg.html("<div class=\"alert alert-warning\">You cannot select an empty answer</div>");
                        }
                        else
                        {
                            if(intMCQMarks > 0)
                            {
                                // save
                                btnMCQSave.button('loading'); // set saving message on the button

                                $.post("create.alink.php",
                                            {ret_type:"a_mcq", exm:intExmNo, qst:strMQst,
                                             ans1:strMAns1, ans2:strMAns2,
                                             ans3:strMAns3, ans4:strMAns4, crt:intMCQAns, mks:intMCQMarks},
                                            function(result)
                                            {
                                                if(result === "false")
                                                {
                                                    divAMCQMsg.html("<div class=\"alert alert-danger\">Error saving question</div>");
                                                }
                                                else
                                                {
                                                    // append question to page
                                                    $("#divExamPaper").append(result);
                                                    
                                                    clearAMCQ();
                                                }

                                                btnMCQSave.button('reset'); // reset when save cycle is complete
                                            });
                                }
                                else
                                {
                                    divAMCQMsg.html("<div class=\"alert alert-warning\">You need to enter marks for this question</div>");
                                }
                        }
                    }            
                }
            }
        }
    }
    
}

function showMCQ(intExmNo, intQstNo)
{
    $.post("create.alink.php",
            {ret_type:"v_qst", exm:intExmNo, qst:intQstNo},
            function(result)
            {
                $("#divEditQst").html(result);
                $("#mdlEditQst").modal('show');
            });
}

function editMCQ(intExmNo, intQstNo)
{
    var strMQst = $("#txtEMQst").val();
    
    var strMAns1 = $("#txtEMAns1").val();
    var strMAns2 = $("#txtEMAns2").val();
    var strMAns3 = $("#txtEMAns3").val();
    var strMAns4 = $("#txtEMAns4").val();

    var intMCQAns = $("#rdoEMCQAns:checked").val();
    
    var intMCQMarks = parseInt($("#txtEMCQMarks").val());
        
    var btnMCQSave = $("#btnEMCQSave");
    

    var divEMCQMsg = $("#divEMCQMsg");

    // check
    if(strMQst === "") // empty question
    {
        divEMCQMsg.html("<div class=\"alert alert-warning\">You forgot to ask the Question</div>");
    }
    else
    {
        if(strMAns1 === "")
        {
            divEMCQMsg.html("<div class=\"alert alert-warning\">You cannot keep the first answer empty</div>");
        }
        else
        {
            if(strMAns2 === "")
            {
                divEMCQMsg.html("<div class=\"alert alert-warning\">You need to enter both Answer 1 and Answer 2</div>");
            }
            else
            {
                if(strMAns3 === "" && strMAns4 !== "")
                {
                    divEMCQMsg.html("<div class=\"alert alert-warning\">You cannot keep Answer 3 empty and give Answer 4</div>");
                }
                else
                {
                    if(intMCQAns === undefined) // didn't select the right answer
                    {
                        divEMCQMsg.html("<div class=\"alert alert-warning\">You need to select one answer</div>");
                    }
                    else
                    {
                        if($("#txtEMAns" + intMCQAns).val() === "")
                        {
                            divEMCQMsg.html("<div class=\"alert alert-warning\">You cannot select an empty answer</div>");
                        }
                        else
                        {
                            if(intMCQMarks > 0)
                            {
                                // get scroll position
                                var scrollPos = $(window).scrollTop();
        
                                // save
                                btnMCQSave.button('loading'); // set saving message on the button

                                $.post("create.alink.php",
                                            {ret_type:"u_mcq", exm:intExmNo, qstn:intQstNo, qst:strMQst,
                                             ans1:strMAns1, ans2:strMAns2,
                                             ans3:strMAns3, ans4:strMAns4, crt:intMCQAns, mks:intMCQMarks},
                                            function(result)
                                            {
                                                if(result === "false")
                                                {
                                                    divEMCQMsg.html("<div class=\"alert alert-danger\">Error saving question</div>");
                                                }
                                                else
                                                {
                                                    // keep scroll still
                                                    $(window).scrollTop(scrollPos);
                                                    
                                                    // fade out and replace
                                                    $("#divMCQ" + intQstNo).fadeOut('slow', function (){
                                                        $(this).replaceWith(result);
                                                    });
    
                                                    // close modal
                                                    $("#mdlEditQst").modal('hide');
                                                    
                                                    // fade in
                                                    $("#divMCQ" + intQstNo).fadeIn('slow');
                                                }

                                                btnMCQSave.button('reset'); // reset when save cycle is complete
                                            });
                                }
                                else
                                {
                                    divEMCQMsg.html("<div class=\"alert alert-warning\">You need to enter marks for this question</div>");
                                }
                        }
                    }            
                }
            }
        }
    }
}

function deleteMCQ(intExmNo, intQstNo)
{
    // get scroll position
    var scrollPos = $(window).scrollTop();
        
    // ask for confirmation
    if(confirm("Are you sure you want to delete Question No: " + intQstNo))
    {
        // delete
        $.post("create.alink.php",
                {ret_type:"d_mcq", exm:intExmNo, qst:intQstNo},
                function(result)
                {
                    if(result === "true")
                    {
                        // keep scroll still
                        $(window).scrollTop(scrollPos);
                        
                        $("#divMCQ" + intQstNo).fadeOut('slow', function (){
                            $(this).remove();
                        });
                    }
                    else
                    {
                        $("#divMCQ" + intQstNo).prepend("<div class=\"col-md-10 col-md-offset-1\" align=\"center\">\n\
                                                <div class=\"alert alert-danger\">Error deleting question " + intQstNo + "</div></div>");
                    }
                });
    } 
}


// Structured
function clearAStr()
{
    $("#divAStrMsg").html("<div class=\"alert alert-success\">OK! Add next question</div>");
    $("#txtSQst").val("");
    $("#txtSAns").val("");
    $("#txtStrMarks").val("");
    
    // scroll to bottom of page
    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
}

function addStr(intExmNo)
{
    var strSQst = $("#txtSQst").val();
    var strSAns = $("#txtSAns").val();
    var intStrMarks = parseInt($("#txtStrMarks").val()); 
    var btnStrSave = $("#btnStrSave");
    var divAStrMsg = $("#divAStrMsg");
    
    // check
    
    if(strSQst === "")
    {
        divAStrMsg.html("<div class=\"alert alert-warning\">You forgot to ask the Question</div>");
    }
    else
    {
        if(strSAns === "")
        {
            divAStrMsg.html("<div class=\"alert alert-warning\">You forgot to add the Answer</div>");
        }
        else
        {
            if(intStrMarks > 0)
            {
                // save
                btnStrSave.button('loading'); // set saving message on the button

                $.post("create.alink.php",
                            {ret_type:"a_str", exm:intExmNo, qst:strSQst,
                             ans:strSAns, mks:intStrMarks},
                            function(result)
                            {
                                if(result === "false")
                                {
                                    divAStrMsg.html("<div class=\"alert alert-danger\">Error saving question</div>");
                                }
                                else
                                {
                                    // append question to page
                                    $("#divExamPaper").append(result);

                                    clearAStr();
                                }

                                btnStrSave.button('reset'); // reset when save cycle is complete
                            });
            }
            else
            {
                divAStrMsg.html("<div class=\"alert alert-warning\">You need to enter marks for this question</div>");
            }
        }
    }
}

function showStr(intExmNo, intQstNo)
{
    $.post("create.alink.php",
            {ret_type:"v_qst", exm:intExmNo, qst:intQstNo},
            function(result)
            {
                $("#divEditQst").html(result);
                $("#mdlEditQst").modal('show');
            });
}

function editStr(intExmNo, intQstNo)
{
    var strESQst = $("#txtESQst").val();
    var strESAns = $("#txtESAns").val();
    var intStrMarks = parseInt($("#txtEStrMarks").val()); 
    var btnEStrSave = $("#btnEStrSave");
    var divEStrMsg = $("#divEStrMsg");
    
    // check
    
    if(strESQst === "")
    {
        divEStrMsg.html("<div class=\"alert alert-warning\">You forgot to ask the Question</div>");
    }
    else
    {
        if(strESAns === "")
        {
            divEStrMsg.html("<div class=\"alert alert-warning\">You forgot to add the Answer</div>");
        }
        else
        {
            if(intStrMarks > 0)
            {
                // get scroll position
                var scrollPos = $(window).scrollTop();
        
                // save
                btnEStrSave.button('loading'); // set saving message on the button

                $.post("create.alink.php",
                            {ret_type:"u_str", exm:intExmNo, qstn:intQstNo, qst:strESQst,
                             ans:strESAns, mks:intStrMarks},
                            function(result)
                            {
                                if(result === "false")
                                {
                                    divEStrMsg.html("<div class=\"alert alert-danger\">Error saving question</div>");
                                }
                                else
                                {
                                    // keep scroll still
                                    $(window).scrollTop(scrollPos);
                                    
                                    // fade out and replace
                                    $("#divStr" + intQstNo).fadeOut('slow', function (){
                                        $(this).replaceWith(result);
                                    });

                                    // close modal
                                    $("#mdlEditQst").modal('hide');

                                    // fade in
                                    $("#divStr" + intQstNo).fadeIn('slow');
                                }

                                btnEStrSave.button('reset'); // reset when save cycle is complete
                            });
            }
            else
            {
                divEStrMsg.html("<div class=\"alert alert-warning\">You need to enter marks for this question</div>");
            }
        }
    }
}

function deleteStr(intExmNo, intQstNo)
{
    // get scroll position
        var scrollPos = $(window).scrollTop();
        
    // ask for confirmation
    if(confirm("Are you sure you want to delete Question No: " + intQstNo))
    {
        // delete
        $.post("create.alink.php",
                {ret_type:"d_str", exm:intExmNo, qst:intQstNo},
                function(result)
                {
                    if(result === "true")
                    {
                        // keep scroll still
                        $(window).scrollTop(scrollPos);
                        
                        $("#divStr" + intQstNo).fadeOut('slow', function (){
                            $(this).remove();
                        });
                    }
                    else
                    {
                        $("#divStr" + intQstNo).prepend("<div class=\"col-md-10 col-md-offset-1\" align=\"center\">\n\
                                <div class=\"alert alert-danger\">Error deleting question " + intQstNo + "</div></div>");
                    }
                });
    }
}
