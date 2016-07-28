<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form_Currency
 *
 * @author Viktor
 */
class Form_Metal extends Core_Form {
    
    public function init() {        
        $el = new Core_Form_Element_Select('metal_code', 
                array('label'=>'Метал',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('count', 
                array('label'=>'Кол-во',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('course', 
                array('label'=>'Курс',                    
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('date', 
                array('label'=>'Дата покупки',                    
                    'value'=>date('d.m.Y'),
                    'required'=>true));
        $this->addElement($el);
        
        $options = array(
            'cancel'=>array(
                'returnUrl'=>'/investments/index/list'
                )
            );
        $this->addButtonsAction($options);
    }
    
    public function setMetal(array $metals) {
        $el = $this->getElement('metal_code');
        $el->setMultiOptions($metals);         
        return $this;
    }
    
    public function getValuesForModel() {
        return $this->getValues();
    }
    
    public function setValuesToModel(InvestmentMetal_Model $model) {
        $data = array(
            'count'=>$model->getCount(),
            'metal_code'=>$model->getMetalCode(),
            'course'=>$model->getCourse(),
            'date'=>$model->getDateFormatDMY(),
        );
        return $this->populate($data);
    }
    
}
