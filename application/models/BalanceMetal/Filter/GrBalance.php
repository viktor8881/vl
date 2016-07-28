<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BalanceMetal_Filter_GrBalance
 *
 * @author Viktor
 */
class BalanceMetal_Filter_GrBalance extends Core_Domen_Filter_Abstract {
        
    public function filter($value) {
        return (float)$value;
    }
    
}
