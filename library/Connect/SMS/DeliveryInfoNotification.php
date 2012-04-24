<?php

/**
 * Description of DeliveryInfo
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_DeliveryInfoNotification {
    
    protected $json;
    
    protected $deliveryStatus;
    protected $code;
    protected $messageId;
    protected $senderAddress;
    protected $address;
    protected $createdDateTime;
    protected $sentDateTime;
    protected $parts;
    protected $direction;
    protected $message;
    
    public function __construct($json = null ) {

        if( !empty( $json ) ) {
            $this->json = $json;
            $notification = json_decode($json);
            $this->deliveryStatus = $notification->deliveryInfoNotification->deliveryInfo->deliveryStatus;
            $this->code = $notification->deliveryInfoNotification->deliveryInfo->code;
            $this->messageId = $notification->deliveryInfoNotification->deliveryInfo->messageId;
            $this->senderAddress = $notification->deliveryInfoNotification->deliveryInfo->senderAddress;
            $this->address = $notification->deliveryInfoNotification->deliveryInfo->address;
            $this->createdDateTime = $notification->deliveryInfoNotification->deliveryInfo->createdDateTime;
            $this->sentDateTime = $notification->deliveryInfoNotification->deliveryInfo->sentDateTime;
            $this->parts = $notification->deliveryInfoNotification->deliveryInfo->parts;
            $this->direction = $notification->deliveryInfoNotification->deliveryInfo->direction;
            $this->message = $notification->deliveryInfoNotification->deliveryInfo->message;
        }
    }
    
    public function getDeliveryStatus() {
        return $this->deliveryStatus;
    }
    
    public function getCode() {
        return $this->code;
    }
    
    public function getMessageId() {
        return $this->messageId;
    }
    
    public function getSenderAddress() {
        return $this->senderAddress;
    }
    
    public function getAddress() {
        return $this->address;
    }
    
    public function getCreatedDateTime() {
        return $this->createdDateTime;
    }
    
    public function getSentDateTime() {
        return $this->sentDateTime;
    }
    
    public function getParts() {
        return $this->parts;
    }
    
    public function getDirection() {
        return $this->direction;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function setMessage( $message ) {
        $this->message = $message;
    }
}

?>
