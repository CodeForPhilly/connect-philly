<?php

/**
 * Represents a response for when a user sends a bad center request
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_Response_BadCenterRequest extends Connect_SMS_Response {
    
    protected $_errMsg;
    protected $_inboundMessage;
    
    public function __construct( Connect_SMS_InboundMessage $inboundMessage ) {
        $this->_inboundMessage = $inboundMessage;
    }
    
    public function getMessage() {
        
        $retVal = '';
        
        if( $this->_errMsg ) {
            $retVal .= $e->getMessage() . "\n";
        }
    
        $retVal .= 'Address \''.$this->_inboundMessage->getMessage()."' was not understood. Please modify"
            . " your request and try again.  Text 'HELP' for"
            . " further instructions.";
        
        return $retVal;
    }
    
    public function setErrorMessage( $errMsg ) {
        $this->_errMsg = $errMsg;
    }
    
}

?>
