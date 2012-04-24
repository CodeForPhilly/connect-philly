<?php

/**
 * taking an inbound message, builds a proper center request
 *
 * @author JimS
 */
class Connect_SMS_RequestBuilder {
    
    public static function create( Connect_SMS_InboundMessage $inboundMessage,
            $testTime = null ) {

        $centerRequest = new Connect_SMS_Request();
        $message = null;
        
        // trim the message content
        $inboundMessage->setMessage( trim( $inboundMessage->getMessage() ) );
        
        // if the message only contains digits, try to pull back the last
        // actuall address they sent
        if( Connect_SMS_InboundMessageType::isNextAddressRequest( 
                $inboundMessage->getMessage() ) ) 
        {
            $nextCenterNum = $inboundMessage->getMessage();
            $centerRequest->setNextCenterNum( $nextCenterNum );
            
            // find the address in the past message text file
            $message = Connect_SMS_PastMessageDB::getLastEntry( 
                                        $inboundMessage->getSenderAddress() );
            
            // oops, there was no last address
            if( empty( $message ) ) {
                $response = new Connect_SMS_Response_NoNextCenter();
                
                throw new Connect_Exception( $response->getMessage() );
            }
        }
        else 
        {
            $message = $inboundMessage->getMessage();
        }

        $centerRequest->setTestIsOpen( self::testIsOpen($message) );
            
        // if we're not testing now
        if( empty($testTime) ) {
            $centerRequest->setTestTime( time() );
        }
        else {
            $centerRequest->setTestTime( $testTime );
        }
            
        $message = self::scrubMessage($message, 'open');
        $searchTerms = self::getSearchTerms( $message );
        $address = self::scrubOfSearchTerms($message);
        
        $position = self::getPosition( $address );
        
        if( empty($position) ) {
            throw new Connect_Exception( 'System could not understand address \'' 
                                            . $address . '\'');
        }
        
        // if we're here, we made it
        $centerRequest->setAddress( $address );
        $centerRequest->setLat( $position['lat'] );
        $centerRequest->setLng( $position['lng'] );
        $centerRequest->setSearchTerms( $searchTerms );
        
        return $centerRequest;
    }

    /**
     * given, address, attempts to geocode it
     * @param string $address
     * @return mixed an associative array with lat and lng as keys
     */
    protected static function getPosition( $address ) {
        return Connect_PhillyGeocoder::geocode( $address );
    }
    
    
    /**
     * searches the message for valid search terms
     * @param string $message
     * @return array the array of terms that were matched
     */
    protected static function getSearchTerms( $message ) {
        $searchTerms = Application_Model_DbTable_SearchTerms::getSearchTerms();
        $matchedTerms = array();
                
        foreach( $searchTerms as $term ) {
            if( preg_match( "/$term/i", $message ) ) {
                array_push( $matchedTerms, $term );
            }
        }
        
        return $matchedTerms;
    }
    
    /**
     * scrubs the message of all search terms
     * @param string $message the message to be scrubbed
     * @return string scrubbed message
     */
    protected static function scrubOfSearchTerms( $message ) {
        $searchTerms = Application_Model_DbTable_SearchTerms::getSearchTerms();
                
        foreach( $searchTerms as $term ) {
            $message = self::scrubMessage($message, $term);
        }
        return $message;
    }
    
    /**
     *
     * @param string $word the word to scrub from message
     */
    protected static function scrubMessage( $message, $word ) {
        $matches = null;
        
        if( preg_match( "/(.*)$word(.*)/i", $message, $matches ) ) {
            $message = trim( $matches[1] . $matches[2] );
        }
        $message = trim( preg_replace( "/\s+/", " ", $message ) );
        
        return $message;
    }
    
    /**
     * searches message for open term and returns true if found
     * @param string $message
     * @return bool true on open
     */
    protected static function testIsOpen( $message ) {
        if( preg_match( '/open/i', $message ) ) {
            return true;
        }
        return false;
    }
}
?>
