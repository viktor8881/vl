<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisMetal_Filter_MetalCode
 *
 * @author Viktor
 */
class AnalysisMetal_Filter_Type extends Core_Domen_Filter_Abstract {
    
    
    public function filter($value) {
        return (string)$value;
    }
    
}
