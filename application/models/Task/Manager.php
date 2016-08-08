<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task_Manager
 *
 * @author Viktor
 */
class Task_Manager extends Core_Domen_Manager_Abstract {
    
    
    public function fetchAllCustom() {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Task_Filter_Type(Task_Model_Abstract::listTypeCustom()));
        return $this->fetchAllByFilter($filters);
    }
    
}
