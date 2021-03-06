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
class Metal_Collection extends Core_Domen_CollectionAbstract {
    
    public function hasCode($code) {
        foreach ($this->getIterator() as $metal) {
            if ($metal->getCode() == $code) {
                return true;
            }
        }
        return false;
    }
    
    public function listName() {
        $res = array();
        foreach ($this->getIterator() as $model) {
            $res[] = $model->getName();
        }
        return $res;
    }
    
}
