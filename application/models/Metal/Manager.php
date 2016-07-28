<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Metal_Manager
 *
 * @author Viktor
 */
class Metal_Manager extends Core_Domen_Manager_Abstract {
    
    public function fetchAllToArray() {
        $result = array();
        foreach (parent::fetchAll() as $metal) {
            $result[$metal->getCode()] = $metal->getName();
        }
        return $result;
    }
    
    public function getByCode($code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Metal_Filter_Code($code));
        return $this->getByFilter($filters);
    }
    
}
