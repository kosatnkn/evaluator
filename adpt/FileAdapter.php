<?php
/**
 * Each type of variable is prefixed using following conventions
 *      str - String
 *      int - Integer
 *      bln - Boolean
 *      arr - Array
 *      res - Resource
 */

/**
 *  This is the main class for handling files
 */
class FileAdapter
{
    /**
     * Open a file
     * @param $strFileName String The path and the name of the file
     * @param $strAccessMode String Access mode used to handle the file
     * @return Resource A pointer to the file
     */
    private function openFile($strFileName, $strAccessMode)
    {
        $fp = fopen($strFileName, $strAccessMode) or die("Error opening file");
        return $fp;
    }

    /**
     * Close a file
     * @param $resPointer Resource File pointer
     */
    private function closeFile($resPointer)
    {
        if($resPointer)
        {
            fclose($resPointer);
        }
    }

    /**
     * Return the content of a file. You do not have to open the file before
     * calling this method because this method do the file opening and closing itself.
     * @param $strFileName String The path and the name of the file
     * @return String Data in the file
     */
    public function readFile($strFileName)
    {
        $fp = $this -> openFile($strFileName, "r");

        $strData;

        if($fp)
        {
            while(!feof($fp))
            {
                $strData .= fgets($fp, 4096);
            }
        }

        $this -> closeFile($fp);
        
        return $strData;
    }

    /**
     * Writes the content to a file
     * @param $strFileName String The path and the name of the file
     * @param $strContent String The content to be written to the file
     * @param $blnAppend Boolean If setto "true" the file will be appended oterwise it will be overwritten
     */
    public function writeFile($strFileName, $strContent, $blnAppend = false)
    {
        $fp;

        if($blnAppend)
        {
            //Append the content to the file
            $fp = $this -> openFile($strFileName, "a");
        }
        else
        {
            //Overwrite the file
            $fp = $this -> openFile($strFileName, "w");
        }

        fwrite($fp, $strContent);
        $this -> closeFile($fp);
    }
}
?>
