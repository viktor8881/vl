<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_DataChart
 *
 * @author Viktor
 */
class Model_DataChart {
    
    private $date;
    private $value;
    private $valueFigure;
    
    
    /**
     * @return Core_Date
     */
    public function getDate() {
        return $this->date;
    }
    
    public function getDateFormatGDate() {
        return $this->getDate()->formatGDate();
    }
    
    public function getDateFormatDMY() {
        return $this->getDate()->formatDMY();
    }

    public function getValue() {
        return $this->value;
    }

    public function getValueFigure() {
        return $this->valueFigure;
    }

    public function setDate(Core_Date $date) {
        $this->date = $date;
        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function setValueFigure($value) {
        $this->valueFigure = $value;
        return $this;
    }


    
}
