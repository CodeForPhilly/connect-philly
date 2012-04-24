<?php

/**
 * Description of NoNextCenter
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_Response_NoNextCenter {
    
    public function getMessage() {
        $msg = 'We\'re sorry, there are no further centers '
                . 'that correspond to your request.';
        
        return $msg;
    }
}

?>
