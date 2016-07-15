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
class Core_Form_Element_TextPassword extends Core_Form_Element_Password {
    
    public $helper = 'passwordPrepend';
    
//    public function __construct($spec, $options = null) 
//    {
//        parent::__construct($spec, $options);
//        $this->getView()->addBasePath('Core/Form', 'Core_Form');
//    }
    
}
