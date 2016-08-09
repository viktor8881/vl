<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseCurrency_Collection
 *
 * @author Viktor
 */
class CourseCurrency_Collection extends Core_Domen_CollectionAbstract {
    
    
    public function listDateCourse() {
        $result = array();
        foreach ($this->getIterator() as $course) {
            $result[$course->getDateFormatDMY()] = $course->getValue();
        }
        return $result;
    }
    
    
}
