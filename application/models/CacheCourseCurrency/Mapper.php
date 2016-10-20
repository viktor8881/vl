<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheCourseCurrency_Mapper
 *
 * @author Viktor
 */
class CacheCourseCurrency_Mapper extends Core_Domen_Mapper_Abstract {
    
    protected $_table='cache_course_currency';
    protected $_primary='id';
    
    
    public function setOperation(array $listId) {
        $values = array('operation'=>CacheCourseMetal_Model::OPERATION);
        $where = parent::getConnect()->quoteInto("id IN (?)", $listId);
        return parent::getConnect()->update($this->_table, $values, $where);
    }
    
    public function addOrder(\Core_Domen_Order_Abstract $order, \Zend_Db_Select $select) {
        switch (get_class($order)) {
            case 'CacheCourseCurrency_Order_Id':
                $select->order('id '.$order->getTypeOrder());
                break;
            default:
                break;
        }
    }

    public function addWhereByFilter(\Core_Domen_Filter_Abstract $filter, \Zend_Db_Select $select) {
        $values = $filter->getValue();
        switch (get_class($filter)) {
            case 'CacheCourseCurrency_Filter_Id':
                $select->where('id IN(?)', $values);
                break;
            case 'CacheCourseCurrency_Filter_Code':                
                $select->where('code IN(?)', $values);
                break;
            case 'CacheCourseCurrency_Filter_Percent':
                $select->where('percent IN(?)', $values);
                break;
            default:
                break;
        }
        return null;
    }

    
}
