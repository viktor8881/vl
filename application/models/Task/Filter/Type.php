<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task_Filter_Type
 *
 * @author Viktor
 */
class Task_Filter_Type extends Core_Domen_Filter_Abstract {
    
    
    public function filter($value) {
        return (int)$value;
    }
    
}
