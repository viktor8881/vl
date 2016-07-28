<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form_Percent
 *
 * @author Viktor
 */
class Form_Percent extends Core_Form {
    
    public function init() {
        $el = new Core_Form_Element_Text('percent', 
                array('label'=>'Процент',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('period', 
                array('label'=>'Период',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_MultiCheckbox('currencies', 
                array('label'=>'Валюты',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_MultiCheckbox('metals', 
                array('label'=>'Метал',
                    'required'=>true));
        $this->addElement($el);
                
        $options = array(
            'cancel'=>array(
                'returnUrl'=>'/tasks/index/list'
                )
            );
        $this->addButtonsAction($options);
    }
    
    public function setMetal(array $metals) {
        $el = $this->getElement('metals')
                ->setMultiOptions($metals);
        return $this;
    }

        public function setCurrency(array $currencies) {
        $el = $this->getElement('investments')
                ->setMultiOptions($currencies);
        return $this;
    }
    
    public function getValuesForModel() {
        return $this->getValues();
    }
    
    public function setValuesToModel(InvestmentCurrency_Model $model) {
        $data = array(
            'count'=>$model->getCount(),
            'currency_code'=>$model->getCurrencyCode(),
            'course'=>$model->getCourse(),
            'date'=>$model->getDateFormatDMY(),
        );
        return $this->populate($data);
    }
    
}
