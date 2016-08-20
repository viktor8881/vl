<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form_Account
 *
 * @author Viktor
 */
class Form_Account extends Core_Form {
    
    public function init() {        
        $el = new Core_Form_Element_HiddenData('current', 
                array('label'=>'Текущий баланс'));
        $this->addElement($el);
        
        $el = new Core_Form_Element_TextMoney('balance', 
                array('label'=>'Баланс',
                    'autofocus'=>true,
                    'required'=>true));
        $this->addElement($el);
        
        $options = array(
            'cancel'=>array(
                'returnUrl'=>'/default/balance'
                )
            );
        $this->addButtonsAction($options);
    }
    
    public function setCurrentBalance($value) {
        $this->getElement('current')
                ->setValue($value)
                ->setData($value);
        return $this;
    }
    
    public function getValueCurrentBalance() {
        return $this->getElement('current')->getValue();
    }

    public function setLabelBalance($label) {
        $this->getElement('balance')->setLabel($label);
        return $this;
    }
    
    public function getValueBalance() {
        return $this->getElement('balance')->getValue();
    }
    
}
