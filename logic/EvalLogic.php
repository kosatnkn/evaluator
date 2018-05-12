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
 * This class handles evaluations
 */

class EvalLogic
{
    public function getEvalExamDetails($intExmCode)
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
                    AND (E.exm_status =  'C' OR E.exm_status =  'A')
                    AND exm_no = $intExmCode
                    LIMIT 1";

	return $DBAdapter->runQuery($query);
    }
	
    public function getEvalList($strExmNo)
    {
            $DBAdapter = new DBAdapter();

    $query = "SELECT T.tke_std_id, 
                CONCAT(U.usr_first_name, ' ', U.usr_last_name) AS std_name,
                T.tke_mcq_marks,
                T.tke_str_marks,
                T.tke_tot_marks,
                T.tke_percentage,
                (SELECT COUNT(tke_qst_psudo_no)
                        FROM exm_tke_answer
                        WHERE tke_std_id = T.tke_std_id
                        AND tke_exm_no = $strExmNo
                        AND tke_eval_status = 'N') AS tke_qst_to_eval
                FROM exm_take T,
                        usr_user U,
                        exm_exam E
                WHERE T.tke_std_id = U.usr_nic
                AND T.tke_exm_no = E.exm_no
                AND (E.exm_status = 'C' OR E.exm_status = 'A')
                AND T.tke_exm_no = $strExmNo";

            return $DBAdapter->runQuery($query);
    }

	public function isEvalComplete($intExmNo)
	{
		$DBAdapter = new DBAdapter();

        $query = "SELECT COUNT(tke_std_id) AS tke_not_evaluated
					FROM exm_take
					WHERE tke_exm_no = $intExmNo
					AND (tke_mcq_marks IS NULL OR tke_str_marks IS NULL)";

        $result = $DBAdapter->runQuery($query);
        
        if(mysql_result($result, 0, "tke_not_evaluated") == 0) // everything is evaluated and summerized
        {
			return true;
		}
		else
		{
			return false;
		}
	}

	public function isPublishReady($intExmNo)
	{
		$DBAdapter = new DBAdapter();

        $query = "SELECT COUNT(tke_std_id) AS tke_not_prepared
					FROM exm_take
					WHERE tke_exm_no = $intExmNo
					AND tke_exm_status <> 'E'
					AND tke_tot_marks IS NULL";

        $result = $DBAdapter->runQuery($query);
        
        if(mysql_result($result, 0, "tke_not_prepared") == 0) // everything is evaluated, summerized and ready to publish
        {
			return true;
		}
		else
		{
			return false;
		}
	}

    public function evaluateMCQ($intExmNO)
    {
        $DBAdapter = new DBAdapter();

        $query = "SELECT TA.tke_std_id,
                            TA.tke_exm_no,
                            TA.tke_qst_no,
                            TA.tke_qst_psudo_no,
                            TA.tke_qst_answer AS std_answer,
                            (SELECT mcq_ans_no 
                                    FROM exm_mcq_answer 
                                    WHERE mcq_exm_no = TA.tke_exm_no 
                                            AND mcq_qst_no = TA.tke_qst_no 
                                            AND mcq_is_ans = 1) AS mcq_answer,
                            Q.qst_marks
                    FROM exm_tke_answer TA,
                            exm_question Q
                    WHERE TA.tke_exm_no = Q.qst_exm_no
                    AND TA.tke_qst_no = Q.qst_no
                    AND Q.qst_type = 'M'
                    AND TA.tke_exm_no = $intExmNO";

        $resAns = $DBAdapter->runQuery($query);

        if(mysql_num_rows($resAns) > 0)
        {
            // use transaction management
            $resConn = $DBAdapter->connect();

            $DBAdapter->begin($resConn);

            
            // update quesions

            $updateQuery = "";
            $blnCheck = true;

            while($row = mysql_fetch_array($resAns))
            {
                if($row["std_answer"] == $row["mcq_answer"]) // answer is correct
                {
                    $updateQuery = "UPDATE exm_tke_answer
                                            SET tke_marks = " . $row["qst_marks"] . ",
                                                tke_eval_status = 'E' 
                                    WHERE tke_std_id = '" . $row["tke_std_id"] . "'
                                            AND tke_exm_no = " . $row["tke_exm_no"] . "
                                            AND tke_qst_psudo_no = " . $row["tke_qst_psudo_no"];

                    if($DBAdapter->runQueryToConn($updateQuery, $resConn))
                    {
                        $blnCheck = true;
                        continue;
                    }
                    else
                    {
                        $blnCheck = false;
                        break;
                    }
                }
                else // answer is incorrect
                {
                    $updateQuery = "UPDATE exm_tke_answer
                                            SET tke_marks = 0,
                                                    tke_eval_status = 'E' 
                                    WHERE tke_std_id = '" . $row["tke_std_id"] . "'
                                            AND tke_exm_no = " . $row["tke_exm_no"] . "
                                            AND tke_qst_psudo_no = " . $row["tke_qst_psudo_no"];

                    if($DBAdapter->runQueryToConn($updateQuery, $resConn))
                    {
                        $blnCheck = true;
                        continue;
                    }
                    else
                    {
                        $blnCheck = false;
                        break;
                    }
                }
            }
            
            if($blnCheck)
            {
                // commit previous transaction
                $DBAdapter->commit($resConn);
                $DBAdapter->close($resConn);

                
                // summerize marks to exm_take
                $query = "SELECT TA.tke_std_id,
                                TA.tke_exm_no,
                                SUM(tke_marks) AS tot_mcq
                            FROM exm_tke_answer TA,
                                    exm_question Q
                            WHERE TA.tke_exm_no = Q.qst_exm_no
                            AND TA.tke_qst_no = Q.qst_no
                            AND Q.qst_type = 'M'
                            AND TA.tke_exm_no = $intExmNO
                            GROUP BY TA.tke_std_id";

                $resSummery = $DBAdapter->runQuery($query);

                // use transaction management
                $resConn = $DBAdapter->connect();

                $DBAdapter->begin($resConn);


                // update tot_mcq

                $updateQuery = "";
                $blnCheck = true;

                while($row = mysql_fetch_array($resSummery))
                {
                    $updateQuery = "UPDATE exm_take
                                        SET tke_mcq_marks = " . $row["tot_mcq"] . 
                                    " WHERE tke_std_id = '" . $row["tke_std_id"] . "' 
                                        AND tke_exm_no = " . $row["tke_exm_no"];
                    
                    if($DBAdapter->runQueryToConn($updateQuery, $resConn))
                    {
                        $blnCheck = true;
                        continue;
                    }
                    else
                    {
                        $blnCheck = false;
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
            // there are no MCQs to evaluate
            return true;
        }
    }
    
    public function getStrQuestions($intExmNo, $strStdID)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "SELECT TA.tke_std_id,
                            TA.tke_exm_no,
                            TA.tke_qst_no,
                            TA.tke_qst_psudo_no,
                            Q.qst_question,
                            SA.str_answer,
                            TA.tke_qst_answer AS std_answer,
                            Q.qst_marks,
                            TA.tke_marks
                    FROM exm_tke_answer TA,
                            exm_question Q,
                            exm_str_answer SA
                    WHERE TA.tke_exm_no = Q.qst_exm_no
                    AND TA.tke_qst_no = Q.qst_no
                    AND TA.tke_exm_no = SA.str_exm_no
                    AND TA.tke_qst_no = SA.str_qst_no
                    AND Q.qst_type = 'S'
                    AND TA.tke_exm_no = $intExmNo
                    AND TA.tke_std_id = '$strStdID'";

	return $DBAdapter->runQuery($query);
    }
    
    public function updateStrMarks($strStdID, $intExmNo, $intQstPsudoNo, $intMarks)
    {
        $DBAdapter = new DBAdapter();
    
        $query = "UPDATE exm_tke_answer
                        SET tke_marks = $intMarks,
                            tke_eval_status = 'E' 
                    WHERE tke_std_id = '$strStdID'
                        AND tke_exm_no = $intExmNo
                        AND tke_qst_psudo_no = $intQstPsudoNo";

	return $DBAdapter->runQueryTrans($query);
    }

	public function completeStrEval($intExmNO, $strStdID)
	{
		$DBAdapter = new DBAdapter();
    
		// check wether exam has un-evaluated structured answers
		$query = "SELECT 
					COUNT(TA.tke_exm_no) AS tke_not_eval       
                    FROM exm_tke_answer TA,
                            exm_question Q
                    WHERE TA.tke_exm_no = Q.qst_exm_no
                    AND TA.tke_qst_no = Q.qst_no
                    AND Q.qst_type = 'S'
                    AND TA.tke_eval_status = 'N'
                    AND TA.tke_exm_no = $intExmNO
                    AND TA.tke_std_id = '$strStdID'";
		
		$result = $DBAdapter->runQuery($query);
		
		if(mysql_result($result, 0, "tke_not_eval") == 0) // all answers are evaluated
		{
			// summerize
			
			$query = "UPDATE exm_take
				SET tke_str_marks = (SELECT SUM(tke_marks) AS tot_str
											FROM exm_tke_answer TA,
													exm_question Q
											WHERE TA.tke_exm_no = Q.qst_exm_no
											AND TA.tke_qst_no = Q.qst_no
											AND Q.qst_type = 'S'
											AND TA.tke_exm_no = $intExmNO
											AND TA.tke_std_id = '$strStdID')
				WHERE tke_std_id = '$strStdID'
				AND tke_exm_no = $intExmNO";

			return $DBAdapter->runQueryTrans($query);
		}
		else // there are un-evaluated answers
		{
			return "There are un-evaluated answers. Please check!";
		}
	}
	
	public function prepareResults($intExmNo)
	{
		$DBAdapter = new DBAdapter();
		
		// take percentage
		$query = "SELECT tke_std_id, ROUND(((TA.tke_mcq_marks + TA.tke_str_marks) / E.exm_tot_marks) * 100) AS tke_percent
					FROM exm_take TA, exm_exam E
					WHERE TA.tke_exm_no = E.exm_no
					AND TA.tke_exm_no = $intExmNo
					GROUP BY tke_std_id";
		
		$resPercent = $DBAdapter->runQuery($query);
		
		
		// use transaction management
        $resConn = $DBAdapter->connect();
        
        $DBAdapter->begin($resConn);
		
		
		// update total
		$query = "UPDATE exm_take
					SET tke_tot_marks = (tke_mcq_marks + tke_str_marks)
					WHERE tke_exm_no = $intExmNo";
		
		if($DBAdapter->runQueryToConn($query, $resConn))
		{
			$blnCheck = true;
			
			// update percentage
			while($row = mysql_fetch_array($resPercent))
			{
				$query = "UPDATE exm_take
							SET tke_percentage = " . $row["tke_percent"] . "
							WHERE tke_exm_no = $intExmNo
							AND tke_std_id = '" . $row["tke_std_id"] . "'";
					
				if($DBAdapter->runQueryToConn($query, $resConn))
				{
					$blnCheck = true;
					continue;
				}
				else
				{
					$blnCheck = false;
					break;
				}
			}
			
			if($blnCheck)
			{
				// update status
				$query = "UPDATE exm_take
						SET tke_exm_status = 'E'
						WHERE tke_exm_no = $intExmNo";
				
				if($DBAdapter->runQueryToConn($query, $resConn))
				{
					// commit transaction
                    $DBAdapter->commit($resConn);
                    $DBAdapter->close($resConn);
                    
                    return true;
				}
				else
				{
					// rollback
					$DBAdapter->rollback($resConn, true);
                        
                    return false;
				}
			}
			else
			{
				// rollback
				$DBAdapter->rollback($resConn, true);
                        
                return false;
			}
			
		}
		else
		{
			// rollback
			$DBAdapter->rollback($resConn, true);
                        
            return false;
		}
		
	}
	
	public function releaseResults($intExmNo)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "UPDATE exm_exam
					SET exm_status = 'R',
						exm_edit_date = NOW()
					WHERE exm_no = $intExmNo";
		
		return $DBAdapter->runQueryTrans($query);
	}

	public function getEvaluatedExams($strStdID)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "SELECT E.exm_no, 
						   CONCAT(S.sbj_code, ' - ', S.sbj_name) AS exm_name,
						   C.crs_name,
						   E.exm_type,
						   T.tke_percentage
					FROM exm_take T,
						exm_exam E,
						sbj_subject S,
						sbj_course C
					WHERE E.exm_sbj_code = S.sbj_code
					AND E.exm_crs_code = C.crs_code
					AND T.tke_exm_no = E.exm_no
					AND E.exm_status = 'R'
					AND T.tke_std_id = '$strStdID'
					ORDER BY E.exm_edit_date ASC";
		
		return $DBAdapter->runQuery($query);
	}

	public function isMCQOnly($intExmNo)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "SELECT COUNT(qst_no) as qst_str_count
					FROM exm_question
					WHERE qst_type = 'S'
					AND qst_exm_no = $intExmNo";
		
		$result = $DBAdapter->runQuery($query);
		
		if(mysql_result($result, 0, "qst_str_count") == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function updateMCQOnlyPaper($intExmNo)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "UPDATE exm_take
					SET tke_str_marks = 0
					WHERE tke_exm_no = $intExmNo";
		
		return $DBAdapter->runQueryTrans($query);
	}
	
	public function isStrOnly($intExmNo)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "SELECT COUNT(qst_no) as qst_mcq_count
					FROM exm_question
					WHERE qst_type = 'M'
					AND qst_exm_no = $intExmNo";
		
		$result = $DBAdapter->runQuery($query);
		
		if(mysql_result($result, 0, "qst_mcq_count") == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function updateStrOnlyPaper($intExmNo)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "UPDATE exm_take
					SET tke_mcq_marks = 0
					WHERE tke_exm_no = $intExmNo";
		
		return $DBAdapter->runQueryTrans($query);
	}
	
	public function getExamGradeSummery($strInsID)
	{
		$DBAdapter = new DBAdapter();
		
		$query = "SELECT E.exm_no, 
					CONCAT(S.sbj_code, ' - ', S.sbj_name) AS exm_name,
					C.crs_name,
					E.exm_type,
					
					(SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 0 AND 40) AS tke_c_minus,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 41 AND 45) AS tke_c,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 46 AND 50) AS tke_c_plus,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 51 AND 55) AS tke_b_minus,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 56 AND 60) AS tke_b,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 61 AND 65) AS tke_b_plus,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 66 AND 70) AS tke_a_minus,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 71 AND 75) AS tke_a,
					 
					 (SELECT COUNT(tke_std_id) 
					 FROM exm_take 
					 WHERE tke_exm_no = E.exm_no 
					 AND tke_percentage BETWEEN 76 AND 100) AS tke_a_plus
					
				FROM exm_exam E,
						sbj_subject S,
						sbj_course C
				WHERE E.exm_sbj_code = S.sbj_code
				AND E.exm_crs_code = C.crs_code
				AND E.exm_status = 'R'
				AND E.exm_lec_code = '$strInsID'
				ORDER BY E.exm_edit_date ASC";
				
		return $DBAdapter->runQuery($query);
	}

}
?>
