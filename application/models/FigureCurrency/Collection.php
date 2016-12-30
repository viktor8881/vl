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
class FigureCurrency_Collection extends Core_Domen_CollectionAbstract {
    
    public function orderByOwerTime() {
        usort($this->_values, array($this, "_orderByOwerTime"));
        return $this;
    }
    
    private function _orderByOwerTime($a, $b) {
        return $a->isOvertime()?-1:1;
    }
    
    
    
}
