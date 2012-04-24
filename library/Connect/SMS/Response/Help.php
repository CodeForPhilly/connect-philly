<?php

/**
 * Description of Help
 *
 * @author Jim Smiley twitter:@jimRsmiley
 */
class Connect_SMS_Response_Help extends Connect_SMS_Response {
    
    public function getMessage() {
        
        $searchTerms = Application_Model_DbTable_SearchTerms::getSearchTerms();
        $searchStr = implode( ', ', $searchTerms );
        $msg = 'Text address to find closest Internet access. '
                .'End message with terms '
                .$searchStr
                . " or open to search for services. Ex.:"
                . "\"1515 Market St. retail\"";
        
        return $msg;
    }
}

?>
