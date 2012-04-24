<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JS_Philly_Geocoder
 *
 * @author JimS
 */
class Connect_PhillyGeocoder extends Connect_GISGeocoder {
    
    public static $PHL_LNG = '-75.163789';
    public static $PHL_LAT = '39.952335';
    
    public static function geocode($address) {
        
        if( empty( $address ) ) {
            Connect_FileLogger::warn( __CLASS__ . '::' . __FUNCTION__ . ' -> '
                    . 'address is empty' );
            return null;
        }
        
        $address = $address . ', Philadelphia, PA';
                
        $position = parent::geocode( $address );
        
        // reset the result if it returns the general philly location
        if( $position['lat'] == self::$PHL_LAT 
                && $position['lng'] == self::$PHL_LNG ) {
            
            Connect_FileLogger::debug( __CLASS__ . '::' . __FUNCTION__ . ' -> '
                    . 'returned general Philadelphia coordinates' );
                    
            $position = null;
        }
        
        return $position;
        
    }
}

?>
