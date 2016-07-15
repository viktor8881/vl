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
class Core_Form_Decorator_Description extends Zend_Form_Decorator_Description {

    public function __construct($options = null) {
        $options['class'] = isset($options['class'])?$options['class'].' help-block':'help-block';
        if (!isset($options['tag'])) {
            $options['tag'] = 'p';
        }
        parent::__construct($options);
    }

    
}

