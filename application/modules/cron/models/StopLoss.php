<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_wer
 *
 * @author Viktor Ivanov
 */
class StopLoss {
    
    const UP_PERCENT = 10;
    
    private $pathFileName;
    
    
    
    public function __construct($pathFileName) {
        $this->pathFileName = $pathFileName;
    }
    
    public function create($item) {
        file_put_contents($this->pathFileName, $item);
        return $this;
    }
    
    public function delete() {
        if (file_exists($this->pathFileName)) {
            unlink($this->pathFileName);
        }
        return $this;
    }
        
    public function up($item) {
        if (file_exists($this->pathFileName)) {
            $value = file_get_contents($this->pathFileName);
            $step = (abs($value - $item))*self::UP_PERCENT/100;
            $this->create($value + $step);
        }
        return $this;
    }
    
    public function isOver($item) {
        if (file_exists($this->pathFileName)) {
            $value = file_get_contents($this->pathFileName);
            if (Core_Math::compare($value,  $item, 4) == 1) {
                return true;
            }
        }
        return false;
    }
    
    public function has() {
        return file_exists($this->pathFileName);
    }
    
}
