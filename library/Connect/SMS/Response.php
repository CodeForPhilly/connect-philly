<?php

/**
 * Description of Response
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_Response {
    
    protected $_centerRequest;
    
    public function _construct( $centerRequest ) {
        $this->_centerRequest = $centerRequest;
    }
    
    public function getMessage() {
        throw new Exception( "this class function is meant to be extended" );
    }
    
    public function getCenterRequest() {
        return $this->_centerRequest;
    }
}

?>
