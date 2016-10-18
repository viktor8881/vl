<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheCourseMetal_Manager
 *
 * @author Viktor
 */
class CacheCourseMetal_Manager extends Core_Domen_Manager_Abstract {    
    
    
    public function fetchAllByPeriodByCodePercent(Core_Date $dateStart, Core_Date $dateEnd, $code, $percent) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CacheCourseMetal_Filter_Period(array($dateStart, $dateEnd)))
                ->addFilter(new CacheCourseMetal_Filter_Code($code))
                ->addFilter(new CacheCourseMetal_Filter_Percent($percent));
        return parent::fetchAllByFilter($filters);
    }
    
    public function lastByCodePercent($code, $percent) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CacheCourseMetal_Filter_Code($code))
                ->addFilter(new CacheCourseMetal_Filter_Percent($percent));
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CacheCourseMetal_Order_Id('DESC'));
        return $this->getByFilter($filters, $orders);
    }
    
    public function fetch5ByCodePercent($code, $percent) {
        return $this->fetchAllByCodePercentCount($code, $percent, 5);
    }
    
    public function fetch7ByCodePercent($code, $percent) {
        return $this->fetchAllByCodePercentCount($code, $percent, 7);
    }
    
    private function fetchAllByCodePercentCount($code, $percent, $count) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CacheCourseMetal_Filter_Code($code))
                ->addFilter(new CacheCourseMetal_Filter_Percent($percent));
        $paginator = Zend_Paginator::factory($count);
        Zend_Paginator::setDefaultItemCountPerPage($count);
        $paginator->setCurrentPageNumber(1);
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CacheCourseMetal_Order_Id('DESC'));
        $rows = $this->fetchAllByFilter($filters, $paginator, $orders);
        if (count($rows)==$count) {
            return $rows->reverse();
        }
        return null;
    }
    
    public function setOperation(array $listId) {
        return $this->getRepository()->setOperation($listId);
    }
    
}
