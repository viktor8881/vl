<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form_OverTime
 *
 * @author Viktor
 */
class Form_OverTime extends Core_Form {
    
    public function init() {
        $el = new Core_Form_Element_Select('mode', 
                array('label'=>'Режим проверки',
                    'multiOptions'=>array(
                        Task_Model_Abstract::MODE_ONLY_UP   => 'Только рост инвестиций',
                        Task_Model_Abstract::MODE_ONLY_DOWN => 'Только понижение инвестиций',
                        Task_Model_Abstract::MODE_UP_DOWN   => 'Рост и понижение инвестиций'),
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('period', 
                array('label'=>'В течении дней',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_MultiCheckbox('currenciesCode', 
                array('label'=>'Валюты',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_MultiCheckbox('metalsCode', 
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
        $el = $this->getElement('metalsCode')
                ->setMultiOptions($metals);
        return $this;
    }

    public function setCurrency(array $currencies) {
        $el = $this->getElement('currenciesCode')
                ->setMultiOptions($currencies);
        return $this;
    }
    
    public function getValuesForModel() {
        $data = $this->getValues();
        $data['type'] = Task_Model_Abstract::TYPE_OVER_TIME;
        return $data;
    }
    
    public function setValuesToModel(Task_Model_OverTime $model) {
        $data = array(
            'mode'=>$model->getMode(),
            'period'=>$model->getPeriod(),
            'currenciesCode'=>$model->getCurrenciesCode(),
            'metalsCode'=>$model->getMetalsCode()
        );
        return $this->populate($data);
    }
    
}
