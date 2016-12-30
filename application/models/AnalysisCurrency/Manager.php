<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisCurrency_Manager
 *
 * @author Viktor
 */
class AnalysisCurrency_Manager extends Core_Domen_Manager_Abstract {
    
    
    public function fetchAllByToday() {
        return $this->fetchAllByDate(new Core_Date());
    }
    
    public function fetchAllByDate(Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisCurrency_Filter_Created($date));
        return $this->fetchAllByFilter($filters);
    }
    
    
    public function getLastDateByCurrencyCode($сurrencyCode) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisCurrency_Filter_CurrencyCode($сurrencyCode));
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new AnalysisCurrency_Order_Created('DESC'));
        $analysis = $this->getByFilter($filters, $orders);
        if ($analysis) {
            return $analysis->getCreated();
        }
        return null;
    }
    
    /**
     * Each metal on last date.
     * @return []
     */
    public function listByCurrencyOnLastDates() {
        $result = [];
        foreach( $this->getManager('currency')->fetchAll() as $currency) {
            $lastDate = $this->getLastDateByCurrencyCode($currency->getCode());
            if ($lastDate) {
                $filters = new Core_Domen_Filter_Collection();
                $filters->addFilter(new AnalysisCurrency_Filter_CurrencyCode($currency->getCode()))
                        ->addFilter(new AnalysisCurrency_Filter_Created($lastDate));
                $result[] = $this->getManager('AnalysisCurrency')->fetchAllByFilter($filters);
            }
        }
        return $result;
    }
    
    public function fetchAllByCurrencyCodeDate($code, Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new AnalysisCurrency_Filter_CurrencyCode($code))
                ->addFilter(new AnalysisCurrency_Filter_Created($date));
        return $this->fetchAllByFilter($filters);
    }

}
