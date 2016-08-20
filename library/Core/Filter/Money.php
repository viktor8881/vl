<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Filter_Money
 *  фильтр денежных величин
 * @author Viktor Ivanov
 */
class Core_Filter_Money extends Core_Filter_Float {
        
    
    public function filter($value) 
    {
        if ($value) {
            $value = parent::filter($value);
            $value = Core_Math::roundMoney($value);
        }
        return $value;
    }
    
    
}

