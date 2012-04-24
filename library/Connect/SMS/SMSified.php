<?php

/**
 * 
 * A PHP class for interacting with the SMSified API.
 *
 */
class Connect_SMS_SMSified {
	
    // Private class members.
    private $base_url = 'https://api.smsified.com/v1/';
    private $username;
    private $password;

	
    /**
     * 
     * Class constructor
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        
        $this->config = Zend_Registry::get( 'configuration' );
    }
	
    /**
     * 
     * Send an outbound SMS message.
     * @param string $senderAddress
     * @param string $toAddress
     * @param string $message
     * @param string $notifyURL
     */
    public function sendMessage($senderAddress, $toAddress, $message, $notifyURL=NULL) {
        $loggerPrefix = __CLASS__ . '->' . __FUNCTION__ . ": ";
        
        if( empty($senderAddress) ) {
            throw new SMSifiedException( 'sender address cannot be empty' );
        }
        else if( empty($toAddress) ) {
            throw new SMSifiedException( 'to address cannot be empty' );
        }
        else if( empty($message) ) {
            throw new SMSifiedException( 'message cannot be empty' );
        }
        
        Connect_FileLogger::debug($loggerPrefix."senderAddress=$senderAddress"
                ." toAddress=$toAddress" . " message='$message'" );
        
        $message = urlencode($message);
        $url = $this->base_url . "smsmessaging/outbound/$senderAddress/requests?address=$toAddress&message=$message";
        if($notifyURL) {
            $url .= "&notifyURL=$notifyURL";
        }
        return self::makeAPICall('POST', $url);
    }
	
    /**
     * 
     * Check the delivery status of an outbound SMS message.
     * @param unknown_type $senderAddress
     * @param unknown_type $requirestId
     */
    public function checkStatus($senderAddress, $requestId) {
        $url = $this->base_url . "smsmessaging/outbound/$senderAddress/requests/$requestId/deliveryInfos";
        return self::makeAPICall('GET', $url);
    }
	
    /**
     * 
     * Create a subscription.
     * @param string $senderAddress
     * @param string $direction
     * @param string $notifyURL
     */
    public function createSubscription($senderAddress, $direction, $notifyURL) {
        $url = $this->base_url . "smsmessaging/$direction/$senderAddress/subscriptions?notifyURL=$notifyURL";
        return self::makeAPICall('POST', $url);
    }
	
    /**
     * 
     * View subscrptions
     * @param string $senderAddress
     * @param string $direction
     */
    public function viewSubscriptions($senderAddress, $direction) {
        $url = $this->base_url . "smsmessaging/$direction/subscriptions/?senderAddress=$senderAddress";
        return self::makeAPICall('GET', $url);
    }
	
    /**
     * 
     * Delete an active subscription.
     * @param string $subscriptionId
     * @param string $direction
     */
    public function deleteSubscriptions($subscriptionId, $direction) {
        $url = $this->base_url . "smsmessaging/$direction/subscriptions/$subscriptionId";
        return self::makeAPICall('DELETE', $url);
    }
	
    /**
     * 
     * Get the details of SMS message delivery.
     * @param string $messageId
     * @param array $params
     */
    public function getMessages($messageId=NULL, $params=NULL) {
        $url = $this->base_url . "messages/";

        if($messageId) {
                $url .= "$messageId";
        }
        else {
                $url .= '?';
                foreach($params as $key => $value) {
                        $url .= "$key=$value&";
                }
        }

        return self::makeAPICall('GET', $url);
    }
	
	
    /**
     * Method to make REST API call.
     *
     * @param string $method
     * @param string $url
     * @param string $payload
     * @return string JSON
     */
    private function makeAPICall($method, $url) {

        $_uri = explode('?',$url);
        $url = $_uri[0];
        $request = $_uri[1];

        $sslcert = APPLICATION_PATH
            . DIRECTORY_SEPARATOR 
            . "certificates"
            . DIRECTORY_SEPARATOR
            . "mozilla.pem";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // for debugging
        //curl_setopt($ch, CURLOPT_VERBOSE, 1 );
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        
        // set to false, 0 for expired certs
        //curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , false );
        //curl_setopt( $ch , CURLOPT_SSL_VERIFYHOST , false );
        
        curl_setopt($ch, CURLOPT_CAINFO, $sslcert );

        switch( $method ) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$request); 
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            default:
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;			
        }

        //curl_setopt($ch, CURLOPT_HTTPHEADER, array( "content-length: 0" ) );
        $result = curl_exec($ch);
        $error = curl_error($ch);

        $curl_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($result === false) {
            throw new SMSifiedException( __CLASS__ . '::' . __FUNCTION__ . ' An error occurred: '.$error);
        } else {
            if (substr($curl_http_code, 0, 2) != '20') {
                throw new SMSifiedException('An error occurred: http_code: '.$curl_http_code.' error:'.$result);
            }
            return $result;
        }		
    }
}

/**
 * 
 * A simple class to wrap exceptions.
 *
 */
class SMSifiedException extends Exception {}

/**
 * 
 * Helper class with message direction.
 *
 */
class MessageDirection {
	public static $inbound = 'inbound';
	public static $outbound = 'outbound';
}

?>