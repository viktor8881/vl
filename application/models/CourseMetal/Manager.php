<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseMetal_Manager
 *
 * @author Viktor
 */
class CourseMetal_Manager extends Core_Domen_Manager_Abstract {    
    
    public function fetchAllByPeriod(Core_Date $dateStart, Core_Date $dateEnd) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Period(array($dateStart, $dateEnd)));
        return parent::fetchAllByFilter($filters);
    }

    public function fetchAllByDate(Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Date($date));
        return parent::fetchAllByFilter($filters);
    }
    
    public function fetchAllByPeriodByCode(Core_Date $dateStart, Core_Date $dateEnd, $code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Period(array($dateStart, $dateEnd)))
                ->addFilter(new CourseMetal_Filter_Code($code));
        return parent::fetchAllByFilter($filters);
    }
    
    public function listValueForAnalysisByCodeToDate($code, Core_Date $date) {
        $result = [];
        
        $paginator = Zend_Paginator::factory(20);
        Zend_Paginator::setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber(1);
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CourseMetal_Order_Id('DESC'));
        
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Code($code))
                ->addFilter(new CourseMetal_Filter_LsEqDate($date));
        $rows = parent::fetchAllByFilter($filters, $paginator, $orders);

        if ($rows->count() > 1) {
            $sign = null;
            $prev = $rows->first();
            $i=0;
            foreach ($rows as $row) {
                if (++$i == 1) {
                    $result[$row->getDateFormatDMY()] = $row->getValue();
                    continue;
                }
                if (is_null($sign)) {
                    if ($prev->getValue() > $row->getValue()) {
                        $sign = 'isGreater';
                    }elseif($prev->getValue() < $row->getValue()){
                        $sign = 'isLess';
                    }else{
                        break;
                    }
                    $result[$row->getDateFormatDMY()] = $row->getValue();
                    $prev = $row;
                    continue;
                }
                if ( $this->{$sign}($prev->getValue(), $row->getValue()) ) {
                    $result[$row->getDateFormatDMY()] = $row->getValue();
                    $prev = $row;
                    continue;
                }else{
                    break;
                }
            }
        }
        return $result;
    }
    
    public function fetchAllByCode($code, \Zend_Paginator $paginator = null, \Core_Domen_Order_Collection $orders = null) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Code($code));
        return parent::fetchAllByFilter($filters, $paginator, $orders);
    }

//    public function getSellCodeByDate($code, Core_Date $date) {
//        $filters = new Core_Domen_Filter_Collection();
//        $filters->addFilter(new CourseMetal_Filter_Date($date))
//                ->addFilter(new CourseMetal_Filter_Code($code));
//        $model = parent::getByFilter($filters);
//        return ($model)?$model->getSell():0;
//    }
    
    public function  getByCodeDate($code, Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Date($date))
                ->addFilter(new CourseMetal_Filter_Code($code));
        return $this->getByFilter($filters);
    }

    public function getValueCodeByDate($code, Core_Date $date) {
        $model = $this->getByCodeDate($code, $date);
        return ($model)?$model->getSell():0;
    }
        
    public function hasByDate(Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Date($date));
        return parent::getByFilter($filters);
    }
        
    public function lastByMetalCode($code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Code($code));
        
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CourseMetal_Order_Id('DESC'));
        
        $paginator = Zend_Paginator::factory(1);
        Zend_Paginator::setDefaultItemCountPerPage(1);
        $paginator->setCurrentPageNumber(1);
        
        $coll = $this->fetchAllByFilter($filters, $paginator, $orders);
        if ($coll->count()) {
            return $coll->first();
        }
        return null;
    }
    
    private function isGreater($left, $right) {
        return $left > $right;
    }
    
    private function isLess($left, $right) {
        return $left < $right;
    }
    
}
