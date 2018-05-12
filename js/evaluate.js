function reloadPage()
{
    location.reload(true);
}

function evalMCQ(intExmNo)
{
    var btnEvalMCQ = $("#btnEvalMCQ");

    // save
    btnEvalMCQ.button('loading'); // set saving message on the button

    $.post("evaluate.alink.php",
            {ret_type:"e_mcq", exm:intExmNo},
            function(result)
            {
                if(result !== "true")
                {
                    alert("Save Failed " + result);
                }

                btnEvalMCQ.button('reset'); // reset when save cycle is complete

                // reload page
                reloadPage();
            });

	
	
}

function evalStr(intExmNo, strStdID)
{
    $.post("evaluate.alink.php",
            {ret_type:"v_str", exm:intExmNo, id:strStdID},
            function(result)
            {
                $("#divStrQst").html(result);
                $("#mdlEvalStr").modal('show');
            });
}

function updateMarks(strStdID, intExmNo, intQstPsudoNo)
{
    var intMarks = parseInt($("#txtMrk" + intQstPsudoNo).val());
    var intMax = parseInt($("#hdnMax" + intQstPsudoNo).val());
    
    if(intMarks != NaN)
    {
		if(intMarks > intMax)
		{
			alert("You cannot enter a value greater than " + intMax);
			
		}
		else
		{
			$.post("evaluate.alink.php",
					{ret_type:"u_str", exm:intExmNo, id:strStdID, 
						qst:intQstPsudoNo, mks:intMarks},
					function(result)
					{
						if(result == "false")
						{
							alert("You have entered an incorrect value");
						}
					});
		}
	}
	else
	{
		alert("Enter a valid number");
	}
}

function completeStrEval()
{
	var strStdID = $("#hdnStdID").val();
	var intExmNo = $("#hdnExmNo").val();
	var btnEvalComp = $("#btnEvalComp");
	
	btnEvalComp.button('loading'); // set saving message on the button
	
    $.post("evaluate.alink.php",
                {ret_type:"c_str", exm:intExmNo, id:strStdID},
                function(result)
                {
                    if(result !== "true")
                    {
                        alert(result);
                    }
                    else
                    {
						$("#mdlEvalStr").modal('hide');
						reloadPage();
					}
					
                    btnEvalComp.button('reset'); // reset when save cycle is complete
                    
                });
}

function prepareResults(intExmNo)
{
	var btnPrepare = $("#btnPrepare");
	
	btnPrepare.button('loading'); // set saving message on the button
	
    $.post("evaluate.alink.php",
                {ret_type:"p_res", exm:intExmNo},
                function(result)
                {
                    if(result !== "true")
                    {
                        alert(result);
                    }
                    else
                    {
						reloadPage();
					}
					
                    btnPrepare.button('reset'); // reset when save cycle is complete
                    
                });
}

function publishResults(intExmNo)
{
	if(confirm("You are about to publish the results of this exam.\n Do you want to proceed?"))
	{
		var btnPublish = $("#btnPublish");
		
		btnPublish.button('loading'); // set saving message on the button
		
		$.post("evaluate.alink.php",
					{ret_type:"r_res", exm:intExmNo},
					function(result)
					{
						if(result !== "true")
						{
							alert(result);
						}
						else
						{
							// redirect to home page
							window.location.href = "http://localhost/exam/home.php";
						}
						
						btnPublish.button('reset'); // reset when save cycle is complete
						
					});
	}
}
