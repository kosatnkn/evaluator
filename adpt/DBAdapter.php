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
 * This class is the main access point to the database
 */
class DBAdapter
{

    //___Connect______________________________________________________________//

    /**
     * Connect to the database using default connection information
     * @return Resource A connection to the database
     */
    public function connect()
    {
        // Connection info
        $strServer = "localhost";
        $strUser = "root";
        $strPassword = "";
        $strDBName = "evaluator";

        //Establish connection
        $resConnection = mysql_connect($strServer, $strUser, $strPassword) or die(mysql_error());
        mysql_select_db($strDBName,$resConnection) or die(mysql_error());

        return $resConnection;
    }

    /**
     * Close connection
     * @param $resConnection Resource The connection to be closed
     */
    public function close($resConnection)
    {
        if($resConnection)
        {
            mysql_close($resConnection);
        }
    }


    //___Transaction Control__________________________________________________//
    
    /**
     * Starts a new transaction in the database
     * @param $resConnection Resource A connection to the database
     */
    public function begin($resConnection)
    {
        mysql_query("BEGIN", $resConnection);
    }

    /**
     * Commits a transaction to the database
     * @param $resConnection Resource A connection to the database
     */
    public function commit($resConnection)
    {
        mysql_query("COMMIT", $resConnection);
    }

    /**
     * Rollback a tranaction from the database and close the connection
     * @param $resConnection Resource A connection to the database
     * @param $blnTerminate Boolean If set to true the transaction will rollback
     * and close the connection. Otherwise the transaction will rollback.
     */
    public function rollback($resConnection, $blnTerminate = false)
    {
        mysql_query("ROLLBACK", $resConnection);
        
        if($blnTerminate)
        {
            $this -> close($resConnection);
        }
    }


    //___Running Queries______________________________________________________//

    

     /**
     * Executes a query on the database using the default connection.
     * This function is used for running queries without database transactions.
     * This is ideal for "SELECT" queries.
     * @param $strQuery String The query
     * @return Resultset|Boolean If the query is a "SELECT" query
     * a resultset will be returned, If it's an "INSERT" or an "UPDATE" query
     * "true" will be returned when the execution is successful. Otherwise mysql
     * error will be returned
     */
    public function runQuery($strQuery)
    {
        //connect
        $resConnection = $this -> connect();

        //run query
        $result = mysql_query($strQuery, $resConnection) or die(mysql_error());

        //close connection
        $this -> close($resConnection);

        return $result;
    }
    
    /**
     * Run a query on a given db connection and return the result without
     * closing the connection.
     * For running multiple queries in the same connection using transaction management 
     * @param String $strQuery The query
     * @param Resource $resConnection A MySQL connection
     * @return Boolean|MySQL_error 'true' if successful otherwise MySQL error
     */
    public function runQueryToConn($strQuery, $resConnection)
    {
        return mysql_query($strQuery, $resConnection) or die(mysql_error());
    }

    /**
     * Executes a query on the database using transaction control and default
     * connection information.
     * This is recommended for "INSERT" and "UPDATE" queries.
     * @param $strQuery String The query
     * @return Resultset|Boolean If the query is a "SELECT" query
     * a resultset will be returned, If it's an "INSERT" or an "UPDATE" query
     * "true" will be returned when the execution is successful. If it failed
     * mysql_error will be returned.
     */
    public function runQueryTrans($strQuery)
    {
        //connect
        $resConnection = $this -> connect();
        
        //start the transaction
        $this->begin($resConnection);

        //run query
        $result = mysql_query($strQuery, $resConnection);

        //check for success
        if($result)
        {
            //commit
            $this->commit($resConnection);
        }
        else
        {
            //rollback
            $this->rollback($resConnection);
        }

        $this -> close($resConnection);

        return $result;
    }
    
}
?>
