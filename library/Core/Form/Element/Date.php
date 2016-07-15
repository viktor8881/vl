<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Элемент указания кол-во и ед измерения идиниц
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_Date extends Core_Form_Element_DateTime {
    
    
    public function __construct($spec, $options = null) {
        $options['pickTime'] = false;
        if (!isset($options['format'])) {
            $options['format'] = self::FORMAT_DATE;
        }
        parent::__construct($spec, $options);
    }
    
    
}
