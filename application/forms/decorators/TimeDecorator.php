<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Time
 *
 * @author JimS
 */
class Application_Form_Decorator_TimeDecorator extends Zend_Form_Decorator_Abstract
{
    protected $elementName = null;
    
    public function render($content)
    {
        
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }
        
        $element = $this->getElement();
        $this->elementName    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());

        
        $markup  =  '<div class="connect-form-input">'
                        .'Hour'.$this->getHourSelect().'Minutes'.$this->getMinuteSelect().$this->getAmpmSelect()
                    .'</div>';
        return $markup;
    }
    
    protected function getHourSelect() {
        
        return '<select name="'.$this->elementName.'[hour]">'
                .'<option>00</option>'
                .'<option>01</option>'
                .'<option>02</option>'
                .'<option>03</option>'
                .'<option>04</option>'
                .'<option>05</option>'
                .'<option>06</option>'
                .'<option>07</option>'
                .'<option>08</option>'
                .'<option>09</option>'
                .'<option>10</option>'
                .'<option>11</option>'
                .'<option>12</option>'
                . '</select>'
            ;
    }
    
    protected function getMinuteSelect() {
        return '<select name="'.$this->elementName.'[minutes]">'
                .'<option>00</option>'
                .'<option>30</option>'
                . '</select>'
            ;
    }
    
    protected function getAmpmSelect() {
        return '<select name="'.$this->elementName.'[ampm]">'
                .'<option>AM</option>'
                .'<option>PM</option>'
                . '</select>'
            ;
    }
}

?>
