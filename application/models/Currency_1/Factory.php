<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Currency_Factory
 *
 * @author Viktor
 */
class Currency_Factory implements Core_Domen_IFactory {
    
    
    public function create(array $values = null) {
        return new Currency_Model($values);
    }

}
