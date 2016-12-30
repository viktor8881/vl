<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FigureMetal_Collection
 *
 * @author Viktor
 */
class FigureMetal_Collection extends Core_Domen_CollectionAbstract {
    
    
    public function getByInvestId($id) {
        foreach ($this->getIterator() as $figure) {
            if ($figure->getInvestmentId() == $id) {
                return $figure;
            }
        }
        return null;
    }
    
    public function getFigureByInvestId($id) {
        $figure = $this->getByInvestId($id);
        if ($figure) {
            return $figure->getFigure();            
        }
        return null;
    }
    
    public function getPercentByInvestId($id) {
        $figure = $this->getByInvestId($id);
        if ($figure) {
            return $figure->getPercentCacheCourses();            
        }
        return null;
    }
    
}
