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
class Core_Form_Decorator_HtmlTag extends Zend_Form_Decorator_HtmlTag {


    public function __construct($options = null) {
        $options['tag'] = 'div';
        parent::__construct($options);
    }

    
}

