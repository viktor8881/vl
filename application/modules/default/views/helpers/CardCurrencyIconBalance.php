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
class Zend_View_Helper_CardCurrencyIconBalance extends Zend_View_Helper_Abstract
{
    
    public function cardCurrencyIconBalance(Model_CardCurrencyBalance $balance) 
    {
        $xhtml = $balance->isInvestUp()?$this->view->iconUp():$this->view->iconDown();
        $xhtml .= ' '.$this->view->formatPercent($balance->diffSumInvestToPercent(), true);
        return $xhtml;
    }
        
}
