<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Currency_Manager
 *
 * @author Viktor
 */
class Currency_Manager extends Core_Domen_Manager_Abstract {
    
    private $_currencies = array(
        'R01235'=>'Доллар США', 
        'R01239'=>'Евро',        
        'R01035'=>'Фунт стерлингов',
        'R01820'=>'Японская иена',
        'R01775'=>'Швейцарский франк',
        'R01010'=>'Австралийский доллар',
        'R01350'=>'Канадский доллар');
    
    
    public function listCurrencies() {
        return $this->_currencies;
    }

}
