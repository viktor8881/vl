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
class Core_Form_Element_TextMoney extends Core_Form_Element_TextAppend {
    
    public $helper = 'textMoney';
    
    public function __construct($spec, $options = null) {
        $options['text'] = Core_Container::getManager('setting')->getMoneyUnit();
//        if (!isset($options['filters'])) {
//            $options['filters'] = array(new Core_Filter_Money());
//        }
        if (!isset($options['validators'])) {
            $options['validators'] = array(new Core_Validate_Money());
        }
        parent::__construct($spec, $options);
    }

    
}
