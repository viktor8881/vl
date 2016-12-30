<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseMetal_Filter_LsEqDate
 *
 * @author Viktor
 */
class CourseMetal_Filter_LsEqDate extends Core_Domen_Filter_Abstract {
        
    
    public function filter($value) {
        if ( $value instanceof Core_Date ) {
            return $value->formatDbDate();
        }
        return false;
    }
    
}
