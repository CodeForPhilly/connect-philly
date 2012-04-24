<?php

class Connect_ComputerCenter
{
    protected $_id;
    protected $_locationTitle;
    
    protected $_address1;
    protected $_address2;
    protected $_city;
    protected $_state;
    protected $_zip;
    
    protected $_displayAddress;
    protected $_standardiz;
    protected $_latitude; // alias for lat_x
    protected $_longitude; // alias for long_y
    protected $_type; // alias for type
    protected $_centerPhoneNumber;
    
    /*
    protected $_monday;
    protected $_tuesday;
    protected $_wednesday;
    protected $_thursday;
    protected $_friday;
    protected $_saturday;
    protected $_sunday;
    */
    
    protected $_mondayHoursDescription;
    protected $_mondayHoursOpen;
    protected $_mondayHoursClose;
    
    protected $_tuesdayHoursDescription;
    protected $_tuesdayHoursOpen;
    protected $_tuesdayHoursClose;
    
    protected $_wednesdayHoursDescription;
    protected $_wednesdayHoursOpen;
    protected $_wednesdayHoursClose;
    
    protected $_thursdayHoursDescription;
    protected $_thursdayHoursOpen;
    protected $_thursdayHoursClose;
    
    protected $_fridayHoursDescription;
    protected $_fridayHoursOpen;
    protected $_fridayHoursClose;
    
    protected $_saturdayHoursDescription;
    protected $_saturdayHoursOpen;
    protected $_saturdayHoursClose;
    
    protected $_sundayHoursDescription;
    protected $_sundayHoursOpen;
    protected $_sundayHoursClose;
    
    protected $_hasWifiAccess;
    protected $_wifiDescription;
    
    protected $_hasInternetAccess;
    protected $_numberOfWorkstations;
    protected $_timeLimitInMinutes;

    protected $_numberOfStaff;
    protected $_hasDisabledAccess;
    protected $_centerLanguages;
    protected $_serviceAgeDescription;
    protected $_ancillaryProgrammingDescription;
    protected $_centerWebsite;
    protected $_centerEmailContact;
    protected $_pendingConfirmation;

