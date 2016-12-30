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
class View_Helper_AnalysisMetal_Figure_Period extends Zend_View_Helper_Abstract
{
    
    public function analysisMetal_Figure_Period(AnalysisMetal_Model_Figure $figure)  {
        $dateStart = $figure->getDateFirst();
        $dateEnd = $figure->getDateLast();
        return $dateStart->formatDMY().' - '.$dateEnd->formatDMY().' ('.$this->view->pluralDays($dateStart->diffDays($dateEnd)).')';
    }
        
}
