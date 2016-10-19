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
class AnalysisCurrency_Collection extends Core_Domen_CollectionAbstract {
    
    
    public function listCodeCurrencies() {
        $listCode = array();
        foreach ($this->getIterator() as $analysis) {
            $listCode[] = $analysis->getCurrencyCode();
        }
        return array_unique($listCode);
    }

    public function getCurrencies() {
        $listCode = $this->listCodeCurrencies();
        if (count($listCode)) {
            return Core_Container::getManager('currency')->fetchAllByCodes($listCode);
        }
        return Core_Container::getManager('currency')->createCollection();
    }
    
    public function listPercentByCurrencyCode($currencyCode) {
        $list = array();
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isPercent() && $analysis->getCurrencyCode() == $currencyCode) {
                $list[] = $analysis;
            }
        }
        return $list;
    }
        
    public function listFigureByCurrencyCode($currencyCode) {
        $list = array();
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isFigure() && $analysis->getCurrencyCode() == $currencyCode) {
                $list[] = $analysis;
            }
        }
        return $list;
    }
    
    public function getOvertimeByCurrencyCode($currencyCode) {
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isOvertime() && $analysis->getCurrencyCode() == $currencyCode) {
                return $analysis;
            }
        }
        return null;
    }
    
}
