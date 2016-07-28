<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseCurrency_Mapper
 *
 * @author Viktor
 */
class CourseCurrency_Mapper extends Core_Domen_Mapper_Abstract {
    
    protected $_table='course_currency';
    protected $_primary='id';
    
    public function addOrder(\Core_Domen_Order_Abstract $order, \Zend_Db_Select $select) {
        switch (get_class($order)) {
            case 'CourseCurrency_Order_Id':
                $select->order('id '.$order->getTypeOrder());
                break;
            default:
                break;
        }
    }

    public function addWhereByFilter(\Core_Domen_Filter_Abstract $filter, \Zend_Db_Select $select) {
        $values = $filter->getValue();
        switch (get_class($filter)) {
			case 'CourseCurrency_Filter_Date':
                $select->where('date = ?',current($values));
                break;
            case 'CourseCurrency_Filter_Period':
                $select->where('date BETWEEN '.$this->getConnect()->quote($values[0]).' AND '.$this->getConnect()->quote($values[1]));
                break;
            case 'CourseCurrency_Filter_Code':                
                $select->where('code IN(?)', $values);
                break;
            default:
                break;
        }
        return null;
    }

    
}
