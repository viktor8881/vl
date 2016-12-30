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
class View_Helper_AnalysisCurrency_Percent_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisCurrency_Percent_Name(AnalysisCurrency_Model_Percent $model)  {
        if ($model->isQuotesGrowth()) {
            $name = _('Повышение курса на');
        }else {
            $name = _('Понижение курса на');
        }
        return $name.' '.$this->view->formatPercent($model->getDiffPercent(), true).' '._('за').' '.$this->view->pluralDays($model->getPeriod());
    }
        
}
