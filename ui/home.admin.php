<?php
//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/logic/UserLogic.php');
//_____________________________________________________________________________

$UserLogic = new UserLogic();

$resStudent = $UserLogic->getApprStudentList();
$resInstructor = $UserLogic->getApprInstructorList();

?>

    <div class="row">
   <div class="col-md-8 col-md-offset-2">
       <P>&nbsp;</P>
   </div>
</div>

    <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <P>&nbsp;</P>
       </div>
    </div>
  

<!-- Tabs ------------------------------------------------------------------ -->
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            
          <li class="active">
              <a href="#divInstructors" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-leaf"></span>&nbsp;&nbsp;&nbsp;Instructors
              </a>
          </li>
          
          <li>
              <a href="#divStudents" role="tab" data-toggle="tab">
                  <span class="glyphicon glyphicon-fire"></span>&nbsp;&nbsp;&nbsp;Students
              </a>
          </li>
          
        </ul>
    </div>
</div>
<!-- /Tabs ----------------------------------------------------------------- -->



<!-- Tab panes ------------------------------------------------------------- -->

<div class="tab-content">
    
    <div class="tab-pane active" id="divInstructors">
    
    <!-- Instructors ------------------------------------------------------- -->

    <div class="row">
        &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12 custom-page-header">
            <h4>Instructors to be approved</h4>
        </div>
    </div>
    
    <div class="row">
    &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-hover">
                
                <?php
                    while($insRow = mysql_fetch_array($resInstructor))
                    {
                        print('
                        <tr id="trIns' . $insRow["usr_nic"] .'">
                          <td width="10%">' . $insRow["usr_nic"] .'</td>
                          <td width="72%">' . $insRow["usr_name"]. '</td>
                          <td width="8%">
                            <a href="#mdlUser" data-toggle="modal" onclick="viewInstructor(\'' . $insRow["usr_nic"] . '\')" class="btn btn-block btn-xs btn-default">View</a>
                                </td>
                          <td width="8%" id="divAprveIns' . $insRow["usr_nic"] . '">'
                                . '<a href="#" onclick="approveInstructor(\'' . $insRow["usr_nic"] . '\')"class="btn btn-block btn-xs btn-primary">Approve</a>
                          </td>
                        </tr>');
                    }
                ?>
                
            </table>
        </div>
   </div>

    <!-- /Instructors ------------------------------------------------------ -->

</div>
   
    <div class="tab-pane" id="divStudents">
    
    <!-- Students --------------------------------------------------------- -->

    <div class="row">
        &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12 custom-page-header">
            <h4>Students to be approved</h4>
        </div>
    </div>
    
    <div class="row">
    &nbsp;
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-hover">
                
                <?php
                    while($stdRow = mysql_fetch_array($resStudent))
                    {
                        print('
                        <tr id="trStd' . $stdRow["usr_nic"] . '">
                          <td width="10%">' . $stdRow["usr_nic"] .'</td>
                          <td width="72%">' . $stdRow["usr_name"]. '</td>
                          <td width="8%">
                            <a href="#mdlUser" data-toggle="modal" onclick="viewStudent(\'' . $stdRow["usr_nic"] . '\')" class="btn btn-block btn-xs btn-default">View</a>
                          </td>
                          <td width="8%" id="divAprveStd' . $stdRow["usr_nic"] . '">'
                                . '<a href="#" onclick="approveStudent(\'' . $stdRow["usr_nic"] . '\')" class="btn btn-block btn-xs btn-primary">Approve</a>
                          </td>
                        </tr>');
                    }
                ?>
                
            </table>
        </div>
   </div>

    <!-- /Students --------------------------------------------------------- -->

</div>
    
</div>

<!-- /Tab panes ------------------------------------------------------------ -->


<!-- modals -->

<div class="modal fade" id="mdlUser" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body" id="mdlUser_content">
                User details go here
            </div>
            
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>
