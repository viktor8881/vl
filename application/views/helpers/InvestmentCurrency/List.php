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
class View_Helper_InvestmentCurrency_List extends Zend_View_Helper_Abstract
{
    
    public function investmentCurrency_List(InvestmentCurrency_Collection $coll) 
    {
        $html = '';
        $html .= 
        '<table class="table table-bordered">
            <tr>
                <th>Операция</th>
                <th>Дата</th>
                <th>Кол-во</th>
                <th>Валюта</th>
                <th>Курс покупки</th>
                <th>Сумма инвестиций (руб.)</th>
                <th>Действия</th>
            </tr>
        <tbody>';
        if ($coll->count()) {
            foreach ($coll as $invest) {
                $html .= '<tr>';
                    $html .= '<td>'.$this->view->investmentCurrency_TypeName($invest->getType()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getDateFormatDMY()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getCount()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getCurrencyName()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getCourse()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getSum()).'</td>';
                    $html .= '<td><a href="/investments/currency/edit/id/'.$invest->getId().'">'.$this->view->iconEdit('edit').'edit</a> '
                            . '<a href="/investments/currency/delete/id/'.$invest->getId().'">'.$this->view->iconDelete('del').'del</a></td>';
                $html .= '</tr>';
            }
        }else{
            $html .= '<tr><td colspan="7"><p class="text-center text-muted">Нет инвестиций</p></td></tr>';
        }
        $html .= 
        '</tbody>
        </table>';
        return $html;
    }
        
}
