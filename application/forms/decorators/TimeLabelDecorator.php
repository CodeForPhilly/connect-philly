<?php
/**
 * Description of ConnectLabel
 *
 * @author JimS
 */
class Application_Form_Decorator_TimeLabelDecorator  extends Zend_Form_Decorator_Abstract {

    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        
        $label = '<div class="connect-form-labeltext-text">'.$label.'</div>';
        
        if ($element->isRequired()) {
            $label .= '<div class="connect-form-labeltext-required">(required)</div>';
        }
        
        $label .= '<div class="clearingdiv"></div>';
        
        $label = '<div class="connect-form-labeltext">'.$label.'</div>';
        
        return $label;
    }
    
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }

        //$separator = $this->getSeparator();
        //$placement = $this->getPlacement();
        $label     = $this->buildLabel();
        //$input     = $this->buildInput();
        //$errors    = $this->buildErrors();
        //$desc      = $this->buildDescription();
        

        $class = '';
        if( preg_match( "/open/", strtolower($this->getElement()->getFullyQualifiedName() ) ) ) {
            $class=  'connect-time-open';
        }
        else {
            $class = 'connect-time-close';
        }
        
        $markup = '<div class="connect-form-element '.$class.'">'
                        .'<div class="connect-form-label">'
                            . $label
                        .'</div><!-- end connect-form-label -->' 
                        . $content
                 .'</div>'
                ;
        return $markup;
    }
}

?>
