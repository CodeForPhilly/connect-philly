<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConnectException
 *
 * @author jsmiley
 */
class Connect_Exception extends Exception {
    
    
    public function __construct( $msg ) {
        Connect_FileLogger::err( $msg );
        Connect_FileLogger::err( $this->getTraceAsString() );
    }
}

?>
