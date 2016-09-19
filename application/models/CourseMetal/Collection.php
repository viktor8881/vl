<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cource_Collection
 *
 * @author Viktor
 */
class CourseMetal_Collection extends Core_Domen_CollectionAbstract {
    
    
    public function getValues() {
        $result = array();
        foreach ($this->getIterator() as $course) {
            $result[] = $course->getValue();
        }
        return $result;
    }

    public function listDateCourse() {
        $result = array();
        foreach ($this->getIterator() as $course) {
            $result[$course->getDateFormatDMY()] = $course->getValue();
        }
        return $result;
    }
    
    public function isQuotesGrowth() {
        $first = $this->first();
        $last = $this->last();
        return $last->getValue() > $first->getValue();
    }
    
    public function isQuotesFall() {
        $first = $this->first();
        $last = $this->last();
        return $last->getValue() < $first->getValue();
    }
    
    public function getCourseByCode($code) {
        $result = 0;
        foreach ($this->getIterator() as $course) {
            if ($code == $course->getCode()) {
                $result = $course->getValue();
                break;
            }
        }
        return $result;
    }
    
}
