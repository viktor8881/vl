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
class CacheCourseMetal_Collection extends Core_Domen_CollectionAbstract {
    
    
    public function firstIsDownTrend() {
        $item = $this->first();
        if ($item) {
            return $item->isDownTrend();
        }
        return false;
    }
    
    public function firstIsUpTrend() {
        $item = $this->first();
        if ($item) {
            return $item->isUpTrend();
        }
        return false;
    }
    
    public function lastNullOperation() {
        $item = $this->last();
        if ($item) {
            return !$item->hasOperation();
        }
        return false;
    }
    
    public function countFirstData() {
        $item = $this->first();
        if ($item) {
            return $item->countDataValue();
        }
        return 0;
    }
    
    public function listLastValue() {
        $result = [];
        foreach ($this->getIterator() as $course) {
            $result[] = $course->getLastValue();
        }
        return $result;
    }
    
    public function listId() {
        $result = [];
        foreach ($this->getIterator() as $course) {
            $result[] = $course->getId();
        }
        return $result;
    }

    public function getFirstDate() {
        return $this->first()->getFirstDate();
    }
    
    public function getLastDate() {
        return $this->last()->getLastDate();
    }
    
}
