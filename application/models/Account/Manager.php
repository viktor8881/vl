<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account_Manager
 *
 * @author Viktor
 */
class Account_Manager extends Core_Domen_Manager_Abstract {
        
    const PRIMARY_ID = 1;
    
    public function getValue() {
        $model = $this->getRepository()->get(self::PRIMARY_ID);
        return $model?$model->getBalance():0;
    }

    public function addPay($value) {
        $model = $this->get(Account_Manager::PRIMARY_ID);
        $model->addBalance($value);
        return $this->update($model);
    }
    
    public function subPay($value) {
        $model = $this->get(Account_Manager::PRIMARY_ID);
        $model->subBalance($value);
        return $this->update($model);
    }
    
}
