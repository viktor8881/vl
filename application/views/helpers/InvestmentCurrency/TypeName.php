<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 *
 * @author Viktor Ivanov
 */
class View_Helper_InvestmentCurrency_TypeName extends Zend_View_Helper_Abstract
{
    
    public function investmentCurrency_TypeName($type) 
    {
        if ($type == InvestmentCurrency_Model::TYPE_BUY) {
            return 'покупка';
        }elseif ($type == InvestmentCurrency_Model::TYPE_SELL) {
            return 'продажа';
        }
        return '';
    }
        
}
