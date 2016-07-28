<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseCurrency_Manager
 *
 * @author Viktor
 */
class CourseCurrency_Manager extends Core_Domen_Manager_Abstract {
    
    public function fetchAllByPeriod(Core_Date $dateStart, Core_Date $dateEnd) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Period(array($dateStart, $dateEnd)));
        return parent::fetchAllByFilter($filters);
    }
    
    public function fetchAllByPeriodByCode(Core_Date $dateStart, Core_Date $dateEnd, $code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Period(array($dateStart, $dateEnd)))
                ->addFilter(new CourseCurrency_Filter_Code($code));
        return parent::fetchAllByFilter($filters);
    }

    public function getByDate(Core_Date $date) {
		$filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Date($date));
        return parent::getByFilter($filters);
    }
    
    public function lastByCurrencyCode($code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Code($code));
        
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CourseCurrency_Order_Id('DESC'));
        
        $paginator = Zend_Paginator::factory(1);
        Zend_Paginator::setDefaultItemCountPerPage(1);
        $paginator->setCurrentPageNumber(1);
        
        $coll = $this->fetchAllByFilter($filters, $paginator, $orders);
        if ($coll->count()) {
            return $coll->first();
        }
        return null;
    }
    
}
