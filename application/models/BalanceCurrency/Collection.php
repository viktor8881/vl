<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cource_Collection
 *
 * @author Viktor
 */
class BalanceCurrency_Collection extends Core_Domen_CollectionAbstract {
    
    public function hasCode($code) {
        foreach ($this->getIterator() as $metal) {
            if ($metal->getCode() == $code) {
                return true;
            }
        }
        return false;
    }
    
}
