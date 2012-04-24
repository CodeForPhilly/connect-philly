<?php
/**
 * class consists of a representation of a computer center day
 *
 * @author jsmiley
 */
class Connect_ComputerCenter_Day {
    
    protected $_name;
    protected $_openTime;
    protected $_closeTime;
    protected $_hoursDescription;
    
    public function __construct( $name, $openStr, $closeStr ) {
        $this->_name = $name;
        $this->_openTime = $openStr;
        $this->_closeTime = $closeStr;
        
        $this->_hoursDescription = $this->getHoursDescription();
    }
  
    public function getOptions() {
        $variables = array_keys( get_class_vars( get_class($this) ) );

        $options = array();
        foreach( $variables as $variableName ) {
            
            // strip leading underscore
            $variableName = substr( $variableName, 1 );
            
            $method = 'get' . ucfirst( $variableName );
            $options[$variableName] = $this->$method();
        }
        
        return $options;
    }
    
    public function getOpenTime() {
        return $this->_openTime;
    }
    
    public function getCloseTime() {
        return $this->_closeTime;
    }
    
    public function getHoursDescription() {
        
        if( !empty($this->_openTime) || !empty($this->_closeTime ) ) {
            
            $openStr = ( !empty($this->_openTime) ? $this->_openTime : 'unknown' );
            $closeStr = ( !empty($this->_closeTime) ? $this->_closeTime : 'unknown' );
            
            return self::getDescriptionString( $openStr, $closeStr );
        }
        
        return null;
    }
    
    public static function getDescriptionString($openStr, $closeStr ) {
        
        if( empty( $openStr ) && empty( $closeStr ) ) {
            return null;
        }
        
        $openStr = ( !empty($openStr) ? $openStr : 'unknown' );
        $closeStr = ( !empty($closeStr) ? $closeStr : 'unknown' );
        
        return "$openStr to $closeStr";
    }
}

?>
