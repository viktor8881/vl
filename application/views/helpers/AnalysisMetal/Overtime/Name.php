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
class View_Helper_AnalysisMetal_Overtime_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisMetal_Overtime_Name(AnalysisMetal_Model_Overtime $model)  {
        if ($model->isQuotesGrowth()) {
//            $name = _('Повышение курса в течении').' '.$this->view->pluralDaysGenitive($model->countData(), true);
//            $name = '▲ в течении';
            $name = _('Повышение курса в течении');
            $cssColor = 'color: rgb(5, 132, 11);';
        }else {
//            $name = _('Понижение курса в течении').' '.$this->view->pluralDaysGenitive($model->countData(), true);
//            $name = '▼ в течении';
            $name = _('Понижение курса в течении');
            $cssColor = 'color: rgb(191, 0, 0);';
        }
        return '<span  style="'.$cssColor.'">'.$name.' '.$this->view->pluralDaysGenitive($model->countData(), true).'</span>';
    }
        
}
