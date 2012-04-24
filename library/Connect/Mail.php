<?php

/**
 * Connect_Mail extends JS_Mail to allow easier access to sending messages.
 * JS_Connect_Mail knows how to obtain the smtp username, password, and the senders
 * addresses for exceptions and notifications that sms was interacted with
 * 
 * Connect_Mail knows who to send mail to in different situations
 *
 * @author JimS
 */
class Connect_Mail  {
    
    protected static $mailerName = 'Connect Philly Mailer';
    protected static $smtpHost = 'smtp.gmail.com';
    
    
    public static function send( $options ) {

        if( empty( $options['message'] ) ) {
            throw new Connect_Exception( 'message option must be defined' );
        } else if( empty( $options['subject'] ) ) {
            throw new Connect_Exception('subject option must be defined' );
        } else if( empty( $options['toAddress'] ) ) {
            throw new Connect_Exception('toAddress option must be defined' );
        }
        
        $config = Zend_Registry::get( 'configuration' );
        
        $options['senderAddress']   = $config->google->user;
        $options['smtpPass']        = $config->google->pass;
        $options['smtpHost']        = self::$smtpHost;
        $options['senderName']      = self::$mailerName;
        
        self::sendMessage( $options );
    }
    
    //protected static function sendMessage($smtpHost, $senderName, $senderAddress, $smtpPass, $send_to_email, $subject, $msg ) {
    protected static function sendMessage( $options ) {
        
        if( empty( $options['message'] ) ) {
            throw new Connect_Exception( 'message option must be defined' );
        } else if( empty( $options['subject'] ) ) {
            throw new Connect_Exception('subject option must be defined' );
        } else if( empty( $options['toAddress'] ) ) {
            throw new Connect_Exception('toAddress option must be defined' );
        }
        
        $smtpConf = array(
                        'auth' => 'login',
                        'ssl' => 'ssl',
                        'port' => '465',
                        'username' => $options['senderAddress'],
                        'password' => $options['smtpPass']
                    );

        try 
        {
            $transport = new Zend_Mail_Transport_Smtp($options['smtpHost'], $smtpConf);

            $mail = new Zend_Mail();
            $mail->setFrom($options['senderAddress'], $options['senderName'] );
            $mail->addTo( $options['toAddress'] );
            $mail->setSubject($options['subject']);
            $mail->setBodyText($options['message']);
            $mail->send($transport);
        }
        catch( Zend_Mail_Protocol_Exception $e ) {
            $errMsg = "error sending mail with content '".$options['message'].".'; reason: ".$e->getMessage();
            Connect_FileLogger::error( $errMsg );
        }
    }
}
?>
