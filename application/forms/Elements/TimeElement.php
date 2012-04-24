<?php
/**
 * Description of Time
 *
 * @author JimS
 */
class Application_Form_Elements_TimeElement extends Zend_Form_Element_Xhtml 
{
    protected $_hour;
    protected $_minutes;
    protected $_ampm;
    
    public function getHour(){
        return $this->_hour;
    }

    public function setHour($_hour){
        $this->_hour = $_hour;
        return $this;
    }

    public function getMinutes(){
        return $this->_minutes;
    }

    public function setMinutes($_minutes){
        $this->_minutes = $_minutes;
        return $this;
    }

    public function getAmpm(){
        return $this->_ampm;
    }

    public function setAmpm($_ampm){
        $this->_ampm = $_ampm;
        return $this;
    }

    public function setValue($value)
    {
        if( $value['hour'] == '00'
                && $value['minutes'] == '00'
                && strtoupper($value['ampm']) == ('AM') ) {
            return $this;
        }
        else {
            $this->setHour( $value['hour'] )
                    ->setMinutes( $value['minutes'] )
                    ->setAmpm( $value['ampm'] );
        }
        
        return $this;
    }

    public function getValue()
    {
        if( empty($this->_hour)
                || empty($this->_minutes)
                || empty($this->_ampm)
                    ) {
            return null;
        }
        return $this->getHour() . ':' . $this->getMinutes() . " " . $this->getAmpm();
    }

}

?>
