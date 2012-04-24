<?php

/**
 * Description of Time
 *
 * @author JimS
 */
class Application_Form_Decorator_TimeDecoratorasdf extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $format = '<label for=\"%s\">%s</label>'
                .'<input id=\"%s\" name=\"%s\" type=\"text\" value=\"%s\"/>';
        
        $element = $this->getElement();
        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());

        $markup  = sprintf($format, $id, $label, $id, $name, $value);
        return $markup;
    }
}
?>
