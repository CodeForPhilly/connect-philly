<?php

/**
 * Description of CenterRequest
 *
 * @author JimS
 */
class Connect_SMS_Request {
    
    protected $_address;
    protected $_searchTerms;
    protected $_lat;
    protected $_lng;
    protected $_nextCenterNum = null;
    protected $_testIsOpen;
    protected $_testTime;
    
    protected $_matchedCenter;
    
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Connect_Exception('Invalid center request property ' . $name . ' and method ' . $method );
        }
        $this->$method($value);
    }
    
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Connect_Exception('Invalid center request property ' . $name . ' and method ' . $method );
        }
        return $this->$method();
    }
    
    public function getAddress() {
        return $this->_address;
    }

    public function setAddress($address) {
        $this->_address = $address;
    }
    
    public function getSearchTerms() {
        return $this->_searchTerms;
    }
    
    public function setSearchTerms($searchTerms) {
        $this->_searchTerms = $searchTerms;
    }
    
    public function getLat() {
        return $this->_lat;
    }
    
    public function setLat($lat) {
        $this->_lat = $lat;
    }
    
    public function getLng() {
        return $this->_lng;
    }
    
    public function setLng($lng) {
        $this->_lng = $lng;
    }
    
    public function getNextCenterNum() {
        return $this->_nextCenterNum;
    }
    
    public function setNextCenterNum($nextCenterNum) {
        $this->_nextCenterNum = $nextCenterNum;
    }
    
    public function getTestIsOpen() {
        return $this->_testIsOpen;
    }
    
    public function setTestIsOpen($isOpen) {
        $this->_testIsOpen = $isOpen;
    }
    
    public function getTestTime(){
        return $this->_testTime;
    }

    public function setTestTime($testTime){
        $this->_testTime = $testTime;
    }
}

?>
