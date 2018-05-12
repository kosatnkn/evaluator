<?php
/**
 * Each type of variable is prefixed using following conventions
 *      str - String
 *      int - Integer
 *      bln - Boolean
 *      arr - Array
 *      res - Resource
 */

//imports______________________________________________________________________
require_once($_SERVER['DOCUMENT_ROOT'].'/exam/adpt/DBAdapter.php');
//_____________________________________________________________________________


/**
 * This class handles users and courses
 */
class UserLogic
{
    /**
     * Get login details specific to an ID number
     * @param String $strUserID User ID
     * @param String $strPassword Password
     * @return Resource MySQL Dataset
     */
    public function getLogin($strUserID, $strPassword)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT usr_nic,
                        CONCAT(usr_first_name, ' ', usr_last_name) AS usr_name,
                        usr_type,
                        usr_approved
                   FROM usr_user
                   WHERE usr_nic = '$strUserID' AND 
                         usr_password = OLD_PASSWORD('$strPassword')";

        return $DBAdapter->runQuery($query);
    }
    
    
    /**
     * Check wether User ID already exists
     * @param String $strUserID User ID
     * @return Boolean 'true' if exists, 'false' if not
     */
    public function checkUserID($strUserID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT COUNT(usr_nic) AS usr_count 
                  FROM usr_user
                  WHERE usr_nic = '$strUserID'";

        if(mysql_result($DBAdapter->runQuery($query),0,0) == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Check wether Registration Number already exists
     * @param String $strRegNo Registration Number
     * @return Boolean 'true' if exists, 'false' if not
     */
    public function checkRegNo($strRegNo)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT COUNT(usr_nic) AS usr_count 
                  FROM usr_user
                  WHERE usr_reg_no = '$strRegNo'";

        if(mysql_result($DBAdapter->runQuery($query),0,0) == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    public function getField()
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT fld_code, fld_name
                  FROM sbj_field";

        return $DBAdapter->runQuery($query);
    }

    
    /**
     * Return a collection of courses under one field
     * @param String $strFieldCode Field Code
     * @return Resource MySQL Dataset
     */
    public function getCourseByField($strFieldCode)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT crs_code, crs_name
                  FROM sbj_course
                  WHERE crs_fld_code = '$strFieldCode'";

        return $DBAdapter->runQuery($query);
    }
    
    
    /**
     * Return a collection of subjects under one course
     * @param String $strCourseCode Course Code
     * @return Resource MySQL Dataset
     */
    public function getSubjectsByCourse($strCourseCode)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT CS.csb_sbj_code AS sbj_code,
                        S.sbj_name,
                        CS.csb_semester AS sbj_semester,
                        CS.csb_compulsory AS sbj_compulsary
                   FROM sbj_crs_sbj CS, sbj_course C, sbj_subject S
                  WHERE CS.csb_crs_code = C.crs_code
                    AND CS.csb_sbj_code = S.sbj_code
                    AND CS.csb_crs_code = '$strCourseCode'
                    AND CS.csb_year = (SELECT MAX(gbl_year) FROM globals)
                 ORDER BY CS.csb_semester ASC, CS.csb_compulsory DESC";

        return $DBAdapter->runQuery($query);
    }
    
    
    public function getSubjectByField($intFieldCode)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT CS.csb_sbj_code AS sbj_code, S.sbj_name
                    FROM sbj_crs_sbj CS, sbj_subject S, sbj_course C
                   WHERE CS.csb_sbj_code = S.sbj_code
                     AND CS.csb_crs_code = C.crs_code
                     AND C.crs_fld_code = $intFieldCode
                     AND csb_year = (SELECT MAX(gbl_year) FROM globals)";

        return $DBAdapter->runQuery($query);
    }
    
    
    public function getCourseBySubject($strSbjCode)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT CS.csb_crs_code AS crs_code, C.crs_name
                    FROM sbj_crs_sbj CS, sbj_course C
                    WHERE csb_crs_code = C.crs_code
                    AND CS.csb_sbj_code = '$strSbjCode'
                    AND CS.csb_year = (SELECT MAX(gbl_year) FROM globals)";

        return $DBAdapter->runQuery($query);
    }

    

    public function getStudent($strStudentID)
    {
        $DBAdapter = new DBAdapter();
        
        $query = "SELECT 
                    usr_nic,
                    usr_reg_no,
                    usr_first_name,
                    usr_middle_name,
                    usr_last_name,
                    usr_gender,
                    usr_email
                FROM usr_user
                WHERE usr_nic = '$strStudentID'";
        
        $arrStudent;
        
        $result = $DBAdapter->runQuery($query);
        
        while($student = mysql_fetch_array($result))
        {
            $arrStudent["usr_nic"] = $student["usr_nic"];
            $arrStudent["usr_reg_no"] = $student["usr_reg_no"];
            $arrStudent["usr_fname"] = $student["usr_first_name"];
            $arrStudent["usr_mname"] = $student["usr_middle_name"];
            $arrStudent["usr_lname"] = $student["usr_last_name"];
            $arrStudent["usr_gender"] = $student["usr_gender"] == "M" ? "Male" : "Female";
            $arrStudent["usr_email"] = $student["usr_email"];
        }
        
	return $arrStudent;
    }
    
    
    public function getStdCourseSubjects($strStudentID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT 
                        C.crs_name, CONCAT(S.sbj_code, ' - ', S.sbj_name) AS sbj_name, SC.csb_semester
                    FROM
                        student_subject SS,
                        sbj_course C,
                        sbj_subject S,
                            sbj_crs_sbj SC
                    WHERE
                        SS.std_crs_code = C.crs_code
                            AND SS.std_sbj_code = S.sbj_code
                                    AND SS.std_sbj_code = SC.csb_sbj_code
                                    AND SS.std_crs_code = SC.csb_crs_code
                            AND SS.std_id = '$strStudentID'
                            AND SS.std_year = (SELECT 
                                MAX(gbl_year)
                            FROM
                                globals)
                    ORDER BY S.sbj_code ASC";

	return $DBAdapter->runQuery($query);
    }

    
    public function addStudent($arrStudent, $arrSubjects)        
    {
        $DBAdapter = new DBAdapter();
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        // add student
        $query = "INSERT INTO usr_user
                            (usr_nic,
                             usr_reg_no,
                             usr_password,
                             usr_first_name,
                             usr_middle_name,
                             usr_last_name,
                             usr_gender,
                             usr_dob,
                             usr_marital,
                             usr_email,
                             usr_type,
                             usr_approved,
                             usr_rec_date)
                     VALUES (
                               '$arrStudent[usr_nic]',
                               '$arrStudent[usr_reg_no]',
                               OLD_PASSWORD('$arrStudent[usr_password]'),
                               '$arrStudent[usr_first_name]',
                               '$arrStudent[usr_middle_name]',
                               '$arrStudent[usr_last_name]',
                               '$arrStudent[usr_gender]',
                               '$arrStudent[usr_dob]',
                               'S',
                               '$arrStudent[usr_email]',
                               'S',
                               'N',
                               NOW()
                            )";
        
        // add the student
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // if that is successful then add course details
            $queryComp = "";
            $queryOpt = "";
            
            $blnCompChk = true;
            $blnOptChk = true;

            // add compulsary subjects
            $queryComp = "INSERT INTO student_subject (std_id, std_crs_code, std_sbj_code, std_year)
                            SELECT 
                                '$arrStudent[usr_nic]' AS std_id,
                                CS.csb_crs_code AS std_crs_code,
                                CS.csb_sbj_code AS std_sbj_code,
                                (SELECT MAX(gbl_year) FROM globals) AS std_year
                            FROM
                                sbj_crs_sbj CS,
                                sbj_course C,
                                sbj_subject S
                            WHERE
                                CS.csb_crs_code = C.crs_code
                                    AND CS.csb_sbj_code = S.sbj_code
                                    AND CS.csb_crs_code = 'CMP01'
                                    AND CS.csb_year = (SELECT MAX(gbl_year) FROM globals)
                                    AND CS.csb_compulsory = true; ";   

            $blnCompChk = $DBAdapter->runQueryToConn($queryComp, $resConn);
            
                // add optional subjects
                if(!empty($arrSubjects))
                {
                    for ($i=0; $i < count($arrSubjects); $i++)
                    {
                        $queryOpt = "INSERT INTO student_subject (std_id, std_crs_code, std_sbj_code, std_year)
                                     VALUES('$arrStudent[usr_nic]',
                                            '$arrStudent[usr_crs_code]',
                                            '$arrSubjects[$i]',
                                            (SELECT MAX(gbl_year) FROM globals));";
                        
                        $blnOptChk = $DBAdapter->runQueryToConn($queryOpt, $resConn);
                        
                        if($blnOptChk){continue;}else{break;}
                        
                    }
                }            
            
            if($blnCompChk && $blnOptChk)
            {
                // when that is successful as well, commit and close
                $DBAdapter->commit($resConn);
                $DBAdapter->close($resConn);
                
                return true;
            }
            else
            {
                // rollback and close
                $DBAdapter->rollback($resConn, true);
                
                return false;
            }
        }
        else
        {
            // rollback and close
            $DBAdapter->rollback($resConn, true);
                
            return false;
        }
        
    }
    
    
    public function updateSubjects($arrStudent, $arrSubjects)
    {
        $DBAdapter = new DBAdapter();
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        // delete existing course details
        $queryDel = "DELETE FROM student_subject
                        WHERE std_id = '$arrStudent[usr_nic]'
                        AND std_year = (SELECT MAX(gbl_year) FROM globals)";
        
        $DBAdapter->runQueryToConn($queryDel, $resConn);
        
        // then add course details
        $querySbj = "";

        $blnCheck = true;
        
        // add subjects
        if(!empty($arrSubjects))
        {
            for ($i=0; $i < count($arrSubjects); $i++)
            {
                $querySbj = "INSERT INTO student_subject (std_id, std_crs_code, std_sbj_code, std_year)
                             VALUES('$arrStudent[usr_nic]',
                                    '$arrStudent[usr_crs_code]',
                                    '$arrSubjects[$i]',
                                    (SELECT MAX(gbl_year) FROM globals));";

                $blnCheck = $DBAdapter->runQueryToConn($querySbj, $resConn);

                if($blnCheck){continue;}else{break;}

            }
        }            

        if($blnCheck)
        {
            // when that is successful as well, commit and close
            $DBAdapter->commit($resConn);
            $DBAdapter->close($resConn);

            return true;
        }
        else
        {
            // rollback and close
            $DBAdapter->rollback($resConn, true);

            return false;
        }
    }


    public function getInstructor($strInstructorID)
    {
        $DBAdapter = new DBAdapter();
        
        $query = "SELECT 
                    usr_nic,
                    usr_first_name,
                    usr_middle_name,
                    usr_last_name,
                    usr_gender,
                    usr_email
                FROM usr_user
                WHERE usr_nic = '$strInstructorID'";
        
        $arrInstructor;
        
        $result = $DBAdapter->runQuery($query);
        
        while($instructor = mysql_fetch_array($result))
        {
            $arrInstructor["usr_nic"] = $instructor["usr_nic"];
            $arrInstructor["usr_fname"] = $instructor["usr_first_name"];
            $arrInstructor["usr_mname"] = $instructor["usr_middle_name"];
            $arrInstructor["usr_lname"] = $instructor["usr_last_name"];
            $arrInstructor["usr_gender"] = $instructor["usr_gender"] == "M" ? "Male" : "Female";
            $arrInstructor["usr_email"] = $instructor["usr_email"];
        }
        
	return $arrInstructor;
    }

    
    public function getInsDepartmentSubject($strInstructorID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT 
                        S.sbj_code, CONCAT(S.sbj_code, ' - ', S.sbj_name) AS sbj_name, SI.ins_crs_code AS crs_code, C.crs_name
                    FROM
                        instructor_subject SI,
                        sbj_subject S, sbj_course C
                    WHERE
                        SI.ins_sbj_code = S.sbj_code
                    AND SI.ins_crs_code = C.crs_code
                            AND SI.ins_year = (SELECT MAX(gbl_year) FROM globals)
                            AND SI.ins_id = '$strInstructorID'
                    ORDER BY S.sbj_code ASC";

	return $DBAdapter->runQuery($query);
    }


    public function getInstructorTitle()
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT tit_code, tit_desc
                  FROM usr_title
                  WHERE tit_code <> 0";

	return $DBAdapter->runQuery($query);
    }

    
    public function addInstructor($arrInstructor, $arrSubjects)
    {
        $DBAdapter = new DBAdapter();
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        // add instructor
        
        $query = "INSERT INTO usr_user
                            (usr_nic,
                             usr_password,
                             usr_title,
                             usr_first_name,
                             usr_middle_name,
                             usr_last_name,
                             usr_gender,
                             usr_dob,
                             usr_marital,
                             usr_email,
                             usr_type,
                             usr_approved,
                             usr_rec_date)
                     VALUES (
                               '$arrInstructor[usr_nic]',
                               OLD_PASSWORD('$arrInstructor[usr_password]'),
                               $arrInstructor[usr_title],
                               '$arrInstructor[usr_first_name]',
                               '$arrInstructor[usr_middle_name]',
                               '$arrInstructor[usr_last_name]',
                               '$arrInstructor[usr_gender]',
                               '$arrInstructor[usr_dob]',
                               '$arrInstructor[usr_marital]',
                               '$arrInstructor[usr_email]',
                               'I',
                               'N',
                               NOW()
                            )";
        
        // add the student
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // if that is successful then add course details
            $blnChk = true;
            
            if(!empty($arrSubjects))
            {
                $strSbjCode = "";
                $strCrsCode = "";
                
                for ($i=0; $i < count($arrSubjects); $i++)
                {
                    $strSbjCode = $arrSubjects[$i][0];
                    $strCrsCode = $arrSubjects[$i][1];
                    
                    $query = "INSERT INTO instructor_subject (ins_id, ins_sbj_code, ins_crs_code, ins_year)
                                 VALUES('$arrInstructor[usr_nic]',
                                        '$strSbjCode',
                                        '$strCrsCode',
                                        (SELECT MAX(gbl_year) FROM globals));";

                    $blnChk = $DBAdapter->runQueryToConn($query, $resConn);

                    if($blnChk){continue;}else{break;}
                }
            }

            if($blnChk)
            {
                // when that is successful as well, commit and close
                $DBAdapter->commit($resConn);
                $DBAdapter->close($resConn);
                
                return true;
            }
            else
            {
                // rollback and close
                $DBAdapter->rollback($resConn, true);
                
                return false;
            }
        }
        else
        {
            // rollback and close
            $DBAdapter->rollback($resConn, true);
                
            return false;
        }
    }
    
    
    public function updateInsSub($arrInstructor, $arrSubjects)
    {
        $DBAdapter = new DBAdapter();
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        // delete subjects
        $queryDel = "DELETE FROM instructor_subject
                     WHERE ins_id = '$arrInstructor[usr_nic]'
                        AND ins_year = (SELECT MAX(gbl_year) FROM globals)";             
        
        $DBAdapter->runQueryToConn($queryDel, $resConn);
        
        // then add course details
        $blnChk = true;

        if(!empty($arrSubjects))
        {
            $strSbjCode = "";
            $strCrsCode = "";
                
            for ($i=0; $i < count($arrSubjects); $i++)
            {
                $strSbjCode = $arrSubjects[$i][0];
                $strCrsCode = $arrSubjects[$i][1];
                
                $query = "INSERT INTO instructor_subject (ins_id, ins_sbj_code, ins_crs_code, ins_year)
                                 VALUES('$arrInstructor[usr_nic]',
                                        '$strSbjCode',
                                        '$strCrsCode',
                                        (SELECT MAX(gbl_year) FROM globals));";

                $blnChk = $DBAdapter->runQueryToConn($query, $resConn);

                if($blnChk){continue;}else{break;}
            }
        }

        if($blnChk)
        {
            // when that is successful as well, commit and close
            $DBAdapter->commit($resConn);
            $DBAdapter->close($resConn);

            return true;
        }
        else
        {
            // rollback and close
            $DBAdapter->rollback($resConn, true);

            return false;
        }
    }

        public function getApprStudentList()
    {
        // get student list for approval

	$DBAdapter = new DBAdapter();
    
        $query = "SELECT usr_nic, CONCAT(usr_first_name, ' ', usr_middle_name, ' ', usr_last_name) AS usr_name
                  FROM usr_user
                  WHERE usr_type = 'S'
                        AND usr_approved = 'N'
                  ORDER BY usr_first_name ASC";

	return $DBAdapter->runQuery($query);
    }

    
    public function getApprInstructorList()
    {
        // get instructor list for approval

	$DBAdapter = new DBAdapter();
    
        $query = "SELECT usr_nic, CONCAT(usr_first_name, ' ', usr_middle_name, ' ', usr_last_name) AS usr_name
                  FROM usr_user
                  WHERE usr_type = 'I'
                        AND usr_approved = 'N'
                  ORDER BY usr_first_name ASC";

	return $DBAdapter->runQuery($query);
    }
    
    
    public function approveUser($userID)
    {
	$DBAdapter = new DBAdapter();
    
        $query = "UPDATE usr_user
                  SET usr_approved = 'Y'
                  WHERE usr_nic = '$userID'";

	return $DBAdapter->runQueryTrans($query);
    }

    
    public function changePassword($strUserID, $strOldPwd, $strNewPwd)
    {
        $DBAdapter = new DBAdapter();
        
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        $query = "UPDATE usr_user
                  SET usr_password = OLD_PASSWORD('$strNewPwd')
                  WHERE usr_nic = '$strUserID' 
                      AND usr_password = OLD_PASSWORD('$strOldPwd')";

        $DBAdapter->runQueryToConn($query, $resConn);
        
        if(mysql_affected_rows($resConn) == 1)
        {
            $DBAdapter->commit($resConn);
            $DBAdapter->close($resConn);
                
            return true;
        }
        else
        {
            $DBAdapter->rollback($resConn, true);
           
            return false;
        }
    }
    
    
    public function editUser($arrUser)
    {
        // edit student
        $DBAdapter = new DBAdapter();
        
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        $query = "UPDATE usr_user
                  SET 	usr_first_name = '$arrUser[usr_first_name]',
                        usr_middle_name = '$arrUser[usr_middle_name]',
                        usr_last_name = '$arrUser[usr_last_name]',
                        usr_gender = '$arrUser[usr_gender]',
                        usr_email = '$arrUser[usr_email]',
                        usr_rec_date = NOW()
                  WHERE usr_nic = '$arrUser[usr_nic]'";
        
        $DBAdapter->runQueryToConn($query, $resConn);
        
        if(mysql_affected_rows($resConn) == 1)
        {
            $DBAdapter->commit($resConn);
            $DBAdapter->close($resConn);
                
            return true;
        }
        else
        {
            $DBAdapter->rollback($resConn, true);
           
            return false;
        }
    }
    
    
    public function getSubjectByInstructor($strInstructorID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT S.sbj_code, CONCAT(S.sbj_code, ' - ', S.sbj_name) AS sbj_name
                    FROM instructor_subject SI, sbj_subject S
                    WHERE SI.ins_sbj_code = S.sbj_code AND SI.ins_id = '$strInstructorID' 
                    AND ins_year = (SELECT MAX(gbl_year) FROM globals)";

	return $DBAdapter->runQuery($query);
    }
}
?>
