<?php

/**
 * Handles delivery notifications returned from SMSified.  Currently, failed
 * notifications are just logged.
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_CallbackApplicaton extends Connect_SMS_Application {
    
    public static function processCallback( $attemptNum, $json ) {
        
        $notification = new Connect_SMS_DeliveryInfoNotification($json);
        
        return self::process( $attemptNum, $notification );
    }
    
    public static function process( $attemptNum,
            Connect_SMS_DeliveryInfoNotification $notification ) 
    {
        $logger= Zend_Registry::get('Log');
        $loggerPrefix = __CLASS__ . '->' . __FUNCTION__ . ": ";
        
        if( empty($attemptNum) ) {
            throw new Connect_Exception( 'attemptNum cannot be empty' );
        }
        
        $logger->info( $loggerPrefix.'********* received delivery notification **********' );
        $logger->info( $loggerPrefix.'delivery status='.$notification->getDeliveryStatus() );
        
        if( $notification->getDeliveryStatus() == 'DeliveredToNetwork' ) {}
        
        // if it failed and there was only one attempt to deliver
        else if( $notification->getDeliveryStatus() !=  'DeliveredToNetwork'
                && $attemptNum == '1' ) {
            
            $logger->info( $loggerPrefix.' previous sms failed.' );
            
            $logger->info( $loggerPrefix.print_r( $notification, true ) );
            
            /*
             * @todo: uncomment if you actually want to start resending SMS
             * messages
            $notifyUrl = self::getNotifyUrl($_SERVER['SERVER_NAME'], ++$attemptNum);
            
            $senderAddress = $notification->getSenderAddress();
            $toAddress = $notification->getAddress();
            try {
                $result = self::smsSend(
                            $senderAddress, 
                            $toAddress, 
                            $notification->getMessage(), $notifyUrl 
                        );
                $logger->debug( $loggerPrefix.'smsSend succeeded' );
            }
            catch( SMSifiedException $e ) {
                $logger->error( $e->getMessage() );
            }
             */
            
            
            // notify systems admin via email
            $options = Connect_Mail_MessageBuilder::resendSmsAttempt(
                            $attemptNum-1, 
                            $toAddress, 
                            $notification->getMessage() 
                    );
            self::sendEmail( $options, $logger, $loggerPrefix );
        }
        return true;
    }
}

?>
