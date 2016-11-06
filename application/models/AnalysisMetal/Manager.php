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
        return $this->fetchAllByDate(new Core_Date());
    }
    
    public function fetchAllByDate(Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisMetal_Filter_Date($date));
        return $this->fetchAllByFilter($filters);
    }
    
    public function getLastByType($type) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisMetal_Filter_Type($type));
        return $this->getByFilter($filters);
    }

}
