<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Connect_HtmlTag_Decorator
 *
 * @author JimS
 */
class Application_Form_Decorator_Composite extends Zend_Form_Decorator_Abstract {


    public function buildLabel()
    {
        $element = $this->getElement();
        
        $label = '<div class="connect-form-labeltext">';
        $label .= '<div class="connect-form-labeltext-text">'.$element->getLabel().'</div>';
        
        if ($element->isRequired()) {
            $label .= '<span class="connect-form-labeltext-required">&nbsp;*</span>';
        }
        $label .= ':';
        $label .= '</div>';// end connect-form-labeltext
        return $label;
    }
    
    public function buildInput()
    {
        $element = $this->getElement();
        
        //Zend_Debug::dump( $element->getView()->form->getName() );
        //exit;
        //Zend_Debug::dump( $element );
        //exit;
        $helper  = $element->helper;
        
        $name = $element->getName();
        
        if( isset( $element->getView()->form )
                && $element->getView()->form instanceof Zend_Form_Subform ) {
            $name = $element->getView()->form->getName() . '[' . $name . ']';
        }
        
        return $element->getView()->$helper(
            $name,
            $element->getValue(),
            $element->getAttribs(),
            $element->options
        );
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        $errors = $element->getMessages();
        
        if (empty($errors)) {
            return '';
        }
        return $element->getView()->formErrors($errors, $this->getOptions() );
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return '<div class="description">' . $desc . '</div>';
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


        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();

        $output = '<div class="connect-form-element">'
                    . '<div class="connect-form-label">'
                        . $label
                    . '</div><!-- end connect-form-label -->'
                    . '<div class="connect-form-input">'
                        . $input
                        . '<div class="connect-form-error">'
                            . $errors
                        . '</div>';
        
        if( $desc != '' ) {
            $output .= '<div class="connect-form-description">'
                        . $desc
                    . '</div>';
        }
            $output .= '</div>'
                . '</div>';

        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}

?>