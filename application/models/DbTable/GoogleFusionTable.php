<?php
include('../library/fusion-tables-client-php/clientlogin.php');
include('../library/fusion-tables-client-php/sql.php');
include('../library/fusion-tables-client-php/file.php');

class Application_Model_DbTable_GoogleFusionTable {
    
    protected $_ftclient;
    protected $_tableId;
    protected $_locationColumn = 'Longitude';
    
    public function __construct(  $tableId, $user, $pass ) {
        
        $this->logger = Zend_Registry::get('Log');

        //JS_FileLogger::debug( $this::logPrefixString(__FUNCTION__) 
        //                 . ": tableId='$tableId', user='$user', pass='$pass'" );
        
        if( !isset( $tableId) || $tableId == '' ) {
            throw new ZendException( "tableId must be defined" );
        }
        
        $token = ClientLogin::getAuthToken( $user, $pass );
        $ftclient = new FTClientLogin($token);

        $this->_tableId = $tableId;
        $this->_ftclient = $ftclient;
    }
 
    /**
     * tries to insert data into the google fusion table, returns false on
     * failure.
     * 
     * It adds the current timestamp to the computer center when entered.
     *
     * @param mixed $data - an associative array
     * @return boolean
     */
    public function insert( $data ) {
        
        $timestamp = date('Y-m-d H:i:s');
        
        $data['Timestamp'] = $timestamp;
        
        if( !$data ) {
            Connect_FileLogger::err( $this::logPrefixString(__FUNCTION__) . ': data is null' );
            return false;
        }
        
        $ftclient = $this->_ftclient;
        
        $result = $ftclient->query(
                   SQLBuilder::insert( $this->_tableId, $data ) 
                   );
        
        if( preg_match('/Error 400/', $result ) ) 
        {
            Connect_FileLogger::err( $this::logPrefixString(__FUNCTION__) . ': error 400' );
            
            Connect_FileLogger::err( $this::logPrefixString(__FUNCTION__) . ': sql => ' . 
                    SQLBuilder::insert( $this->_tableId, $data ) );
            
            Connect_FileLogger::err( $this::logPrefixString(__FUNCTION__). Zend_Debug::dump( $data, false ) );
            
            return false;
        }
        
        return true;
    }
    
    public function getColumnInfo() {

        $ftclient = $this->_ftclient;
        
        $result = 
                $ftclient->query(SQLBuilder::describeTable( $this->_tableId ) );
        
        return $this->toAssocArray( $result );
    }
    
    protected function getCenters( $lat, $lng, $searchTerms, $offset = null, $limit = null ) {
        $logPrefix = __CLASS__ . "->" . __FUNCTION__ . ": ";
        
        $conditions = "'Pending Confirmation' NOT EQUAL TO 'true' AND";
        
        $limitStr = '';
        if( !empty($limit) ) {
            $limitStr = " LIMIT $limit";
        }
        
        $offsetStr = '';
        if( !empty($offset) ) {
            $offsetStr = " OFFSET $offset";
        }
        
        $orderBy = sprintf( " ORDER BY ST_DISTANCE('%s', LATLNG( %s, %s ) )%s%s",
                            $this->_locationColumn, $lat, $lng, $offsetStr, $limitStr );
        
        $cols=null; 
        

        if( $searchTerms != null ) {
            foreach( $searchTerms as $term ) {
                $conditions .= ' ' 
                    . Application_Model_DbTable_SearchTerms::getFtSql( $term )
                    . ' AND';
            }
        }
        // get rid of that last trailing 'AND'
        if( $conditions != null ) {
            $conditions = substr( $conditions, 0, strlen( $conditions ) - 3 );
        }
        
        $sql = SQLBuilder::select( $this->_tableId, 
                                $cols, 
                                $conditions, 
                                $orderBy );
        
        /*
         * make the call to the fusion table
         */
        $result = $this->makeAPICall( $sql );
        
        $centers = $this::parseCSV( $result );
        Connect_FileLogger::info( $logPrefix."returning " . count($centers) . " centers" );
        
        return $centers;
    }
    
