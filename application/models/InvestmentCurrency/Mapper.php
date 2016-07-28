<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvestmentCurrency_Mapper
 *
 * @author Viktor
 */
class InvestmentCurrency_Mapper extends Core_Domen_Mapper_Abstract {
    
    protected $_table='investment_currency';
    protected $_primary='id';
    
    public function addOrder(\Core_Domen_Order_Abstract $order, \Zend_Db_Select $select) {
        switch (get_class($order)) {
            case 'InvestmentCurrency_Order_Id':
                $select->order('id '.$order->getTypeOrder());
                break;
            
            default:
                break;
        }
    }

    public function addWhereByFilter(\Core_Domen_Filter_Abstract $filter, \Zend_Db_Select $select) {
        $values = $filter->getValue();
        switch (get_class($filter)) {
            case 'InvestmentCurrency_Filter_Code':                
                $select->where('currency_code IN(?)', $values);
                break;
            case 'InvestmentCurrency_Filter_Type':                
                $select->where('type = ?', current($values));
                break;
            default:
                break;
        }
        return null;
    }

    
}
