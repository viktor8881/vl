<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheCourseMetal_Filter_Code
 *
 * @author Viktor
 */
class CacheCourseMetal_Filter_Code extends Core_Domen_Filter_Abstract {
    
    
    public function filter($value) {
        return (int)$value;
    }
    
}
