<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Checkbox
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_Checkbox extends Zend_Form_Element_Checkbox {
 
    
    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array(
                    'ViewHelper',
                    array('Label'=>new Core_Form_Decorator_CheckBoxLabel(array('for'=>$this->getId()))),
                    array('HtmlTag'=>new Core_Form_Decorator_HtmlTag(array('class'=>'checkbox'))),
                    'Description',
                    'Errors',
                    ));
            $this->addDecorator(new Core_Form_Decorator_WrapperElement(array('class'=>'col-sm-offset-3 col-sm-9')))
                    ->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
    }
        
     
}

