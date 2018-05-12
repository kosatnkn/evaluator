<?php

class CommonLogic
{
    
    /**
     * Calculate time in minutes
     * @param Int $intHours Hours
     * @param Int $intMinutes Minutes
     * @return Int Time in minutes
     */
    public function timeInMinutes($intHours, $intMinutes)
    {
        // calculate duration
    
        $intDuration = 0;

        if($intHours > 0)
        {
            $intDuration += ($intHours * 60);
        }

        if($intMinutes > 0)
        {
            $intDuration += $intMinutes;
        }
        
        return $intDuration;
    }
    
    
    /**
     * Create a Hours and Minutes string out of a minutes value
     * @param Int $intDuration
     * @return String Hours and Minutes string
     */
    public function createTimeString($intDuration)
    {
        $strDuration = "";
        
        if($intDuration > 0)
        {
            if($intDuration < 60)
            {
                $strDuration = $intDuration%60 . " mins";
            }
            else
            {
                if($intDuration%60 == 0)
                {
                    $strDuration = $intDuration/60 . " hrs ";
                }
                else
                {
                    $strDuration = floor($intDuration/60) . " hrs " 
                            . $intDuration%60 . " mins";
                }
            }
        }
        else
        {
            $strDuration = "0 mins";
        }
        
        return $strDuration;
    }
    
    /**
     * 
     * @param string $strDate1 A string representation of date
     * @param string $strDate2 A string representation of date
     * @param boolean $blnConsiderSign 'true' will return $strDate1 - $strDate2 with sign (+ or -) otherwise just the value
     * @return int Date difference in days
     */
    public function getDateDiff($strDate1, $strDate2, $blnConsiderSign = false)
    {
        if($blnConsiderSign)
        {
            $diff = strtotime($strDate1) - strtotime($strDate2);
        }
        else
        {
            $diff = abs(strtotime($strDate1) - strtotime($strDate2));
        }

        return floor($diff / 86400); // 60*60*24
    }
    
    
    public function drawGrade($intPercentage)
    {
		$strClass = "";
		$strGrade = "";
		
		if($intPercentage <= 40)
		{
			$strGrade = "C-";
			$strClass = "btn-danger";
		}
		elseif($intPercentage <= 45)
		{
			$strGrade = "C";
			$strClass = "btn-warning";
		}
		elseif($intPercentage <= 50)
		{
			$strGrade = "C+";
			$strClass = "btn-warning";
		}
		elseif($intPercentage <= 55)
		{
			$strGrade = "B-";
			$strClass = "btn-warning";
		}
		elseif($intPercentage <= 60)
		{
			$strGrade = "B";
			$strClass = "btn-success";
		}
		elseif($intPercentage <= 65)
		{
			$strGrade = "B+";
			$strClass = "btn-success";
		}
		elseif($intPercentage <= 70)
		{
			$strGrade = "A-";
			$strClass = "btn-success";
		}
		elseif($intPercentage <= 75)
		{
			$strGrade = "A";
			$strClass = "btn-info";
		}
		else
		{
			$strGrade = "A+";
			$strClass = "btn-info";
		}
		
		
		
		return "<span class=\"btn btn-block btn-xs $strClass\">
					<strong>$strGrade</strong>&nbsp;&nbsp;&nbsp;$intPercentage&nbsp;%
				</span>";
	}
    
}

?>
