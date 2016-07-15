<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Investment_FormAdd
 *
 * @author Viktor
 */
class Form_Add extends Core_Form {
    
    public function init() {
        
        $el = new Core_Form_Element_Text('sum', 
                array('label'=>'Сумма в руб.',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('count', 
                array('label'=>'Кол-во',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Select('currency', 
                array('label'=>'Валюта',
                    'required'=>true));
        $this->addElement($el);
        
        $el = new Core_Form_Element_Text('exchange', 
                array('label'=>'Курс',
                    'required'=>true));
        $this->addElement($el);
        
        $options = array(
            'cancel'=>array(
                'returnUrl'=>'/investment/index/list'
                )
            );
        $this->addButtonsAction($options);
    }
    
    public function setCurrency(array $currencies) {
        $el = $this->getElement('currency');
       $el->setMultiOptions($currencies);         
        return $this;
    }
    
}
