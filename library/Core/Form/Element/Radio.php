<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Textarea
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_Radio extends Zend_Form_Element_Radio {
 
    
    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setSeparator('</div><div class="radio">');
            $this->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    'Description',
                    array(array('uno' => 'HtmlTag'), array('tag' => 'div', 'class' => 'radio')),
                    ));
            $this->addDecorator(new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9')))
                    ->addDecorator(new Core_Form_Decorator_Label(array('class'=>'col-sm-3')))
                    ->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
    }


    
    
}

