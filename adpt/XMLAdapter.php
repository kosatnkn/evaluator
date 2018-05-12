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
require_once('FileAdapter.php');
//_____________________________________________________________________________

/*
 * This is the main access and control class for XML files for the server side
 */
class XMLAdapter extends FileAdapter
{
    /**
     * Return the content of the XML file as a string
     * @param $strFileName String Name of the XML file
     * @return String|false If the file is an XML file it will return the content,
     * otherwise it will return "false"
     */
    function getXMLContent($strFileName)
    {
        if($strFileName != "" && substr($strFileName, -3) == "xml")
        {
            $xml = parent::readFile($strFileName);
            return $xml;
        }
        else
        {
            return false;
        }
    }

    /**
     * Return all tags of the XML file as an array
     * @param $strFileName String Name of the XML file
     * @return Array A multidimentional array containing tag names,
     * tag attributes and tag values
     */
    function getXMLTagArray($strFileName)
    {
        $xml = parent::readFile($strFileName);

        //Create an XML parser
        $resXMLParser = xml_parser_create();
        xml_parser_set_option($resXMLParser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($resXMLParser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($resXMLParser, $xml, $arrValues);
        xml_parser_free($resXMLParser);

        return $arrValues;
    }


    /**
     * Return the XML file in a simple array
     * @param $strFileName String Name of the XML file
     * @return Array A simple array containing all the data in the XML file
     */
    function getXMLData($strFileName)
    {
        $arrTemp = $this -> getXMLTagArray($strFileName);
        
        $arrValues = array();

        foreach($arrTemp as $arrSub)
        {
            if($arrSub['type'] == "complete")
            {
                $arrValues[] = $arrSub['value'];
            }
        }

        return $arrValues;
    }
}
?>
