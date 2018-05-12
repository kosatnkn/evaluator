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
 * This class handles exams
 */

class ExamLogic
{
    // Exam ____________________________________________________________________
    
    public function getAlredyHeldExamTypes($strSbjCode, $strCrsCode, $strInstructorID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT DISTINCT exm_type
                    FROM exm_exam
                    WHERE exm_sbj_code = '$strSbjCode'
                        AND exm_crs_code = '$strCrsCode'
                        AND exm_lec_code = '$strInstructorID'
                        AND exm_year = (SELECT MAX(gbl_year) FROM globals)";

	return $DBAdapter->runQuery($query);
    }
    
    public function getCreatedExams($strInstructorID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT 
                        E.exm_no,
                        CONCAT(S.sbj_code, ' - ', S.sbj_name) AS exm_name,
                        C.crs_name,
                        E.exm_type,
                        E.exm_duration,
                        COUNT(Q.qst_exm_no) AS exm_questions
                    FROM
                        exm_exam E
                            LEFT JOIN
                        exm_question Q ON E.exm_no = Q.qst_exm_no,
                        sbj_subject S,
                        sbj_course C
                    WHERE
                        E.exm_sbj_code = S.sbj_code
                            AND E.exm_crs_code = C.crs_code
                            AND E.exm_lec_code = '$strInstructorID'
                            AND E.exm_status = 'J'
                            AND E.exm_year = (SELECT 
                                MAX(gbl_year)
                            FROM
                                globals)
                    GROUP BY E.exm_no
                    ORDER BY exm_sbj_code ASC , exm_edit_date ASC";

	return $DBAdapter->runQuery($query);
    }
    
    public function getPublishedExams($strInstructorID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT E.exm_no, 
                        CONCAT(S.sbj_code, ' - ', S.sbj_name) AS exm_name,
                        C.crs_name,
                        E.exm_type, 
                        E.exm_duration, 
                        E.exm_pub_date, 
                        E.exm_due_date,
                        (SELECT COUNT(tke_std_id) FROM exm_take WHERE tke_exm_no = E.exm_no) AS exm_taken
                    FROM exm_exam E, 
                        sbj_subject S,
                        sbj_course C
                    WHERE E.exm_sbj_code = S.sbj_code
                        AND E.exm_crs_code = C.crs_code
                        AND E.exm_lec_code = '$strInstructorID'
                        AND E.exm_status = 'P'
                        AND E.exm_year = (SELECT MAX(gbl_year) FROM globals)";

	return $DBAdapter->runQuery($query);
    }
    
    public function getCompletedExams($strInstructorID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT E.exm_no,
                        CONCAT(S.sbj_code, ' - ', S.sbj_name) AS exm_name,
                        C.crs_name,
                        E.exm_type,
                        E.exm_status,
                        (SELECT COUNT(tke_std_id) FROM exm_take WHERE tke_exm_no = E.exm_no) AS exm_taken
                    FROM exm_exam E,
                        sbj_subject S,
                        sbj_course C
                    WHERE E.exm_sbj_code = S.sbj_code
                        AND E.exm_crs_code = C.crs_code
                        AND E.exm_lec_code = '$strInstructorID'
                        AND (E.exm_status = 'C' OR E.exm_status = 'A')
                        AND E.exm_year = (SELECT MAX(gbl_year) FROM globals)";

	return $DBAdapter->runQuery($query);
    }
    
    public function addExam($arrExam)
    {
        $DBAdapter = new DBAdapter();
        
        // add student
        $query = "INSERT INTO exm_exam (
                    exm_no,
                    exm_sbj_code,
                    exm_crs_code,
                    exm_year,
                    exm_type,
                    exm_lec_code,
                    exm_duration,
                    exm_status,
                    exm_edit_date,
                    exm_pub_date,
                    exm_due_date
                    )
                    VALUES( 
                    NULL,
                    '$arrExam[sbj_code]',
                    '$arrExam[crs_code]',
                    (SELECT MAX(gbl_year) FROM globals),
                    '$arrExam[exm_type]',
                    '$arrExam[usr_nic]',
                    '$arrExam[exm_duration]',
                    'J',
                    NOW(),
                    NULL,
                    NULL)
                    ";
        
