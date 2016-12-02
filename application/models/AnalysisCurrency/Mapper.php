<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisCurrency_Mapper
 *
 * @author Viktor
 */
class AnalysisCurrency_Mapper extends Core_Domen_Mapper_Abstract {
    
    protected $_table='analysis_currency';
    protected $_primary='id';
    
    public function addOrder(\Core_Domen_Order_Abstract $order, \Zend_Db_Select $select) {
        switch (get_class($order)) {
            case 'AnalysisCurrency_Order_Id':
                $select->order('id '.$order->getTypeOrder());
                break;
            case 'AnalysisCurrency_Order_Created':
                $select->order('created '.$order->getTypeOrder());
                break;
            default:
                break;
        }
    }

    public function addWhereByFilter(\Core_Domen_Filter_Abstract $filter, \Zend_Db_Select $select) {
        $values = $filter->getValue();
        switch (get_class($filter)) {
            case 'AnalysisCurrency_Filter_Created':
                $select->where('created = ?',current($values));
                break;
            case 'AnalysisCurrency_Filter_CurrencyCode':                
                $select->where('currency_code IN(?)', $values);
                break;
            default:
                break;
        }
        return null;
    }

    
}
