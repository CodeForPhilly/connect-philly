<?php

/**
 * Description of Application_Form_ConnectSubform
 *
 * @author JimS
 */
class Application_Form_ConnectSubform extends Zend_Form_SubForm {
    
    protected $_pageDescription;
    protected $_dataDescription;
    
    public function init() {}
    
    public function setPageDescription( $description ) {
        $this->_pageDescription = $description;
    }
    
    public function getPageDescription() {
        return $this->_pageDescription;
    }
    
    public function setDataDescription( $description ) {
        $this->_dataDescription = $description;
    }
    
    public function getDataDescription() {
        return $this->_dataDescription;
    }
}
?>
