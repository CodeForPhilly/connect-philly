<?php

/**
 * Description of UnknownAddress
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class UnknownAddress extends Connect_SMS_Response {

    public function getMessage() {
        return "Address '.$this->getRequest()->getMessage().' was not understood. Please modify"
            . " your request and try again.  Text 'HELP' for"
            . " further instructions.";
    }
}

?>
