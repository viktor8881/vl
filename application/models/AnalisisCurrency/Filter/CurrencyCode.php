<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalisisCurrency_Filter_CurrencyCode
 *
 * @author Viktor
 */
class AnalisisCurrency_Filter_CurrencyCode extends Core_Domen_Filter_Abstract {
    
    
    public function filter($value) {
        return (string)$value;
    }
    
}
