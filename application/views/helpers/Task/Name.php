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
class View_Helper_Task_Name extends Zend_View_Helper_Abstract
{
    
    public function task_Name(Task_Model_Abstract $model) 
    {
        $xhtml = '<div class="row">';
        if ($model->isModeOnlyUp()) {
            $modeName = _('Рост');
        }elseif($model->isModeOnlyDown()) {
            $modeName = _('Понижение');
        }else{
            $modeName = _('Pост/понижение');
        }
        if ($model->isPercent()) {
            $xhtml .= '<div class="col-sm-12">'.sprintf(_('%1$s на %2$s за %3$s'), $modeName, $this->view->formatPercent($model->getPercent(), true), $this->view->pluralDays($model->getPeriod(), true)).'</div>';
        }elseif ($model->isOvertime()) {
            $xhtml .= '<div class="col-sm-12">'.sprintf(_('%1$s в течении %2$s'), $modeName, $this->view->pluralDaysGenitive($model->getPeriod(), true)).'</div>';
        }
        if ($model->countMetals()) {
            $xhtml .= '<div class="col-sm-3 text-success" style="padding-top:16px; padding-bottom:16px;">'
                    . '<strong>'._('Металы').' </strong>'
                    . '<a href="#" data-toggle="tooltip" label label-warning title="'.implode("<br /> ", $model->listNameMetals()).'">'
                        . '<span class="label label-success">'.$model->countMetals().'</span>'
                    .'</a>'
                    . '</div>';
        }
        if ($model->countCurrencies()) {
            $xhtml .= '<div class="col-sm-3 text-warning" style="padding-top:16px; padding-bottom:16px;">'
                    . '<strong>'._('Валюты').'</strong> '
                    . '<a href="#" data-toggle="tooltip" label label-warning title="'.implode("<br /> ", $model->listNameCurrencies()).'">'
                        . '<span class="label label-warning">'.$model->countCurrencies().'</span>'
                    .'</a>'
                    . '</div>';
        }
        $xhtml .= '</div>';
        return $xhtml;
    }
        
}
