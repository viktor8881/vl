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
class View_Helper_AnalysisMetal_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisMetal_Name(AnalysisMetal_Model_Abstract $model)  {
        $name = '';
        if ($model instanceof AnalysisMetal_Model_Percent) {
            $name = $this->view->analysisMetal_Percent_Name($model);
        }elseif($model instanceof AnalysisMetal_Model_OverTime) {
            $name = $this->view->analysisMetal_Overtime_Name($model);
        }elseif($model instanceof AnalysisMetal_Model_Figure) {
            $name = $this->view->analysisMetal_Figure_Name($model->getFigure()).' '.
                    $this->view->analysisMetal_Figure_Period($model).' '.
                    $this->view->formatPercent($model->getPercentCacheCourses(), true);
        }
        return $name;
    }
        
}
