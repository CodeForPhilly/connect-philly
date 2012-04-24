<?php
/**
 * this class handles the storage of past successful messages.
 *
 * @author JimS
 */
class Connect_SMS_PastMessageDB {
    
    protected static $_file = "/../dat/past-successful-geocoded-messages.txt";
    
    /**
     * stores an smsified inbound message in the format |[date]|[sender address]|[message]
     * 
     * @param Connect_SMS_InboundMessage $inboundMessage 
     */
    public static function store( Connect_SMS_InboundMessage $inboundMessage ) {
        $logPrefix = __CLASS__ . "->" . __FUNCTION__ . ": ";
        $maxTries = 10;
        
        $file = APPLICATION_PATH . self::$_file;
        
        $line = self::getTimestamp()
                . "|". $inboundMessage->getSenderAddress()
                . "|".$inboundMessage->getMessage()
                . "|\n";
        
        $fp = fopen($file, "a");
        
        
        $count = 0;
        $success = false;
        do {
            if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
                fwrite($fp, $line);
                flock($fp, LOCK_UN); // release the lock
                $success = true;
            }
            else {
                Connect_FileLogger::warn( $logPrefix."could not get file lock. sleeping");
                time_nanosleep(0, 500000000);
            }
            ++$count;
        } while( !$success && $count < $maxTries );
        
        if( !$success && $count < $maxTries ) {
            fclose($fp);
            throw new Exception($logPrefix."unable to store incoming message");
        }
        fclose($fp);
    }
    
    public static function getLastEntry( $senderAddress ) {
        $file = APPLICATION_PATH . self::$_file;
        
        if( !file_exists( $file ) ) {
            return false;
        }
        
        $lines = file( $file );
        
        // we want to look at the newest messages first
        $lines = array_reverse( $lines );
        
        //Zend_Debug::dump( $lines );
        foreach( $lines as $line ) {
            
            $elements = explode( '|', $line );
            $timestamp = $elements[0];
            $possibleSenderAddress = $elements[1];
            $message = $elements[2];
            
            if( $possibleSenderAddress == $senderAddress ) {
                Connect_FileLogger::debug( 'returning last message ' .$message );
                return $message;
            }
        }
        
        // did not find message, return false
        return false;
    }
    
    public static function getTimestamp() {
        return date( "Ymd g:i:s a", time() );
    }
}

?>
