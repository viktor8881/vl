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
class View_Helper_AnalysisCurrency_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisCurrency_Name(AnalysisCurrency_Model_Abstract $model)  {
        $name = '';
        if ($model instanceof AnalysisCurrency_Model_Percent) {
            $name = $this->view->analysisCurrency_Percent_Name($model);
        }elseif($model instanceof AnalysisCurrency_Model_OverTime) {
            $name = $this->view->analysisCurrency_Overtime_Name($model);
        }elseif($model instanceof AnalysisCurrency_Model_Figure) {
            $name = $this->view->analysisCurrency_Figure_Name($model->getFigure()).' '.
                    $this->view->analysisCurrency_Figure_Period($model).' '.
                    $this->view->formatPercent($model->getPercentCacheCourses(), true);
        }
        return $name;
    }
        
}
