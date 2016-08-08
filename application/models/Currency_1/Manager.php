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
        
    
    public function fetchAllToArray() {
        $result = array();
        foreach (parent::fetchAll() as $model) {
            $result[$model->getCode()] = $model->getName();
        }
        return $result;
    }
    
    public function getByCode($code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Currency_Filter_Code($code));
        return $this->getByFilter($filters);
    }
    
    public function fetchAllByCodes($listCode) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Currency_Filter_Code($listCode));
        return $this->fetchAllByFilter($filters);
    }

}
