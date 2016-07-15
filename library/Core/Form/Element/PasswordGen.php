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
class Core_Form_Element_PasswordGen extends Core_Form_Element_Text {
    
    public $helper = 'passwordGen';
    
    
    public function __construct($spec, $options = null) 
    {
        parent::__construct($spec, $options);
        $view = $this->getView();
        if ($view) {
            $view->addBasePath('Core/Form', 'Core_Form');
        }
    }
    
    
}
