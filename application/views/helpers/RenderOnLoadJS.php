<?php

class Application_View_Helper_RenderOnLoadJS extends Zend_View_Helper_Abstract {
	
	public $view;
	
	public function renderOnLoadJS($val) {
			$this->view->customOnloadJS = $val;
	}
	
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
	
    public function setOnLoadJS( $val ) {	
	
    }
}
?>