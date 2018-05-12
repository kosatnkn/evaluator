$(document).ready(function()
{
	updateFooter();
});


function updateFooter()
{
    var strStatus = $("#hdnStatus").val();
    
    if(strStatus == "O")
    {
		var intQstCount = $("#hdnQstCount").val();
		var strQstRemaining = $("#hdnQstRemaining").val();
		var intQstCurrent = $("#hdnQstCurrent").val();
    
		$("#divQst").html("<p class=\"navbar-text\"><strong>" + intQstCurrent + " <em>of</em> " + intQstCount +"</strong></p>");
		$("#divTime").html("<p class=\"navbar-text\"><strong>" + strQstRemaining + 
							"</strong><em><small>&nbsp;&nbsp;&nbsp;remaining</small></em></p>");
	}
	else if(strStatus == "C")
	{
		$("#divQst").html("");
		$("#divTime").html("");
		
		resetButtons();
	}
}

function resetButtons()
{
	var intQstPrev = $("#hdnQstPrev").val();
	var intQstNext = $("#hdnQstNext").val();
	
	var btnPrev = $("#btnPrev");
	var btnNext = $("#btnNext");
	
	var btnSave = $("#btnSave");
	var btnComplete = $("#btnComplete");
	
	var strStatus = $("#hdnStatus").val();
    
    if(strStatus == "O")
    {
		// Previous Button
		if(intQstPrev == 0)
		{
			// disable prev
			btnPrev.attr("disabled", "disabled");
		}
		else
		{
			// enable prev
			btnPrev.removeAttr("disabled");
		}
		
		
		// Next Button
		if(intQstNext == 0)
		{
			// disable next
			btnNext.attr("disabled", "disabled");
		}
		else
		{
			// enable next
			btnNext.removeAttr("disabled");
		}
	}
	else if(strStatus == "C")
	{
		btnPrev.attr("disabled", "disabled");
		btnNext.attr("disabled", "disabled");
		btnSave.attr("disabled", "disabled");
		btnComplete.attr("disabled", "disabled");
	}
}


function btnSave_Click()
{
	// initialize
	var intQstCount = $("#hdnQstCount").val();
	var strQstRemaining = $("#hdnQstRemaining").val();
	var intQstPrev = $("#hdnQstPrev").val();
	var intQstCurrent = $("#hdnQstCurrent").val();
	var intQstNext = $("#hdnQstNext").val();
	var strQstType = $("#hdnQstType").val();
	
	var strStdID = $("#hdnStdID").val();
	var intExmNo = $("#hdnExmNo").val();
	
	var btnSave = $("#btnSave");
	
	var answer;
	
	if(strQstType == "M") // MCQ
	{
		answer = $("#rdoAnswer:checked").val();
		
		if(answer !== undefined)
		{
			// save
			btnSave.button('loading'); // set saving message on the button

			$.post("take.alink.php",
					{ret_type:"s_ans", id:strStdID, exm:intExmNo, qst:intQstCurrent, ans:answer},
					 function(result)
					 {
					   if(result !== "true")
					   {
							alert("Save Failed " + result);
					   }
					   
						 btnSave.button('reset'); // reset when save cycle is complete
					  });
		}
		else
		{
			alert("Give an answer");
		}
	}
	else if(strQstType == "S") // structured
	{
		answer = $("#txtStrAns").val();
		
		if(answer !== "")
		{
			// save
			btnSave.button('loading'); // set saving message on the button

			$.post("take.alink.php",
					{ret_type:"s_ans", id:strStdID, exm:intExmNo, qst:intQstCurrent, ans:answer},
					 function(result)
					 {
					   if(result !== "true")
					   {
							alert("Save Failed " + result);
					   }
					   
						 btnSave.button('reset'); // reset when save cycle is complete
					  });
		}
		else
		{
			alert("Give an answer");
		}
	}
	
}

function btnPrev_Click()
{
	// trigger 'Save' click
	btnSave_Click();
	
	var strStdID = $("#hdnStdID").val();
	var intExmNo = $("#hdnExmNo").val();
	var intQstPrev = $("#hdnQstPrev").val();
	
	var btnPrev = $("#btnPrev");
	
	// load all data
		btnPrev.button('loading'); // set saving message on the button
		
		if(intQstPrev != 0)
		{
			$.post("take.alink.php",
				{ret_type:"v_pre", exm:intExmNo, pre:intQstPrev, id:strStdID},
				 function(result)
				 {
				   if(result === "false")
                   {
						alert(result);
                   }
                   else
                   {
					 // show previous
					 
					 // fade out and replace
                     $("#divShowQst").fadeOut('slow', function (){
                          $(this).html(result);
                       });
					
					// fade in
                     $("#divShowQst").fadeIn('slow', function(){
                     
						// update footer
						updateFooter();
						
						// update burtton status
						resetButtons();
						});
                   }
				   
					 btnPrev.button('reset'); // reset when save cycle is complete
					 
						
				  });
		}
	
	
}

function btnNext_Click()
{
	// trigger 'Save' click
	btnSave_Click();
	
	var strStdID = $("#hdnStdID").val();
	var intExmNo = $("#hdnExmNo").val();
	var intQstNext = $("#hdnQstNext").val();
	
	var btnNext = $("#btnNext");
	
	// load all data
	btnNext.button('loading'); // set saving message on the button
		
		if(intQstNext != 0)
		{
			$.post("take.alink.php",
				{ret_type:"v_nxt", exm:intExmNo, nxt:intQstNext, id:strStdID},
				 function(result)
				 {
				   if(result === "false")
                   {
						alert(result);
                   }
                   else
                   {
					 // show next
					 
					 // fade out and replace
                     $("#divShowQst").fadeOut('slow', function (){
                          $(this).html(result);
                       });
					
					// fade in
                     $("#divShowQst").fadeIn('slow', function(){
                     
						 // update footer
						updateFooter();
						
						// update burtton status
						resetButtons();
						});
                   }
				   
					 btnNext.button('reset'); // reset when save cycle is complete
					 
				  });
		}
}

function btnComplete_Click()
{
	if(confirm("Are you sure you want to complete this exam"))
	{
			// save current answer
			try
			{
				// trigger 'Save' click
				
				btnSave_Click();
			}
			catch(e)
			{
				alert("Error Saving");
			}
			
            // redirect to home page
            window.location.href = "http://localhost/exam/home.php";
	}
}
