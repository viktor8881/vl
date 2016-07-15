<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_MultiSelect
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_MultiSelect extends Zend_Form_Element_Multiselect{
    
    
    public function __construct($spec, $options = null) {
        $options['class'] = isset($options['class'])?$options['class'].' form-control':'form-control';
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    'Description',
                    array('HtmlTag'=>new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9'))),
                    array('Label'=>new Core_Form_Decorator_Label(array('class'=>'col-sm-3'))),
                    ));
            $this->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
    }

}