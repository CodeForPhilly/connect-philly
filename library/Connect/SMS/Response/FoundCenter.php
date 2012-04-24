<?php

/**
 * Description of CenterResponse
 *
 * @author JimS
 */
class Connect_SMS_Response_FoundCenter extends Connect_SMS_Response {
    
    protected $_distance;
    protected $_foundCenter;
    
    public function __construct( Connect_SMS_Request $request,
     Connect_ComputerCenter $foundCenter ) {
        $this->_centerRequest = $request;
        $this->_foundCenter = $foundCenter;
    }
    
    public function getDistance() {
        return $this->_distance;
    }
    
    public function setDistance($distance) {
        $this->_distance = $distance;
    }
    
    public function getFoundCenter() {
        return $this->_foundCenter;
    }
    
    public function getMessage() {
        
        $center = $this->getFoundCenter();
        $distance = $this->getDistance();

        $nextCenterNum = (int)$this->getCenterRequest()->getNextCenterNum();
        
        $wifi = $center->getHasWifiAccess();
        $phone = $center->getCenterPhoneNumber();
        $disabledAccess = $center->getHasDisabledAccess();
        $testTime = $this->getCenterRequest()->getTestTime();
        $openStatus = $center->getOpenStatus( $testTime );
        
        $wifi           = ( empty($wifi) ? '' : 'Wifi' );
        $phone          = ( empty($phone) ? '' : ' Tel.: '.$phone );
        $disableAccess  = ( empty($disabledAccess) ? '' : ' Disabled Access: yes' );
        
        $isOpenStr = '';
        if( $openStatus == Connect_ComputerCenter_OpenStatus::$OPEN ) {
            $isOpenStr = ' Open Now';
        }
        else if($openStatus == Connect_ComputerCenter_OpenStatus::$CLOSED ) {
            $isOpenStr = ' Closed Now';
        }
        
        $msg =  $center->getLocationTitle() . "\n"
                . $center->getAddress1() . "\n"
                //. "$distance miles away" . "\n"
                . $wifi 
                //. $disableAccess 
                . $phone
                . $isOpenStr;
        
        return $msg."\n".self::successMessageSuffix(($nextCenterNum+1));
    }
    
    public static function successMessageSuffix($nextNum) {
        $msgSuffix = 'Send \'' . $nextNum 
                . '\' for next location. Text \'help\''
                . ' for search options and more info';
        return $msgSuffix;
    }
}
?>
