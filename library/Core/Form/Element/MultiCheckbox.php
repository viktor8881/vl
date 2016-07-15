<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_MultiCheckbox
 *
 * @author victor
 */
class Core_Form_Element_MultiCheckbox extends Zend_Form_Element_MultiCheckbox {
    
    
    
    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);
//        $this->setAttrib('label_class', "allilua");
        if (!isset($options['decorators'])) {
            $this->setSeparator('</div><div class="checkbox">');
            $this->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    'Description',
                    array(array('uno' => 'HtmlTag'), array('tag' => 'div', 'class' => 'checkbox')),
                    ));
            $this->addDecorator(new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9')))
                    ->addDecorator(new Core_Form_Decorator_Label(array('class'=>'col-sm-3')))
                    ->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
    }
    /**
     * @todo - пример обертки в список ul
     */
//    'separator' => '</div><div class="checkbox">',
//				'decorators' => array(
//				    'viewHelper',
//				    'Errors',
//				    array(array('uno' => 'HtmlTag'), array('tag' => 'div', 'class' => 'checkbox')),
////				    array(array('due' => 'HtmlTag'), array('tag' => 'ul','class' => 'check')),
//				)
    
        
        
}

