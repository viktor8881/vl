<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Helper_PriceFuel
 *
 * @author Viktor Ivanov
 */

require_once 'Core/Form/helpers/DateTime.php';

class Core_Form_Helper_DatePeriod extends Core_Form_Helper_DateTime
{
    

    public function datePeriod($name, $value = '', $attribs = null, $options = null)
    {
        $optStart = isset($options[Core_Form_Element_DatePeriod::POSTFIX_START])?$options[Core_Form_Element_DatePeriod::POSTFIX_START]:array();
        $optEnd = isset($options[Core_Form_Element_DatePeriod::POSTFIX_END])?$options[Core_Form_Element_DatePeriod::POSTFIX_END]:array();
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        
        $xhtml = '';
        $fieldset = new Core_Form_SubForm();        
        $optStart['decorators'] = array('ViewHelper');
        $optStart['value'] = isset($value[Core_Form_Element_DatePeriod::POSTFIX_START])?$value[Core_Form_Element_DatePeriod::POSTFIX_START]:'';
        $start = new Core_Form_Element_Date(Core_Form_Element_DatePeriod::POSTFIX_START, $optStart);
        
        $optEnd['decorators'] = array('ViewHelper');
        $optEnd['value'] = isset($value[Core_Form_Element_DatePeriod::POSTFIX_END])?$value[Core_Form_Element_DatePeriod::POSTFIX_END]:'';
        $end = new Core_Form_Element_Date(Core_Form_Element_DatePeriod::POSTFIX_END, $optEnd);
        $fieldset->addElement($start)
                ->addElement($end)
                ->setName($name);
        
        $xhtml .= 
                '<div class="input-group date-period">
                        '.$fieldset->render().'
                    </div>';
        
        $startId = $start->getId();
        $endId = $end->getId();
        $this->view->headScript()->captureStart();
        echo  
        '$(function () {
            $("#'.$startId.'").on("dp.change",function (e) {
               $("#'.$endId.'").data("DateTimePicker").setMinDate(e.date);
            });
            $("#'.$startId.'").on("dp.show",function (e) {
               $("#'.$endId.'").data("DateTimePicker").hide();
            });
            
            $("#'.$endId.'").on("dp.change",function (e) {
               $("#'.$startId.'").data("DateTimePicker").setMaxDate(e.date);
            });
            $("#'.$endId.'").on("dp.show",function (e) {
               $("#'.$startId.'").data("DateTimePicker").hide();
            });
                
            $("#'.$startId.'").data("DateTimePicker").setMaxDate( $("#'.$endId.'").data("DateTimePicker").getDate() );
            $("#'.$endId.'").data("DateTimePicker").setMinDate( $("#'.$startId.'").data("DateTimePicker").getDate() );
        });';
        $this->view->headScript()->captureEnd();
        return $xhtml;
    }
    
    
    
    
}
