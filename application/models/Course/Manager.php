<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Course_Manager
 *
 * @author Viktor
 */
class Course_Manager extends Core_Domen_Manager_Abstract {
    
    public function fetchAllByPeriod(Core_Date $dateStart, Core_Date $dateEnd) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Course_Filter_Period(array($dateStart, $dateEnd)));
        return parent::fetchAllByFilter($filters);
    }
    
    public function fetchAllByPeriodByCode(Core_Date $dateStart, Core_Date $dateEnd, $code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Course_Filter_Period(array($dateStart, $dateEnd)))
                ->addFilter(new Course_Filter_Code($code));
        return parent::fetchAllByFilter($filters);
    }

    public function getByDate(Core_Date $date) {
		$filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new Course_Filter_Date($date));
        return parent::getByFilter($filters);
	}
    
}
