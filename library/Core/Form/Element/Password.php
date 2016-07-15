<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Captcha
 *
 * @author Victor
 */
class Core_Form_Element_Password extends Zend_Form_Element_Password {
        
    
    public function __construct($spec, $options = null) {
        $options['class'] = 'form-control';
        parent::__construct($spec, $options);
        
        $this->addPrefixPath(
            'Core_Form_Decorator',
            'Core/Form/Decorator',
            'decorator'
        );
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
