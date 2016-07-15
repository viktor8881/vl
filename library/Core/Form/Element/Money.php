<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Money
 *
 * @author Victor
 */
class Core_Form_Element_Money extends Core_Form_Element_TextAppend {
    
    
    
    public function __construct($spec, $options = null) {
        if (empty($options['filters'])) {
            $options['filters'] = array(new Core_Filter_MoneyValue());
        }
        if (empty($options['validators'])) {
            $options['validators'] = array(new Core_Validate_MoneyValue());
        }
        if (empty($options['text'])) {
            $options['text'] = Core_Container::getService('setting')->getMoneyUnit();
        }
        parent::__construct($spec, $options);
    }
    
    
    public function render(\Zend_View_Interface $view = null) {
        // добавим фильтр ввода
        if (!$view) {
            $view = $this->getView();
        }
        $view->headScript()->appendFile($view->baseUrl().'/js/jquery.numberMask.min.js');
        
        $id = $this->getId();
        $view->headScript()->captureStart();
            echo "$('#".$id."').numberMask({decimalMark:['.',','],type:'float', afterPoint:'".Core_Container::getService('setting')->fractMoney()."'});";
        $view->headScript()->captureEnd();
        return parent::render($view);
    }
    
}
