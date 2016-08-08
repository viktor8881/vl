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
class View_Helper_Task_DescriptionOptions extends Zend_View_Helper_Abstract
{
    
    public function task_DescriptionOptions(Task_Model_Abstract $model) 
    {
        $xhtml = '';
        if ($model instanceof Task_Model_Percent) {
            $xhtml .= $model->getPercent().'% за '.$this->view->pluralDays($model->getPeriod(), true).'<br />';
            $xhtml .= $this->htmlList('Валюты', $model->getCurrencies());            
            $xhtml .= $this->htmlList('Металы', $model->getMetals());
            
        }elseif ($model instanceof Task_Model_OverTime) {
            $xhtml .= 'в течении '.$this->view->PluralDaysGenitive($model->getPeriod(), true).'<br />';
            $xhtml .= $this->htmlList('Валюты', $model->getCurrencies());            
            $xhtml .= $this->htmlList('Металы', $model->getMetals());
        }
        return $xhtml;
    }
    
    private function htmlList($name, $list) {
        if (!count($list)) {
            return '';
        }
        $xhtml = '<div class="col-sm-6">';
            $xhtml .= '<strong>'.$name.'</strong>';
            $xhtml .= '<ul>';
            foreach ($list as $item) {
                $xhtml .= '<li>'.$this->view->escape($item->getName()).'</li>';
            }
            $xhtml .= '</ul>';
        $xhtml .= '</div>';
        
        return $xhtml;
    }
        
}
