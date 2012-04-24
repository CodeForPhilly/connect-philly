<?php

/**
 * The main sms application.  It processes the inbound message.  Primarily it
 * runs the the request and response builders and makes handles failures in
 * sending response SMS.
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_Application {
    
    /**
     * @param type $json
     * @return boolean true on successful sms response, false on sms api send
     * failure
     */
    public static function run($json) {
        
        // needed because of notify url
        if( empty($_SERVER['SERVER_NAME'] ) ) {
            $_SERVER['SERVER_NAME'] = 'test.jimsmiley.us';
        }
        
        $config = Zend_Registry::get('configuration');
        
        $logger = Zend_Registry::get('Log');
        $loggerPrefix = __CLASS__ . '->' . __FUNCTION__ . ": ";
        
        $inboundMessage = new Connect_SMS_InboundMessage($json);
        $logger->info( $loggerPrefix.'*********received center request \''
                . $inboundMessage->getMessage() 
                . '\' **********' );
        
        $response = Connect_SMS_ResponseBuilder::create($inboundMessage);
        
        $attemptNum = '1';
        $notifyUrl = self::getNotifyUrl($_SERVER['SERVER_NAME'], $attemptNum );
        $logger->info( $loggerPrefix. 'using notifyUrl ' 
                . urldecode( $notifyUrl ) );
        /*
         * try and reply to the incoming sms message through the SMSified
         * API
         */
        try {
            
            self::smsSend(  $inboundMessage->getDestinationAddress(), 
                            $inboundMessage->getSenderAddress(), 
                            $response->getMessage(),
                            $notifyUrl
                    );
            
            // notify system addresses of sms interaction
            $options = Connect_Mail_MessageBuilder::smsSuccess($inboundMessage, $response->getMessage() );
            $smsResult = self::sendEmail( $options, $logger, $loggerPrefix );
            $logger->debug( "resendSMS result '$smsResult'" );
            return true;
        }
        
        /*
         * if replying to the message fails, send an email to Connect Philly admin
         */
        catch (SMSifiedException $ex) {
            $config = Zend_Registry::get('configuration');
            
            $responseText = 'attempt to send SMS message to ' 
                    . $inboundMessage->getSenderAddress() . ' failed';
            $logger->warn( $responseText );
            $logger->err(  $ex->getMessage() );

            $mailOptions = array();
            $mailOptions['subject'] = 'SMS Error';
            $mailOptions['message'] = $responseText . "\n" . $ex->getMessage();
            $mailOptions['toAddress'] = 
                        $config->mail->systemMessages->toAddresses->toArray();

            Connect_Mail::send( $mailOptions );
            
            return false;
        }
    }
    
    public static function smsSend($destination,$sender,$message,$notifyUrl = null ) {
        $config = Zend_Registry::get('configuration');
        $sms = new Connect_SMS_SMSified( $config->smsified->user, $config->smsified->pass );
        $result = $sms->sendMessage( $destination,$sender,$message,$notifyUrl );
        return $result;
    }
    
    public static function sendEmail( $options, $logger = null, $loggerPrefix = null ) {
            
        if( !empty( $logger ) ) {
            $logger->info( $loggerPrefix.str_replace( "\n", '; ', $options['message'] ) );
        }
        Connect_Mail::send( $options );

        return true;
    }
    
    /**
     * create the nofity url for callback url
     * @return string the callback url
     */
    public static function getNotifyUrl($serverName,$attemptNum) {
        $url = "http://$serverName/sms/smsified_callback.php?attemptNum=$attemptNum";
        $url = urlencode( $url );
        return $url;
    }
}

?>
