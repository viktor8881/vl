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
class Course_View_Helper_HeaderCurrency extends Zend_View_Helper_Abstract
{
    
    public function headerCurrency(Currency_Collection $currencies, Currency_Model $currentCurrency, array $period) {
        $xhtml = '<h3>';
            $xhtml .= _('Курс валюты');
            $xhtml .= '<form method="get" action="" class="form-inline" style="display:inline">';
            $xhtml .= ' <div class="form-group"><label for="metalName" class="sr-only">Метал</label>'
                    . '<select name="metalName" id="metalName" class="form-control">';
            foreach($currencies as $currency) {
                if ($currency->getId() == $currentCurrency->getId()) {
                    $selected = 'selected="selected"';
                }else{
                    $selected ='';
                }
                $xhtml .= '<option value="'.$currency->getId().'" '.$selected.'>'.$this->view->escape($currency->getName()).'</option>';
            }
            $xhtml .= '</select></div>';
            $xhtml .= ' <div class="form-group">'
                        . '<label for="daterange" class="sr-only">Период</label>'
                        . '<input type="text" class="form-control" name="daterange" id="daterange" value="">'
                    . '</div>';
            $xhtml .= ' <button type="button" class="btn btn-success" id="btn-filter">Применить</button>';
            $xhtml .= '</form>';
        $xhtml .= '</h3>';
        
        $this->view->headScript()->prependFile('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js');
        $this->view->headScript()->prependFile('//cdn.jsdelivr.net/momentjs/latest/moment.min.js');
        $this->view->headLink()->appendStylesheet('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css');
        $this->view->headScript()->captureStart();
        echo 
        "$(function(){"
            . "$('input[name=\"daterange\"]').daterangepicker(
            {
                
                locale: {
                    language: 'ru',
                  format: 'DD.MM.YYYY'
                },
                startDate: '".$period['start']."',
                endDate: '".$period['end']."'
            });
            
            $('#btn-filter').click(function() {
                var splitdr = $('#daterange').val().split(' - ');
                var url = '/course/currency/index/id/'+$('#metalName').val()+'/start/'+splitdr[0]+'/end/'+splitdr[1];
                window.location = url;
            });
        });";
        $this->view->headScript()->captureEnd();
        
        return $xhtml;
    }
        
}
