<?php

/**
 * Description of SMSifiedResult
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class SMSifiedResult {
    
    public function __construct( $json ) {
        
        if( !empty( $json ) ) {
            $result = json_decode($json);
            $this->timeStamp = $result->inboundSMSMessageNotification->inboundSMSMessage->dateTime;
            $this->destinationAddress = $result->inboundSMSMessageNotification->inboundSMSMessage->destinationAddress;
            $this->message = $result->inboundSMSMessageNotification->inboundSMSMessage->message;
            $this->messageId = $result->inboundSMSMessageNotification->inboundSMSMessage->messageId;
            $this->senderAddress = $result->inboundSMSMessageNotification->inboundSMSMessage->senderAddress;
        }
    }
}

?>
