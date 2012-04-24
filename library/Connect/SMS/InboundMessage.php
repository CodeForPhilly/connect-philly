<?php

/*
 * Convenience class that parses inbound SMSified JSON into a simple object.
 */
class Connect_SMS_InboundMessage {

    // Class properties.
    protected $timeStamp;
    protected $destinationAddress;
    protected $message;
    protected $messageId;
    protected $senderAddress;
    protected $json;

    // Class constructor.
    public function __construct( $json = null ) {

        if( !empty( $json ) ) {
            $this->json = $json;
            $notification = json_decode($json);
            $this->timeStamp = $notification->inboundSMSMessageNotification->inboundSMSMessage->dateTime;
            $this->destinationAddress = $notification->inboundSMSMessageNotification->inboundSMSMessage->destinationAddress;
            $this->message = $notification->inboundSMSMessageNotification->inboundSMSMessage->message;
            $this->messageId = $notification->inboundSMSMessageNotification->inboundSMSMessage->messageId;
            $this->senderAddress = $notification->inboundSMSMessageNotification->inboundSMSMessage->senderAddress;
        }
    }
	
	public function getTimeStamp() {
		return $this->timeStamp;
	}
	
        public function setTimeStamp( $timestamp ) {
            $this->timeStamp = $timestamp;
        }
        
	public function getDestinationAddress() {
		return $this->destinationAddress;
	}
	
        public function setDestinationAddress($address) {
            $this->destinationAddress = $address;
        }
        
	public function getMessage() {
		return $this->message;
	}
	
        public function setMessage( $msg ) {
            $this->message = $msg;
        }
        
	public function getMessageId() {
		return $this->messageId;
	}
        
        public function setMessageId( $id ) {
            $this->messageId = $id;
        }
	
	public function getSenderAddress() {
		return $this->senderAddress;
	}
        
        public function setSenderAddress( $address ) {
            $this->senderAddress = $address;
        }
        
        public function getJSON() {
            
            if( empty($this->json) ) {
                $array = array( 'inboundSMSMessageNotification' =>
                            array( 'inboundSMSMessage' =>
                                array( 'destinationAddress' => $this->destinationAddress,
                                    'senderAddress' => $this->senderAddress,
                                    'message'       => $this->message,
                                    'messageId'     => $this->messageId,
                                    'dateTime'      => $this->timeStamp,
                                    )
                                )
                            );

                $this->json = json_encode( $array );

                       
            }

            return $this->json;
        }

}

?>