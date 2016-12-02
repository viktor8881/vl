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
class View_Helper_AnalysisMetal_Panel extends Zend_View_Helper_Abstract
{
    
    public function analysisMetal_Panel(AnalysisMetal_Model_Abstract $model)  {
        $name = '<strong>'.$this->view->analysisMetal_Name($model).'</strong>';
        $class = 'panel-default';
        switch ($model->getType()) {
            case AnalysisMetal_Model_Abstract::TYPE_PERCENT:
            case AnalysisMetal_Model_Abstract::TYPE_OVER_TIME:
                if ($model->isQuotesGrowth()) {
                    $class = 'panel-info';
                    $classBg = 'bg-info';
                }else{
                    $class = 'panel-danger';
                    $classBg = 'bg-danger';
                }
                break;
            case AnalysisMetal_Model_Abstract::TYPE_FIGURE:
                $name .= '<span class="pull-right"><a href="">show chart</a></span>';
                break;
        }
        return '<div class="panel '.$class.'">
                  <div class="panel-body '.$classBg.'">
                      '.$name.'
                  </div>
                </div>';
    }
        
}