    protected $_timestamp;
    
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
            throw new Exception('Invalid computer center property ' . $name . ' and method ' . $method );
        }
        $this->$method($value);
    }
    
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid computer center property ' . $name . ' and method ' . $method );
        }
        return $this->$method();
    }
    
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            $method = str_replace( ' ', '', $method );
            
            // see if this is a valid method
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
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
    
    public function getId(){
            return $this->_id;
    }

    public function setId($id){
            $this->_id = $id;
    }

    public function getLocationTitle(){
            return $this->_locationTitle;
    }

    public function setLocationTitle($locationTitle){
            $this->_locationTitle = $locationTitle;
    }
    
    public function getAddress1(){
            return $this->_address1;
    }

    public function setAddress1($address1){
            $this->_address1 = $address1;
    }

    public function getAddress2(){
            return $this->_address2;
    }

    public function setAddress2($address2){
            $this->_address2 = $address2;
    }

    public function getCity(){
            return $this->_city;
    }

    public function setCity($city){
            $this->_city = $city;
    }

    public function getState(){
            return $this->_state;
    }

    public function setState($state){
            $this->_state = $state;
    }

    public function getZip(){
            return $this->_zip;
    }

    public function setZip($zip){
            $this->_zip = $zip;
    }
        
    public function getDisplayAddress(){
            return $this->_displayAddress;
    }

    public function setDisplayAddress($displayAddress){
            $this->_displayAddress = $displayAddress;
    }
    
    public function getStandardiz(){
            return $this->_standardiz;
    }

    public function setStandardiz($standardiz){
            $this->_standardiz = $standardiz;
    }
    
    public function getLatitude(){
            return $this->_latitude;
    }

    public function setLatitude($latitude){
            $this->_latitude = $latitude;
    }

    public function getLongitude(){
            return $this->_longitude;
    }

    public function setLongitude($longitude){
            $this->_longitude = $longitude;
    }
    
    public function getType(){
            return $this->_type;
    }

    public function setType($type){
            $this->_type = $type;
    }

    public function getCenterPhoneNumber(){
        return $this->_centerPhoneNumber;
    }

    public function setCenterPhoneNumber($centerPhoneNumber){
        $this->_centerPhoneNumber = $centerPhoneNumber;
    }

    public function getMondayHoursDescription(){
        
        if( empty( $this->_mondayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_mondayHoursOpen, $this->_mondayHoursClose );
        }
        else {
            return $this->_mondayHoursDescription;
        }
    }

    public function setMondayHoursDescription($mondayHoursDescription){
        $this->_mondayHoursDescription = $mondayHoursDescription;
    }

    public function getMondayHoursOpen(){
        return $this->_mondayHoursOpen;
    }

    public function setMondayHoursOpen($mondayHoursOpen){
        $this->_mondayHoursOpen = $mondayHoursOpen;
    }

    public function getMondayHoursClose(){
        return $this->_mondayHoursClose;
    }

    public function setMondayHoursClose($mondayHoursClose){
        $this->_mondayHoursClose = $mondayHoursClose;
    }
    
    public function getTuesdayHoursDescription(){
        
        if( empty( $this->_tuesdayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_tuesdayHoursOpen, $this->_tuesdayHoursClose );
        }
        else {
            return $this->_tuesdayHoursDescription;
        }
    }

    public function setTuesdayHoursDescription($tuesdayHoursDescription){
        $this->_tuesdayHoursDescription = $tuesdayHoursDescription;
    }

    public function getTuesdayHoursOpen(){
        return $this->_tuesdayHoursOpen;
    }

    public function setTuesdayHoursOpen($tuesdayHoursOpen){
        $this->_tuesdayHoursOpen = $tuesdayHoursOpen;
    }
        
    public function getTuesdayHoursClose(){
        return $this->_tuesdayHoursClose;
    }

    public function setTuesdayHoursClose($tuesdayHoursClose){
        $this->_tuesdayHoursClose = $tuesdayHoursClose;
    }
        
    public function getWednesdayHoursDescription(){
        
        if( empty( $this->_wednesdayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_wednesdayHoursOpen, $this->_wednesdayHoursClose );
        }
        else {
            return $this->_wednesdayHoursDescription;
        }
    }

    public function setWednesdayHoursDescription($wednesdayHoursDescription){
        $this->_wednesdayHoursDescription = $wednesdayHoursDescription;
    }

    public function getWednesdayHoursOpen(){
        return $this->_wednesdayHoursOpen;
    }

    public function setWednesdayHoursOpen($wednesdayHoursOpen){
        $this->_wednesdayHoursOpen = $wednesdayHoursOpen;
    }

    public function getWednesdayHoursClose(){
        return $this->_wednesdayHoursClose;
    }

    public function setWednesdayHoursClose($wednesdayHoursClose){
        $this->_wednesdayHoursClose = $wednesdayHoursClose;
    }
        
    public function getThursdayHoursDescription(){
        if( empty( $this->_thursdayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_thursdayHoursOpen, $this->_thursdayHoursClose );
        }
        else {
            return $this->_thursdayHoursDescription;
        }
    }

    public function setThursdayHoursDescription($thursdayHoursDescription){
        $this->_thursdayHoursDescription = $thursdayHoursDescription;
    }

    public function getThursdayHoursOpen(){
        return $this->_thursdayHoursOpen;
    }

    public function setThursdayHoursOpen($thursdayHoursOpen){
        $this->_thursdayHoursOpen = $thursdayHoursOpen;
    }

    public function getThursdayHoursClose(){
        return $this->_thursdayHoursClose;
    }

    public function setThursdayHoursClose($thursdayHoursClose){
        $this->_thursdayHoursClose = $thursdayHoursClose;
    }
        
    public function getFridayHoursDescription(){
        if( empty( $this->_fridayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_fridayHoursOpen, $this->_fridayHoursClose );
        }
        else {
            return $this->_fridayHoursDescription;
        }
    }

    public function setFridayHoursDescription($fridayHoursDescription){
        $this->_fridayHoursDescription = $fridayHoursDescription;
    }

    public function getFridayHoursOpen(){
        return $this->_fridayHoursOpen;
    }

    public function setFridayHoursOpen($fridayHoursOpen){
        $this->_fridayHoursOpen = $fridayHoursOpen;
    }

    public function getFridayHoursClose(){
        return $this->_fridayHoursClose;
    }

    public function setFridayHoursClose($fridayHoursClose){
        $this->_fridayHoursClose = $fridayHoursClose;
    }
        
    public function getSaturdayHoursDescription(){
        if( empty( $this->_saturdayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_saturdayHoursOpen, $this->_saturdayHoursClose );
        }
        else {
            return $this->_saturdayHoursDescription;
        }
    }

    public function setSaturdayHoursDescription($saturdayHoursDescription){
        $this->_saturdayHoursDescription = $saturdayHoursDescription;
    }
    
    public function getSaturdayHoursOpen(){
        return $this->_saturdayHoursOpen;
    }

    public function setSaturdayHoursOpen($saturdayHoursOpen){
        $this->_saturdayHoursOpen = $saturdayHoursOpen;
    }
    
    public function getSaturdayHoursClose(){
        return $this->saturdayHoursClose;
    }

    public function setSaturdayHoursClose($saturdayHoursClose){
        $this->saturdayHoursClose = $saturdayHoursClose;
    }

    public function getSundayHoursDescription(){
        if( empty( $this->_sundayHoursDescription ) ) {
            return Connect_ComputerCenter_Day::getDescriptionString(
                    $this->_sundayHoursOpen, $this->_sundayHoursClose );
        }
        else {
            return $this->_sundayHoursDescription;
        }
    }
    
    public function setSundayHoursDescription($sundayHoursDescription){
        $this->_sundayHoursDescription = $sundayHoursDescription;
    }
    
    public function getSundayHoursOpen(){
        return $this->_sundayHoursOpen;
    }

    public function setSundayHoursOpen($sundayHoursOpen){
        $this->_sundayHoursOpen = $sundayHoursOpen;
    }
    
    public function getSundayHoursClose(){
        return $this->_sundayHoursClose;
    }

    public function setSundayHoursClose($sundayHoursClose){
        $this->_sundayHoursClose = $sundayHoursClose;
    }
    
    public function getHasInternetAccess(){
        return $this->_hasInternetAccess;
    }

    public function setHasInternetAccess($hasInternetAccess){
        $this->_hasInternetAccess = $hasInternetAccess;
    }
        
    public function getNumberOfWorkstations(){
            return $this->_numberOfWorkstations;
    }

    public function setNumberOfWorkstations($numWorkstations){
            $this->_numberOfWorkstations = $numWorkstations;
    }

    public function getTimeLimitInMinutes(){
            return $this->_timeLimitInMinutes;
    }

    public function setTimeLimitInMinutes($timeLimitMinutes){
            $this->_timeLimitInMinutes = $timeLimitMinutes;
    }

    public function gethasWifiAccess(){
            return $this->_hasWifiAccess;
    }

    public function setHasWifiAccess($hasWifiAccess){
            $this->_hasWifiAccess = $hasWifiAccess;
    }

    public function getWifiDescription(){
            return $this->_wifiDescription;
    }

    public function setWifiDescription($wifiAccessDescription){
            $this->_wifiDescription = $wifiAccessDescription;
    }
    
    public function getNumberOfStaff(){
            return $this->_numberOfStaff;
    }

    public function setNumberOfStaff($numStaff){
            $this->_numberOfStaff = $numStaff;
    }
    
    public function getHasDisabledAccess(){
            return $this->_hasDisabledAccess;
    }

    public function setHasDisabledAccess($hasDisabledAccess){
            $this->_hasDisabledAccess = $hasDisabledAccess;
    }
    
    public function getCenterLanguages(){
            return $this->_centerLanguages;
    }

    public function setCenterLanguages($centerLanguages){
        
        if( is_array( $centerLanguages ) ) 
        {
            $this->_centerLanguages = implode( ',', $centerLanguages );
        }
        else {
            $this->_centerLanguages = $centerLanguages;
        }
    }

    public function getServiceAge(){
        return $this->_serviceAge;
    }

    public function setServiceAge($serviceAge){
        $this->_serviceAge = $serviceAge;
    }
    
    public function getServiceAgeDescription(){
        return $this->_serviceAgeDescription;
    }

    public function setServiceAgeDescription($serviceAgeDescription){
        $this->_serviceAgeDescription = $serviceAgeDescription;
    }

    public function getAncillaryProgrammingDescription(){
        return $this->_ancillaryProgrammingDescription;
    }

    public function setAncillaryProgrammingDescription($ancillaryProgrammingDescription){
        $this->_ancillaryProgrammingDescription = $ancillaryProgrammingDescription;
    }
        
    public function getCenterWebsite(){
        return $this->_centerWebsite;
    }

    public function setCenterWebsite($url){
        $this->_centerWebsite = $url;
    }
    
    public function getCenterEmailContact(){
        return $this->_centerEmailContact;
    }

    public function setCenterEmailContact($emailContact){
        $this->_centerEmailContact = $emailContact;
    }
    
    public function getPendingConfirmation(){
        return $this->_pendingConfirmation;
    }

    public function setPendingConfirmation($pendingConfirmation){
        $this->_pendingConfirmation = $pendingConfirmation;
    }
    
    public function getTimestamp(){
        return $this->_timestamp;
    }

    public function setTimestamp($timestamp){
        $this->_timestamp = $timestamp;
    }
    
    /**
     * given the unix timesetamp timeStamp, figures out whether the center
     * is open or closed during that time.
     * 
     * If it cannot determine whether the center is open or closed, will return
     * false.
     * 
     * @param string $timeStamp - the unix timestamp
     * @return string yes for open, no for closed, unknown if unknown
     */
    public function getOpenStatus( $timeStamp ) {
        $logPrefix = __CLASS__ . "->". __FUNCTION__ . ": ";
        
        // get the day Monday,Tuesday.....
        $testDay = date( 'l', $timeStamp );
        
        // get the timestamps HH:MM AM/PM format
        $testTime = date( 'h:i A', $timeStamp );
        
        $times = $this->getOpenCloseTimes($testDay);
        
        $openTime = $times[0];
        $closeTime = $times[1];
        
        Connect_FileLogger::debug( $logPrefix . "testing day='$testDay' with time='$testTime',"
                ."open time is $openTime and close time is $closeTime" );
        
        $retVal;
        
        if( empty($openTime) || empty($closeTime) ) {
            $retVal = Connect_ComputerCenter_OpenStatus::$UNKNOWN;
        }
        else if( strtotime( $testTime ) > strtotime( $openTime ) 
                && strtotime( $testTime ) < strtotime( $closeTime ) ) {
            $retVal = Connect_ComputerCenter_OpenStatus::$OPEN;
        }
        else {
            $retVal = Connect_ComputerCenter_OpenStatus::$CLOSED;
        }
        
        return $retVal;
    }
    
    /**
     * given the string day, returns the centers open or closed time.  Returns empty
     * string if invalid input is given.
     * 
     * @para m string $day the day in string format, Monday, Tuesday......
     * @return mixed a two element array with 0 as openTime string and 1 as closedTime string
     */
    protected function getOpenCloseTimes( $day ) {
        
        $openTime = '';
        $closeTime = '';
        
        switch( $day ) {
            case( 'Monday' ) :
                $openTime = $this->getMondayHoursOpen();
                $closeTime = $this->getMondayHoursClose();
                break;
            
            case( 'Tuesday' ) :
                $openTime = $this->getTuesdayHoursOpen();
                $closeTime = $this->getTuesdayHoursClose();
                break;
            
            case( 'Wednesday' ) :
                $openTime = $this->getWednesdayHoursOpen();
                $closeTime = $this->getWednesdayHoursClose();
                break;
            
            case( 'Thursday' ) :
                $openTime = $this->getThursdayHoursOpen();
                $closeTime = $this->getThursdayHoursClose();
                break;
            
            case( 'Friday' ) :
                $openTime = $this->getFridayHoursOpen();
                $closeTime = $this->getFridayHoursClose();
                break;
            
            case( 'Saturday' ) :
                $openTime = $this->getSaturdayHoursOpen();
                $closeTime = $this->getSaturdayHoursClose();
                break;
            
            case( 'Sunday' ) :
                $openTime = $this->getSundayHoursOpen();
                $closeTime = $this->getSundayHoursClose();
                break;
        }
        return array( $openTime, $closeTime );
    }
    
    public function getDays() {
        
        $array = array(
            new Connect_ComputerCenter_Day( 'Monday', 
                    $this->_mondayHoursOpen, $this->_mondayHoursClose ),
            new Connect_ComputerCenter_Day( 'Tuesday',
                    $this->_tuesdayHoursOpen, $this->_tuesdayHoursClose ),
            new Connect_ComputerCenter_Day( 'Wednesday',
                    $this->_wednesdayHoursOpen, $this->_wednesdayHoursClose ),
            new Connect_ComputerCenter_Day( 'Thursday',
                    $this->_thursdayHoursOpen, $this->_thursdayHoursClose ),
            new Connect_ComputerCenter_Day( 'Friday',
                    $this->_fridayHoursOpen, $this->_fridayHoursClose ),
            new Connect_ComputerCenter_Day( 'Saturday',
                    $this->_saturdayHoursOpen, $this->_saturdayHoursClose ),
            new Connect_ComputerCenter_Day( 'Sunday',
                    $this->_sundayHoursOpen, $this->_sundayHoursClose )
        );
        
        return $array;
    }
}