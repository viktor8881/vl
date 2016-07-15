<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Currency_Mapper
 *
 * @author Viktor
 */
class Currency_Mapper extends Core_Domen_Mapper_Abstract {
    
    protected $_table='currency';
    protected $_primary='id';
    
    public function addOrder(\Core_Domen_Order_Abstract $order, \Zend_Db_Select $select) {
        
    }

    public function addWhereByFilter(\Core_Domen_Filter_Abstract $filter, \Zend_Db_Select $select) {
//        $values = $filter->getValue();
//        switch (get_class($filter)) {
//            case 'Currency_Filter_Period':
//                $dateStart = $values[0];
//                $select->where('date BETWEEN '.$this->getConnect()->quote($values[0]).' AND '.$this->getConnect()->quote($values[1]));
//                break;
//            default:
//                break;
//        }
        return null;
    }

    
}
