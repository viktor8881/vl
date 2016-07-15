<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Decorator_NameDecor
 *
 * @author Victor
 */
class Core_Form_Decorator_Label extends Zend_Form_Decorator_Label {
    
    
    public function __construct($options = null) {
        $options['class'] = isset($options['class'])?$options['class'].' control-label':'control-label';
        parent::__construct($options);
    }
    
    public function render($content) 
    {
        $element = $this->getElement();
        if (strlen($element->getLabel())) {
            $element->setLabel($element->getLabel().':');
            if ( $element->isRequired()) {
                $element->setLabel($element->getLabel().' *');
            }
        }
        return parent::render($content);
    }
    
}

