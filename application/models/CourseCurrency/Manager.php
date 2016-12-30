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
        
    public function fetchAllByDate(Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Date($date));
        return parent::fetchAllByFilter($filters);
    }
        
    public function fetchAllByDateListCode(Core_Date $date, array $listCodes) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Date($date))
                ->addFilter(new CourseCurrency_Filter_Code($listCodes));
        return parent::fetchAllByFilter($filters);
    }
    
    public function fetchAllByPeriodByCode(Core_Date $dateStart, Core_Date $dateEnd, $code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Period(array($dateStart, $dateEnd)))
                ->addFilter(new CourseCurrency_Filter_Code($code));
        return parent::fetchAllByFilter($filters);
    }
    
    public function getByCodeDate($code, Core_Date $date) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Date($date))
                ->addFilter(new CourseCurrency_Filter_Code($code));
        return $this->getByFilter($filters);
    }
    
    public function getValueCodeByDate($code, Core_Date $date) {
        $model = $this->getByCodeDate($code, $date);
        return ($model)?$model->getValueForOne():0;
    }

    public function hasByDate(Core_Date $date) {
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
    
    public function listValueForAnalysisByCodeToDate($code, Core_Date $date) {
        $result = [];
        
        $paginator = Zend_Paginator::factory(20);
        Zend_Paginator::setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber(1);
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CourseCurrency_Order_Id('DESC'));
        
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseCurrency_Filter_Code($code))
                ->addFilter(new CourseCurrency_Filter_LsEqDate($date));
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
        $filters->addFilter(new CourseCurrency_Filter_Code($code));
        return parent::fetchAllByFilter($filters, $paginator, $orders);
    }
    
    private function isGreater($left, $right) {
        return $left > $right;
    }
    
    private function isLess($left, $right) {
        return $left < $right;
    }
       
    
}