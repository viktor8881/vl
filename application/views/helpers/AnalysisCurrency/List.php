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
class View_Helper_AnalysisCurrency_List extends Zend_View_Helper_Abstract
{
    
    public function analysisCurrency_List(array $coll) 
    {
        $html = '';
        $html .= 
        '<table class="table table-bordered">
            <tr>
                <th class="col-md-2">'._('Дата').'</th>
                <th class="col-md-2">'._('Валюта').'</th>
                <th class="col-md-8">'._('Наименование').'</th>
            </tr>
        <tbody>';
        if (count($coll)) {
            foreach ($coll as $items) {
                $listNames = [];
                foreach ($items as $item) {
                    $listNames[] = $this->view->analysisCurrency_Name($item);
                }
                $currency = $items->first()->getCurrency();
                $date = $items->first()->getCreated();
                $html .= '<tr>';
                    $html .= '<td>'.$this->view->escape($date->formatDMY()).'</td>';
                    $html .= '<td>'.$this->view->escape($currency->getName()).'</td>';
                    $html .= '<td><a href="/analysis/currency/index/id/'.$currency->getId().'/date/'.$date->formatDMY().'">'.implode('<br/>', $listNames).'</a></td>';
                $html .= '</tr>';
            }
        }else{
            $html .= '<tr><td colspan="3"><p class="text-center text-muted">'._('Аналитика не найдена').'</p></td></tr>';
        }
        $html .= 
        '</tbody>
        </table>';
        return $html;
    }
        
}