        // add the student
        if($DBAdapter->runQueryTrans($query))
        {
            // if successful get inserted id
            $query = "SELECT MAX(exm_no) AS exm_no
                        FROM exm_exam
                        WHERE exm_sbj_code = '$arrExam[sbj_code]'
                            AND exm_lec_code = '$arrExam[usr_nic]'
                            AND exm_status = 'J'
                            AND exm_year = (SELECT MAX(gbl_year) FROM globals)";
            
            $arrResult = $DBAdapter->runQuery($query);
            
            return mysql_result($arrResult, 0, "exm_no");
        }
        else
        {
            return false;
        }
    }
    
    public function getExamDetails($intExmCode)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT CONCAT(S.sbj_code, ' - ', S.sbj_name) AS sbj_name, 
                            C.crs_name, E.exm_year, E.exm_type, E.exm_duration, CS.csb_semester
                    FROM exm_exam E, sbj_subject S, sbj_course C, sbj_crs_sbj CS
                    WHERE E.exm_sbj_code = S.sbj_code
                    AND E.exm_crs_code = C.crs_code
                    AND E.exm_crs_code = CS.csb_crs_code
                    AND E.exm_sbj_code = CS.csb_sbj_code
                    AND E.exm_year = CS.csb_year
                    AND exm_status =  'J'
                    AND exm_no = $intExmCode
                    LIMIT 1";

	return $DBAdapter->runQuery($query);
    }
    
    public function deleteExam($intExamNo)
    {
        $DBAdapter = new DBAdapter();
	
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
	// delete from exm_mcq_answer
        $query = "DELETE FROM exm_mcq_answer WHERE mcq_exm_no = $intExamNo";
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // delete from exm_str_answer
            $query = "DELETE FROM exm_str_answer WHERE str_exm_no = $intExamNo";
        
            if($DBAdapter->runQueryToConn($query, $resConn))
            {
                // delete from exm_question
                $query = "DELETE FROM exm_question WHERE qst_exm_no = $intExamNo";
                
                if($DBAdapter->runQueryToConn($query, $resConn))
                {
                    // delete from exm_exam
                    $query = "DELETE FROM exm_exam WHERE exm_no = $intExamNo";

                    if($DBAdapter->runQueryToConn($query, $resConn))
                    {
                        // commit transaction
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
                else
                {
                    $DBAdapter->rollback($resConn, true);
                    
                    return false;
                }
            }
            else
            {
                $DBAdapter->rollback($resConn, true);
                
                return false;
            }
        }
        else
        {
            $DBAdapter->rollback($resConn, true);
            
            return false;
        }
    }
    
    public function editExmDuration($intExmNo, $intDuration)
    {
        $DBAdapter = new DBAdapter();
        
        $query = "UPDATE exm_exam
                    SET exm_duration = $intDuration
                    WHERE exm_no = $intExmNo";
        
        if($DBAdapter->runQueryTrans($query))
        {
            return $intDuration;
        }
        else
        {
            return false;
        }
    }

    public function getQuestions($intExmNo)
    {
        $DBAdapter = new DBAdapter();
        
        $query = "SELECT qst_no, qst_type, qst_marks, qst_question
                    FROM exm_question
                    WHERE qst_exm_no = $intExmNo
                    ORDER BY qst_no ASC";

	return $DBAdapter->runQuery($query);
    }

    public function getMCQAnswer($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
        
        $query = "SELECT mcq_ans_no, mcq_answer, mcq_is_ans
                    FROM exm_mcq_answer
                    WHERE mcq_exm_no = $intExmNo
                    AND mcq_qst_no = $intQstNo";

	return $DBAdapter->runQuery($query);
    }
    
    public function getStrAnswer($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
        
        $query = "SELECT str_answer
                    FROM exm_str_answer
                    WHERE str_exm_no = $intExmNo
                    AND str_qst_no = $intQstNo";

	return $DBAdapter->runQuery($query);
    }

    public function loadQuestion($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
    
        // get question
        $query = "SELECT qst_no, qst_question, qst_marks, qst_type
                    FROM exm_question
                    WHERE qst_exm_no = $intExmNo
                    AND qst_no = $intQstNo";

		$resQst = $DBAdapter->runQuery($query);
        
        
        $arrQst["qst_no"] = mysql_result($resQst, 0, "qst_no");
        $arrQst["qst_question"] = mysql_result($resQst, 0, "qst_question");
        $arrQst["qst_marks"] = mysql_result($resQst, 0, "qst_marks");
        $arrQst["qst_type"] = mysql_result($resQst, 0, "qst_type");
        
        if($arrQst["qst_type"] == "M")
        {
            // get answers for MCQ
            $query = "SELECT mcq_ans_no, mcq_answer, mcq_is_ans
                        FROM exm_mcq_answer
                        WHERE mcq_exm_no = $intExmNo
                        AND mcq_qst_no = $intQstNo";

            $resMCQAns = $DBAdapter->runQuery($query);

            $arrQst["qst_answers"] = $resMCQAns;
        }
        elseif($arrQst["qst_type"] == "S")
        {
            // get answer for Structured
            $query = "SELECT str_answer
                        FROM exm_str_answer
                        WHERE str_exm_no = $intExmNo
                        AND str_qst_no = $intQstNo";

            $resStdAns = $DBAdapter->runQuery($query);

            $arrQst["qst_answer"] = mysql_result($resStdAns, 0, "str_answer");
        }
        
        return $arrQst;
    }

    public function publishExam($intExmNo, $strDueDate)
    {
        $DBAdapter = new DBAdapter();
    
        // exam status set to P - Published
        $query = "UPDATE exm_exam
                    SET exm_status = 'P',
                        exm_pub_date = NOW(),
                        exm_due_date = '$strDueDate',
                        exm_tot_marks = (SELECT SUM(qst_marks)
                                            FROM exm_question
                                            WHERE qst_exm_no = $intExmNo)
                    WHERE exm_no = $intExmNo";

	return($DBAdapter->runQueryTrans($query));
    }

    public function cancelExam($intExmNo)
    {
        $DBAdapter = new DBAdapter();
    
        // exam status set to A - Aborted
        $query = "UPDATE exm_exam
                    SET exm_status = 'A',
                        exm_edit_date = NOW()
                    WHERE exm_no = $intExmNo";

	return($DBAdapter->runQueryTrans($query));
    }

    public function getTakingExams($strStdID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT E.exm_no,
                        CONCAT(S.sbj_code, ' - ', S.sbj_name) AS exm_name,
                        C.crs_name,
                        E.exm_type, 
                        E.exm_duration,
                        E.exm_due_date,
                        (SELECT COUNT(tke_std_id) 
							FROM exm_take WHERE tke_std_id = '$strStdID' 
							AND tke_exm_no = E.exm_no) AS exm_is_taken
                    FROM student_subject SS,
                        exm_exam E,
                        sbj_subject S,
                        sbj_course C
                    WHERE SS.std_crs_code = E.exm_crs_code
                    AND SS.std_sbj_code = E.exm_sbj_code
                    AND SS.std_sbj_code = S.sbj_code
                    AND SS.std_crs_code = C.crs_code
                    AND E.exm_status = 'P'
                    AND SS.std_id = '$strStdID'
                    AND SS.std_year = (SELECT MAX(gbl_year) FROM globals) 
                    ORDER BY E.exm_due_date ASC";

		return $DBAdapter->runQuery($query);
    }


    // MCQ Questions ___________________________________________________________
    
    public function addMCQQuestion($intExamNo, $strQuestion, $arrAnswer, $intCorrect, $intMarks)
    {
        $DBAdapter = new DBAdapter();
	
        // create question number
        $query = "SELECT 
                        (CASE
                            WHEN MAX(qst_no) IS NULL THEN 1
                            ELSE (MAX(qst_no) + 1)
                        END)
                    FROM
                        exm_question
                    WHERE
                        qst_exm_no = $intExamNo";
        
        $intQstNo = mysql_result($DBAdapter->runQuery($query), 0);
        
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        
	// add question
        $query = "INSERT INTO exm_question (
                    qst_exm_no,
                    qst_no,
                    qst_type,
                    qst_marks,
                    qst_question
                    )
                    VALUES( 
                    $intExamNo,
                    $intQstNo,
                    'M',
                    $intMarks,
                    '$strQuestion')";
        
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // add answer
            $blnCheck = true;
            
            for($i = 0; $i < count($arrAnswer); $i++)
            {
                $j = $i + 1;
                
                if($i + 1 == $intCorrect)
                {
                    $blnIsAns = 1;
                }
                else
                {
                    $blnIsAns = 0;
                }
                
                $query = "INSERT INTO exm_mcq_answer (
                        mcq_exm_no,
                        mcq_qst_no,
                        mcq_ans_no,
                        mcq_answer,
                        mcq_is_ans
                        )
                        VALUES( 
                        $intExamNo,
                        $intQstNo,
                        $j,
                        '$arrAnswer[$i]',
                        $blnIsAns)";
                
                
                $blnCheck = $DBAdapter->runQueryToConn($query, $resConn);
                
                if(!$blnCheck)
                {
                    break;
                }

            }
            if($blnCheck)
            {
                // commit transaction
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
        else
        {
            $DBAdapter->rollback($resConn, true);

            return false;
        }
        
    }
    
    public function getLatestMCQ($intExmNo)
    {
        $DBAdapter = new DBAdapter();
    
        // get latest question
        $query = "SELECT qst_no, qst_question, qst_marks
                    FROM exm_question
                    WHERE qst_exm_no = $intExmNo
                    AND qst_no = (SELECT MAX(qst_no) FROM exm_question WHERE qst_exm_no = $intExmNo)
                    AND qst_type = 'M'";

	$resMCQQst = $DBAdapter->runQuery($query);
        
        
        $arrQst["qst_no"] = mysql_result($resMCQQst, 0, "qst_no");
        $arrQst["qst_question"] = mysql_result($resMCQQst, 0, "qst_question");
        $arrQst["qst_marks"] = mysql_result($resMCQQst, 0, "qst_marks");
        
        
        // get answers
        $query = "SELECT mcq_ans_no, mcq_answer, mcq_is_ans
                    FROM exm_mcq_answer
                    WHERE mcq_exm_no = $intExmNo
                    AND mcq_qst_no = $arrQst[qst_no]";

	$resMCQAns = $DBAdapter->runQuery($query);
        
        $arrQst["qst_answers"] = $resMCQAns;
        
        return $arrQst;
    }

    public function getMCQ($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
    
        // get question
        $query = "SELECT qst_no, qst_question, qst_marks
                    FROM exm_question
                    WHERE qst_exm_no = $intExmNo
                    AND qst_no = $intQstNo
                    AND qst_type = 'M'";

	$resMCQQst = $DBAdapter->runQuery($query);
        
        
        $arrQst["qst_no"] = mysql_result($resMCQQst, 0, "qst_no");
        $arrQst["qst_question"] = mysql_result($resMCQQst, 0, "qst_question");
        $arrQst["qst_marks"] = mysql_result($resMCQQst, 0, "qst_marks");
        
        
        // get answers
        $query = "SELECT mcq_ans_no, mcq_answer, mcq_is_ans
                    FROM exm_mcq_answer
                    WHERE mcq_exm_no = $intExmNo
                    AND mcq_qst_no = $arrQst[qst_no]";

	$resMCQAns = $DBAdapter->runQuery($query);
        
        $arrQst["qst_answers"] = $resMCQAns;
        
        return $arrQst;
    }
    
    public function editMCQQuestion($intExmNo, $intQstNo, $strQuestion, $arrAnswer, $intCorrect, $intMarks)
    {
        $DBAdapter = new DBAdapter();
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        
	// update question
        $query = "UPDATE exm_question
                    SET qst_marks = $intMarks,
                        qst_question = '$strQuestion'
                    WHERE qst_exm_no = $intExmNo
                        AND qst_no = $intQstNo";
        
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // delete existing answers
            $query = "DELETE FROM exm_mcq_answer 
                        WHERE mcq_exm_no = $intExmNo AND mcq_qst_no = $intQstNo";
            
            if($DBAdapter->runQueryToConn($query, $resConn))
            {
                // add answer
                $blnCheck = true;

                for($i = 0; $i < count($arrAnswer); $i++)
                {
                    $j = $i + 1;

                    if($i + 1 == $intCorrect)
                    {
                        $blnIsAns = 1;
                    }
                    else
                    {
                        $blnIsAns = 0;
                    }

                    $query = "INSERT INTO exm_mcq_answer (
                            mcq_exm_no,
                            mcq_qst_no,
                            mcq_ans_no,
                            mcq_answer,
                            mcq_is_ans
                            )
                            VALUES( 
                            $intExmNo,
                            $intQstNo,
                            $j,
                            '$arrAnswer[$i]',
                            $blnIsAns)";


                    $blnCheck = $DBAdapter->runQueryToConn($query, $resConn);

                    if(!$blnCheck)
                    {
                        break;
                    }

                }
                if($blnCheck)
                {
                    // commit transaction
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
            else
            {
                $DBAdapter->rollback($resConn, true);

                return false;
            }
        }
        else
        {
            $DBAdapter->rollback($resConn, true);

            return false;
        }
        
    }
    
    public function deleteMCQQuestion($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
	
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
	// delete from exm_mcq_answer
        $query = "DELETE FROM exm_mcq_answer WHERE mcq_exm_no = $intExmNo AND mcq_qst_no = $intQstNo";
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // delete from exm_question
            $query = "DELETE FROM exm_question 
                        WHERE qst_exm_no = $intExmNo 
                            AND qst_no = $intQstNo";
        
            if($DBAdapter->runQueryToConn($query, $resConn))
            {
                // commit transaction
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
        else
        {
            $DBAdapter->rollback($resConn, true);
            
            return false;
        }
    }
    
    
    
    // Structured Questions ____________________________________________________

    public function addStdQuestion($intExamNo, $strQuestion, $strAnswer, $intMarks)
    {
        $DBAdapter = new DBAdapter();
	
        // create question number
        $query = "SELECT 
                        (CASE
                            WHEN MAX(qst_no) IS NULL THEN 1
                            ELSE (MAX(qst_no) + 1)
                        END)
                    FROM
                        exm_question
                    WHERE
                        qst_exm_no = $intExamNo";
        
        $intQstNo = mysql_result($DBAdapter->runQuery($query), 0);
        
        
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        
	// add question
        $query = "INSERT INTO exm_question (
                    qst_exm_no,
                    qst_no,
                    qst_type,
                    qst_marks,
                    qst_question
                    )
                    VALUES( 
                    $intExamNo,
                    $intQstNo,
                    'S',
                    $intMarks,
                    '$strQuestion')";
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            $query = "INSERT INTO exm_str_answer (
                        str_exm_no,
                        str_qst_no,
                        str_answer
                        )
                        VALUES( 
                        $intExamNo,
                        $intQstNo,
                        '$strAnswer')";
            
            if($DBAdapter->runQueryToConn($query, $resConn))
            {
                // commit transaction
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
        else
        {
            $DBAdapter->rollback($resConn, true);

            return false;
        }
        
    }
    
    public function getLatestStd($intExmNo)
    {
        $DBAdapter = new DBAdapter();
    
        
        // get latest question
        $query = "SELECT qst_no, qst_question, qst_marks
                    FROM exm_question
                    WHERE qst_exm_no = $intExmNo
                    AND qst_no = (SELECT MAX(qst_no) FROM exm_question WHERE qst_exm_no = $intExmNo)
                    AND qst_type = 'S'";

	$resMCQQst = $DBAdapter->runQuery($query);
        
        
        $arrQst["qst_no"] = mysql_result($resMCQQst, 0, "qst_no");
        $arrQst["qst_question"] = mysql_result($resMCQQst, 0, "qst_question");
        $arrQst["qst_marks"] = mysql_result($resMCQQst, 0, "qst_marks");
        
        
        // get answer
        $query = "SELECT str_answer
                    FROM exm_str_answer
                    WHERE str_exm_no = $intExmNo
                    AND str_qst_no = $arrQst[qst_no]";

	$resStdAns = $DBAdapter->runQuery($query);
        
        $arrQst["qst_answer"] = mysql_result($resStdAns, 0, "str_answer");
        
        return $arrQst;
    }
    
    public function getStd($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
    
        
        // get latest question
        $query = "SELECT qst_no, qst_question, qst_marks
                    FROM exm_question
                    WHERE qst_exm_no = $intExmNo
                    AND qst_no = $intQstNo
                    AND qst_type = 'S'";

	$resMCQQst = $DBAdapter->runQuery($query);
        
        
        $arrQst["qst_no"] = mysql_result($resMCQQst, 0, "qst_no");
        $arrQst["qst_question"] = mysql_result($resMCQQst, 0, "qst_question");
        $arrQst["qst_marks"] = mysql_result($resMCQQst, 0, "qst_marks");
        
        
        // get answer
        $query = "SELECT str_answer
                    FROM exm_str_answer
                    WHERE str_exm_no = $intExmNo
                    AND str_qst_no = $arrQst[qst_no]";

	$resStdAns = $DBAdapter->runQuery($query);
        
        $arrQst["qst_answer"] = mysql_result($resStdAns, 0, "str_answer");
        
        return $arrQst;
    }
    
    public function editStdQuestion($intExmNo, $intQstNo, $strQuestion, $strAnswer, $intMarks)
    {
        $DBAdapter = new DBAdapter();
	
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
        
	// add question
        $query = "UPDATE exm_question
                    SET qst_marks = $intMarks,
                        qst_question = '$strQuestion'
                    WHERE qst_exm_no = $intExmNo
                        AND qst_no = $intQstNo";
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            $query = "UPDATE exm_str_answer
                        SET str_answer = '$strAnswer'
                            WHERE str_exm_no = $intExmNo
                            AND str_qst_no = $intQstNo";
            
            if($DBAdapter->runQueryToConn($query, $resConn))
            {
                // commit transaction
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
        else
        {
            $DBAdapter->rollback($resConn, true);

            return false;
        }
        
    }
    
    public function deleteStrQuestion($intExmNo, $intQstNo)
    {
        $DBAdapter = new DBAdapter();
	
        // use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
        
	// delete from exm_mcq_answer
        $query = "DELETE FROM exm_str_answer WHERE str_exm_no = $intExmNo AND str_qst_no = $intQstNo";
        
        if($DBAdapter->runQueryToConn($query, $resConn))
        {
            // delete from exm_question
            $query = "DELETE FROM exm_question WHERE qst_exm_no = $intExmNo AND qst_no = $intQstNo";
        
            if($DBAdapter->runQueryToConn($query, $resConn))
            {
                // commit transaction
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
        else
        {
            $DBAdapter->rollback($resConn, true);
            
            return false;
        }
    }




	// Take Exam _______________________________________________________________
	
	
    public function prepareExam($intExmNo, $strStdID)
    {
            $DBAdapter = new DBAdapter();

    // get question
    $query = "INSERT INTO exm_take(
                        tke_std_id,
                        tke_exm_no,
                        tke_start_time,
                        tke_end_time
                        )
                SELECT '$strStdID' AS std_id,
                $intExmNo AS exm_no,
                NOW() as exm_start_time,
                (NOW() + INTERVAL exm_duration MINUTE) AS exm_end_time
                FROM exm_exam
                WHERE exm_no = $intExmNo";

            if($DBAdapter->runQueryTrans($query))
            {
                    // select question number and question type from exm_question
                    $query = "SELECT qst_no
                                            FROM exm_question 
                                            WHERE qst_exm_no = $intExmNo";

                    $result = $DBAdapter->runQuery($query);

                    $intPsudo = 1;

                    while($row = mysql_fetch_array($result))
                    {
                            // add empty answers
                            $query = "INSERT INTO exm_tke_answer(
                                                    tke_std_id,
                                                    tke_exm_no,
                                                    tke_qst_psudo_no,
                                                    tke_qst_no,
                                                    tke_qst_answer
                                                    )
                                                    VALUES(
                                                    '$strStdID',
                                                    $intExmNo,
                                                    $intPsudo,
                                                    $row[qst_no],
                                                    ''
                                                    )";

                            $DBAdapter->runQueryTrans($query);

                            $intPsudo++;
                    }

        return true;
            }
            else
            {
                    return false;
            }
    }

	public function loadQuestionForExam($intExmNo, $intQstPsudoNo, $strStdID)
    {
        $DBAdapter = new DBAdapter();
    
        // get question
        $query = "SELECT TA.tke_qst_psudo_no,
                            Q.qst_no,
                            Q.qst_type,
                            Q.qst_question,
                            Q.qst_marks,
                            TA.tke_qst_answer,
                            (SELECT MAX(tke_qst_psudo_no) 
                             FROM exm_tke_answer 
                             WHERE tke_std_id = '$strStdID' AND tke_exm_no = $intExmNo) AS exm_questions,
                             TIMESTAMPDIFF(MINUTE, NOW(), T.tke_end_time) AS qst_remain
                    FROM exm_take T,
                                    exm_tke_answer TA,
                                    exm_question Q
                    WHERE T.tke_std_id = TA.tke_std_id
                                    AND T.tke_exm_no = TA.tke_exm_no
                                    AND T.tke_exm_no = Q.qst_exm_no
                                    AND TA.tke_qst_no = Q.qst_no
                                    AND T.tke_std_id = '$strStdID'
                                    AND T.tke_exm_no = $intExmNo
                                    AND TA.tke_qst_psudo_no = $intQstPsudoNo";

		$resQst = $DBAdapter->runQuery($query);
		$intQuestionNo = mysql_result($resQst, 0, "qst_no");
        
        
        $arrQst["qst_no"] = mysql_result($resQst, 0, "tke_qst_psudo_no");
        $arrQst["qst_question"] = mysql_result($resQst, 0, "qst_question");
        $arrQst["qst_marks"] = mysql_result($resQst, 0, "qst_marks");
        $arrQst["qst_answer"] = mysql_result($resQst, 0, "tke_qst_answer"); // answer given by student
        $arrQst["qst_type"] = mysql_result($resQst, 0, "qst_type");
        $arrQst["qst_count"] = mysql_result($resQst, 0, "exm_questions");
        $arrQst["qst_remain"] = mysql_result($resQst, 0, "qst_remain");
        
        if($arrQst["qst_type"] == "M") // MCQ
        {
            // get answers of MCQ
            $query = "SELECT mcq_ans_no, mcq_answer
                        FROM exm_mcq_answer
                        WHERE mcq_exm_no = $intExmNo
                        AND mcq_qst_no = $intQuestionNo";

            $resMCQAns = $DBAdapter->runQuery($query);

            $arrQst["qst_answers"] = $resMCQAns;
        }
        
        return $arrQst;
    }

    public function saveExamAnswer($strUsrID, $intExmNo, $intPsudoNo, $strAnswer)
    {
            $DBAdapter = new DBAdapter();

            $query = "UPDATE exm_tke_answer
                                    SET tke_qst_answer = '$strAnswer'
                                    WHERE tke_std_id = '$strUsrID'
                                    AND tke_exm_no = $intExmNo
                                    AND tke_qst_psudo_no = $intPsudoNo";


            if($DBAdapter->runQueryTrans($query))
            {
                    return true;
            }
            else
            {
                    return false;
            }
    }

    
}

?>
