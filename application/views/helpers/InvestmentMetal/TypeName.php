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
class View_Helper_InvestmentMetal_TypeName extends Zend_View_Helper_Abstract
{
    
    public function investmentMetal_TypeName($type) 
    {
        if ($type == InvestmentMetal_Model::TYPE_BUY) {
            return _('покупка');
        }elseif ($type == InvestmentMetal_Model::TYPE_SELL) {
            return _('продажа');
        }
        return '';
    }
        
}
