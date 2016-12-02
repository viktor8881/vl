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
class View_Helper_AnalysisMetal_Percent_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisMetal_Percent_Name(AnalysisMetal_Model_Percent $model)  {
        if ($model->isQuotesGrowth()) {
//            $name = _('Повышение курса на');
            $name = '▲ ';
            $cssColor = 'color: rgb(5, 132, 11);';
        }else {
//            $name = _('Понижение курса на');
            $name = '▼ ';
            $cssColor = 'color: rgb(191, 0, 0);';
        }
        return '<span style="'.$cssColor.'">'.$name.' '._('за').' '.$this->view->pluralDays($model->getPeriod()).' '.$this->view->formatPercent($model->getDiffPercent(), true).'</span> ';
    }
        
}
