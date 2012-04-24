<?php

class Application_View_Helper_RenderDistanceSearchResult extends Zend_View_Helper_Abstract {
	
    protected $_view;
    
    public function setView( Zend_View_Interface $view ) {
        $this->_view = $view;
    }
    
    public function renderDistanceSearchResult( $distanceSearchResult ) {
		
        $html = "<div class=\"computercenter\">\n";

            $html .= $this->_view->renderSummaryComputerCenter( 
                            $distanceSearchResult->getComputerCenter() );

        $html .= "</div>\n";

        $html .= '<div class="distancedata">';
            $html .= '<p>' . round( $distanceSearchResult->getDistance(), 2 ) . ' miles away</p>';
            $html .= '<p><a href="' .  $distanceSearchResult->getDirectionsUrl() . '">';
            $html .= "Get Directions";
            $html .= '</a></p>';
        $html .= '</div>';

        return $html;
    } // end renderDistanceSearchResult
}