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
class View_Helper_AnalysisCurrency_Overtime_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisCurrency_Overtime_Name(AnalysisCurrency_Model_Overtime $model)  {
        if ($model->isQuotesGrowth()) {
            $name = _('Повышение курса в течении').' '.$this->view->pluralDaysGenitive($model->countData(), true);
        }else {
            $name = _('Понижение курса в течении').' '.$this->view->pluralDaysGenitive($model->countData(), true);
        }
        return $name;
    }
        
}
