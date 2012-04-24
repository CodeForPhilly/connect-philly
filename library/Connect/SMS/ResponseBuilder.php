<?php
/**
 * Handle all responses for sms functionality.  Given the sms inbound message,
 * knows all heuristics required for the system to respond.  All messages should
 * originate in this class.
 *
 * Possible outcomes:
 * 
 * request: a valid address &search terms    response:nearest center
 * 
 *
 * 
 * 
 * @author JimS
 */
class  Connect_SMS_ResponseBuilder {

    
    public static function create( Connect_SMS_InboundMessage $inboundMessage ) {
        
        if( empty($inboundMessage) ) {
            throw new Connect_Exception('inboundMessage cannot be null' );
        }
        
        if( self::isHelpRequest($inboundMessage) ) {
            return new Connect_SMS_Response_Help($inboundMessage);
        }
        
        try {
            $request = Connect_SMS_RequestBuilder::create($inboundMessage);
            $response = self::getCenterFromRequest($request);
            
            // this is a first address request, store the message for later
            if( !$request->getNextCenterNum() ) {
                Connect_SMS_PastMessageDB::store($inboundMessage);
            }   
        }
        catch( Connect_Exception $e ) {
            return new Connect_SMS_Response_BadCenterRequest($inboundMessage);
        }
        return $response;
    }
    
    public static function getCenterFromRequest( 
                        Connect_SMS_Request $request, $logPrefix = '' ) {
        
        Connect_FileLogger::info( $logPrefix . self::getRequestString( $request ) );

        $foundCenter = self::getNearestCenterFromDB( $request );

        if( !$foundCenter && $request->getNextCenterNum() != null ) {
            $response = new Connect_SMS_Response_NoNextCenter();
        }
        else {
            $response = new Connect_SMS_Response_FoundCenter($request,$foundCenter);
        }
        
        return $response;
    }
    
    
    protected static function getNearestCenterFromDB( $request ) {
        $logPrefix = __CLASS__ . '->' . __FUNCTION__ . ": ";
        
        $nextCenterNum = $request->getNextCenterNum();
        $nextCenterNum = ((int)$request->getNextCenterNum() );
        
        $config = Zend_Registry::get('configuration');

        $ftclient = new Application_Model_DbTable_GoogleFusionTable( 
                $config->gmap->FusionTableId, 
                $config->google->user,
                $config->google->pass
              );

        /*
         * get the nearest computer center
         */
        $center = null;
        
        // if we're testing time
        if( self::shouldTestForOpenCenter($request) ) {
            
            $center = $ftclient->getNearestOpenCenter(
                    $request->getLat(),
                    $request->getLng(), 
                    $request->getSearchTerms(), 
                    $nextCenterNum, 
                    $request->getTestTime() );
        }
        else {
            $center = $ftclient->getNearestCenter(
                    $request->getLat(),
                    $request->getLng(), 
                    $request->getSearchTerms(), 
                    $nextCenterNum );
        }
        
        if( empty($center) && empty($nextCenterNum ) ) {
                throw new Connect_Exception( 'unable to retrieve any centers' );
        }

        return $center;
    }
    
    protected static function getSearchTermStr( $searchTerms ) {
        $termStr = '';
        
        if( is_array( $searchTerms ) ) {
          
            foreach( $searchTerms as $a ) {
                $termStr .= $a . ' ';
            }
            $termStr = trim( $termStr );
        }
        return $termStr;
    }
    
    public static function isHelpRequest(
                                Connect_SMS_InboundMessage $inboundMessage ) {
        return( strtolower( $inboundMessage->getMessage() ) == 'help' );
    }
    
    /**
     *
     * @param type $request 
     */
    public static function shouldTestForOpenCenter(Connect_SMS_Request $request ) {
        return $request->getTestIsOpen();
    }
    
    public static function getCurrentTime() {
        return time();
    }
    
    public static function  getRequestString( Connect_SMS_Request $request ) {
        $retVal = "CenterRequest: address='".$request->getAddress()."'";

        $searchTerms = $request->getSearchTerms();

        if( !empty( $searchTerms ) ) {
            $retVal .= " searchTerms='";
            foreach( $searchTerms as $term ) {
                $retVal .= "$term;";
            }
            $retVal .= "'";
        }

        $testTime = $request->getTestTime();
        if( !empty( $testTime ) ) {
            $retVal .= " testTime='"
                    .date( "l g:i a", $request->getTestTime() )
                    . '\'';
        }

        return $retVal;
    }
    
    public function getDistance( $request, $foundCenter ) {

        $distance = Connect_GISDistanceCalculator::distance(
                        $request->getLat(),$request->getLng(),
                        $foundCenter->getLatitude(), $foundCenter->getLongitude(),
                        "M" // for miles
                        );
        $distance = round( $distance, '2' );

        return $distance;
    }
}

?>
