<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Элемент времени
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_Time extends Core_Form_Element_DateTime {
    
    
    public function __construct($spec, $options = null) {
        $options['pickDate'] = false;
        if (!isset($options['format'])) {
            $options['format'] = self::FORMAT_TIME;
        }
        $options['icon'] = 'glyphicon-time';
        parent::__construct($spec, $options);
    }
    
    
}
