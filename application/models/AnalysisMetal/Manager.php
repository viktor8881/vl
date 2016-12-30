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
        $filters->addFilter(new AnalysisMetal_Filter_Created($date));
        return $this->fetchAllByFilter($filters);
    }
    
    public function getLastByType($type) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisMetal_Filter_Type($type));
        return $this->getByFilter($filters);
    }
    
    public function getLastDateByMetalCode($metalCode) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisMetal_Filter_MetalCode($metalCode));
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new AnalysisMetal_Order_Created('DESC'));
        $analysis = $this->getByFilter($filters, $orders);
        if ($analysis) {
            return $analysis->getCreated();
        }
        return null;
    }
    
    public function fetchAllByMetalCodeDate($metalCode, Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisMetal_Filter_MetalCode($metalCode))
                ->addFilter(new AnalysisMetal_Filter_Created($date));
        return $this->fetchAllByFilter($filters);
    }

    /**
     * Each metal on last date.
     * @return []
     */
    public function listByMetalsOnLastDates() {
        $result = [];
        foreach( $this->getManager('metal')->fetchAll() as $metal) {
            $lastDate = $this->getLastDateByMetalCode($metal->getCode());
            if ($lastDate) {
                $result[] = $this->fetchAllByMetalCodeDate($metal->getCode(), $lastDate);
            }
        }
        return $result;
    }

}
