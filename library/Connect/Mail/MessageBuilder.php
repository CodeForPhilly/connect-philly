<?php
/**
 * Description of MessageBuilder
 *
 * @author jsmiley
 */
class Connect_Mail_MessageBuilder {
    
    protected static $msgFooter = 'This is an automatically generated message from the Connect Philly System.  Do not reply to this message';

    public static function resendSmsAttempt($attemptNum, $senderAddress, $originalMessage) {
        $msg = "sender: " . $senderAddress
                . " original message: '" . $originalMessage . "'\n"
                . "\n"
                . "attempt number:$attemptNum\n"
                . "\n"
                . "content length: " . strlen($originalMessage);
        
        $options = array();
        $options['subject'] = 'Resending SMS to ' . $senderAddress;
        $options['message'] = $msg;
        $options['toAddress'] = self::getSystemToAddresses();
        
        return $options;
    }
    
    public static function smsSuccess( Connect_SMS_InboundMessage $inboundMessage, $smsText ) {
        $msg = "sender: " . $inboundMessage->getSenderAddress() 
                . " requested: '" . $inboundMessage->getMessage() . "'\n"
                . "\n"
                . "response:\n"
                . "'$smsText'\n"
                . "\n"
                . "content length: " . strlen($smsText);
        
        $options = array();
        $options['subject'] = 'SMS from ' . $inboundMessage->getSenderAddress();
        $options['message'] = $msg;
        $options['toAddress'] = self::getSystemToAddresses();
        
        return $options;
    }
    
    public static function addCenter(Connect_ComputerCenter $center ) {

        $msg = '';
        foreach( $center->getOptions() as $key => $value ) {
            $msg .= "$key: $value\n";
        }
        $msg .= self::$msgFooter;
        
        $options = array();
        $options['message']     = $msg;
        $options['subject']     = '\''.$center->getLocationTitle() . '\' added to system';
        $options['toAddress']   = self::getAddCenterAddresses();
        
        return $options;
    }
    
    protected static function getSystemToAddresses() {
        $config = Zend_Registry::get('configuration');
        return $config->mail->systemMessages->toAddresses->toArray();
    }
    
    protected static function getAddCenterAddresses() {
        $config = Zend_Registry::get('configuration');
        return $config->mail->addCenter->toAddresses->toArray();
    }
}

?>