    /**
     * returns the center nearest the given $lat, $lng coordinates
     * @param type $lat
     * @param type $lng
     * @return type 
     */
    public function getNearestCenter( $lat, $lng, $searchTerms, $offset = null ) {
        $logPrefix = __CLASS__ . "->" . __FUNCTION__ . ": ";
        $limit = null;
        $centers = $this->getCenters( $lat, $lng, $searchTerms, $offset, $limit );
        
        if( count( $centers ) == 0 ) {
            Connect_FileLogger::debug( 'returning no centers' );
            return null;
        }
        // return the last one in the array, because of the limit
        $center = $centers[ 0 ];
        Connect_FileLogger::debug( 'returning ' . $center->getLocationTitle() );
        return $center;
    }
    
    public function getNearestOpenCenter( $lat, $lng, $searchTerms, $nextCenterNum, $timestamp ) {
        $logPrefix = __CLASS__ . "->" . __FUNCTION__ . ": ";
        
        $limit = 50;
        $offset = 0;
        
        $counter = 0; // how many center matches have we found so far
        while( $centers 
                = $this->getCenters( $lat, $lng, $searchTerms, $offset, $limit )
                ) 
        {
            
            foreach( $centers as $center ) {
                
                if( $center->getOpenStatus( $timestamp ) 
                        == Connect_ComputerCenter_OpenStatus::$OPEN ) {
                    
                    Connect_FileLogger::debug( $logPrefix."open center found: " . $center->getLocationTitle() );
                    
                    if( $counter < $nextCenterNum ) {
                        Connect_FileLogger::debug( $logPrefix."but it's too early in the count $counter < $nextCenterNum" );
                        $counter += 1;
                    }
                    // this is the center we're looking for
                    else {
                        Connect_FileLogger::debug( $logPrefix."returning it" );
                        return $center;
                    }
                }
            } // end foreach center
            $offset += $limit;
        } // end while there are more centers
        
        return null;
    }
    
    /**
     * handles the fusion table api calls and checks the result for errors
     * 
     * @param type $sql
     * @return type 
     */
    protected function makeAPICall( $sql ) {
        $logPrefix = __CLASS__ . "->" . __FUNCTION__ . ": ";
        Connect_FileLogger::info( $logPrefix.$sql );
        $result = $this->_ftclient->query( $sql );
        
        //print $result;
        //exit;
        if( preg_match( "/(Parse error near.*)\n/", $result, $match ) ) 
        {
            $msg = $sql . "\n" . $match[1];
            
            throw new FusionTableException( $msg );
        }
        
        return $result;
    }
    
    /**
     * 
     * given the fusion table result, breaks up the csv format into computer center objects
     * @param type $csv the result of a sql query from the fusion table API
     * @return array containing the computer centers
     */
    protected static function parseCSV( $csv ) {
        
        // break it up by lines, each line is a computer center
        $lines = explode( "\n", $csv );
        
        // the first line is the column names
        $colNames = explode( ',', $lines[0] );
        
        $computerCenters = array();
        
        for( $i = 1; $i < count( $lines ); $i++ ) 
        {
            // probably at the end of the result
            if( preg_match( "/^\s*$/", $lines[$i] ) ) {
                break;
            }
            
            //$centerArray = explode( ',', $lines[$i] );
            $centerArray = str_getcsv( $lines[$i] );
            $assoc_array = array_combine( $colNames, $centerArray );
            
            //print_r( $assoc_array );
           
            array_push( $computerCenters, 
                    new Connect_ComputerCenter( $assoc_array ) );
        }
        
        return $computerCenters;
    }
    
    protected function toAssocArray( $result ) {
        
        $lines = explode( "\n", $result );
        $columnNames = explode( ',', $lines[0] );
       
        $retArray = array();
        for( $i = 1; $i < count( $lines ); $i++ ) {
            
            if( empty($lines[$i]) ) {
                break;
            } 
            
            $elements = explode( ',', $lines[$i] );
            
            $obj_array = array();
            for( $j = 0; $j < count( $elements); $j++ ) {
                $obj_array[ $columnNames[$j] ] = $elements[ $j ];
            }
            array_push( $retArray, $obj_array );
        }

        return $retArray;
    }
    
    /**
     * returns a string of the class name and function name for log strings
     * @return string
     */
    protected static function logPrefixString($functionName) {
        return __CLASS__ .'->'. $functionName;
    }
}

class FusionTableException extends Zend_Exception {
    
}
?>
