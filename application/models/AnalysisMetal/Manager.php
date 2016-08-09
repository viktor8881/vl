<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisMetal_Manager
 *
 * @author Viktor
 */
class AnalysisMetal_Manager extends Core_Domen_Manager_Abstract {
        
    
    public function fetchAllByToday() {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisMetal_Filter_Date(new Core_Date()));
        return $this->fetchAllByFilter($filters);
    }

}
