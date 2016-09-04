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
class View_Helper_InvestmentMetal_List extends Zend_View_Helper_Abstract
{
    
    public function investmentMetal_List(InvestmentMetal_Collection $coll) 
    {
        $html = '';
        $html .= 
        '<table class="table table-bordered">
        <tr>
            <th>'._('Операция').'</th>
            <th>'._('Дата').'</th>
            <th>'._('Кол-во').'</th>
            <th>'._('Метал').'</th>
            <th>'._('Курс покупки').'</th>
            <th>'._('Сумма инвестиций (руб.)').'</th>
            <th>'._('Действия').'</th>
        </tr>
        <tbody>';
        if ($coll->count()) {
            foreach ($coll as $invest) {
                $html .= '<tr>';
                    $html .= '<td>'.$this->view->investmentMetal_TypeName($invest->getType()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getDateFormatDMY()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getCount()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getMetalName()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getCourse()).'</td>';
                    $html .= '<td>'.$this->view->escape($invest->getSum()).'</td>';
                    $html .= '<td><a href="/investments/metal/edit/id/'.$invest->getId().'">'.$this->view->iconEdit('edit').'</a> '
                            . '<a href="/investments/metal/delete/id/'.$invest->getId().'">'.$this->view->iconDelete('del').'</a></td>';
                $html .= '</tr>';
            }
        }else{
            $html .= '<tr><td colspan="7"><p class="text-center text-muted">'._('Нет инвестиций').'</p></td></tr>';
        }
        $html .= 
        '</tbody>
        </table>';
        return $html;
    }
        
}
