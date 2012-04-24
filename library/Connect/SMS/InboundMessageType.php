<?php

/**
 * Description of InboundMessageType
 *
 * @author JimS
 */
class Connect_SMS_InboundMessageType {
    
    public static function isHelp( $message ) {
        return (strcmp( strtolower($message), "help" ) == 0);
    }
    
    public static function isNextAddressRequest( $message ) {
        return preg_match( "/^\d+$/", $message );
    }
}

?>
