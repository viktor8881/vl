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
    
    public function getByMinValue() {
        $result = null;
        $val = null;
        foreach ($this->getIterator() as $course) {
            if (is_null($val) or $course->getValue() < $val) {
                $val = $course->getValue();
                $result = $course;
            }
        }
        return $result;
    }
    
    public function getByMaxValue() {
        $result = null;
        $val = null;
        foreach ($this->getIterator() as $course) {
            if (is_null($val) or $course->getValue() > $val) {
                $val = $course->getValue();
                $result = $course;
            }
        }
        return $result;
    }
    
}
