<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheCourseCurrency_Filter_Percent
 *
 * @author Viktor
 */
class CacheCourseCurrency_Filter_Percent extends Core_Domen_Filter_Abstract {
    
    
    public function filter($value) {
        return (float)$value;
    }
    
}
