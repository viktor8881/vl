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

class Core_Form_Helper_DateTimePeriod extends Core_Form_Helper_DateTime
{
    

    public function dateTimePeriod($name, $value = '', $attribs = null, $options = null)
    {
        $optStart = isset($options[Core_Form_Element_DateTimePeriod::POSTFIX_START])?$options[Core_Form_Element_DateTimePeriod::POSTFIX_START]:array();
        $optEnd = isset($options[Core_Form_Element_DateTimePeriod::POSTFIX_END])?$options[Core_Form_Element_DateTimePeriod::POSTFIX_END]:array();
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        
        $xhtml = '';
        $fieldset = new Core_Form_SubForm();        
        $optStart['decorators'] = array('ViewHelper');
        $optStart['value'] = isset($value[Core_Form_Element_DateTimePeriod::POSTFIX_START])?$value[Core_Form_Element_DateTimePeriod::POSTFIX_START]:'';
        $start = new Core_Form_Element_DateTime(Core_Form_Element_DateTimePeriod::POSTFIX_START, $optStart);
        
        $optEnd['decorators'] = array('ViewHelper');
        $optEnd['value'] = isset($value[Core_Form_Element_DateTimePeriod::POSTFIX_END])?$value[Core_Form_Element_DateTimePeriod::POSTFIX_END]:'';
        $end = new Core_Form_Element_DateTime(Core_Form_Element_DateTimePeriod::POSTFIX_END, $optEnd);
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
        });
        

        function changeDates()
        {
            var elValue = $("#'.$startId.'").data("DateTimePicker").getDate().format("'.$start->getFormatJS().'");
            var nameEl = "datestart";
            var pattern = new RegExp("\/"+nameEl+"\/([0-9\%\+A\.:]+)?", "i")
            var currentUrl = baseUrl.replace(pattern, "");
            currentUrl = currentUrl.replace(new RegExp("\/$"), "")+"/"+nameEl+"/"+elValue;

            console.log($("#period-start").data("DateTimePicker").getDate()._i);


            var elValue = $("#'.$endId.'").data("DateTimePicker").getDate().format("'.$end->getFormatJS().'");
            nameEl =  "dateend";
            pattern = new RegExp("\/"+nameEl+"\/([0-9\%\+A\.:]+)?", "i")
            currentUrl = currentUrl.replace(pattern, "");
            currentUrl = currentUrl.replace(new RegExp("\/$"), "")+"/"+nameEl+"/"+elValue;
            document.location.href = currentUrl;

        }


        ';
        $this->view->headScript()->captureEnd();
        return $xhtml;
    }
    
    
    
    
}
