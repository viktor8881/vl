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
class InvestmentMetal_Collection extends Core_Domen_CollectionAbstract {
    
    
   public function getSumByBalance($balance) {
        $result = 0;
        foreach ($this->getIterator() as $invest) {
            if (Core_Math::compare($invest->getCount(), $balance, 6) == 1) {
                $result += $invest->getSum();
                $balance -= $invest->getCount();
            }else{
                $result += $balance*$invest->getCourse();
                break;
            }
        }
        return $result;
    }
    
    public function listId() {
        return array_keys($this->_values);
    }
    
}
