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
class Core_Form_Decorator_BootstrapControl extends Zend_Form_Decorator_Abstract {
    
    
    public function render($content) 
    {
        $class = isset($this->_options['class'])?' '.$this->_options['class']:'';
        $element = $this->getElement();
        if ($element && $element instanceof Zend_Form_Element ) {
            $class.= ($element->hasErrors() && $element->getDecorator('Errors') )?' has-error':'';
        }
        return '<div class="form-group'.$class.'">'.$content.'</div>';
    }

    
}

