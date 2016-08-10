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
class AnalysisMetal_Collection extends Core_Domen_CollectionAbstract {
    
    public function listCodeMetals() {
        $listCode = array();
        foreach ($this->getIterator() as $analysis) {
            $listCode[] = $analysis->getMetalCode();
        }
        return array_unique($listCode);
    }

    public function getMetals() {
        $listCode = $this->listCodeMetals();
        if (count($listCode)) {
            return Core_Container::getManager('metal')->fetchAllByCodes($listCode);
        }
        return Core_Container::getManager('metal')->createCollection();
    }        
    
    public function listPercentByMetalCode($code) {
        $list = array();
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isPercent() && $analysis->getMetalCode() == $code) {
                $list[] = $analysis;
            }
        }
        return $list;
    }
    
    public function getOvertimeByMetalCode($code) {
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isOvertime() && $analysis->getMetalCode() == $code) {
                return $analysis;
            }
        }
        return null;
    }
    
}
