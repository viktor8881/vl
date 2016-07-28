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
class Zend_View_Helper_CardMetalIconBalance extends Zend_View_Helper_Abstract
{
    
    public function cardMetalIconBalance(Model_CardMetalBalance $balance) 
    {
        $xhtml = $balance->isInvestUp()?$this->view->iconUp():$this->view->iconDown();
        $xhtml .= ' '.$this->view->formatPercent($balance->diffSumInvestToPercent(), true);
        return $xhtml;
    }
        
}
