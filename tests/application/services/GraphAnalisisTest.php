<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_GraphAnalisis
 *
 * @author Viktor
 */
use PHPUnit\Framework\TestCase;
require_once APPLICATION_PATH.'/services/GraphAnalisis.php';

class Service_GraphAnalisisTest extends TestCase {
    
    /**
     * @dataProvider additionIsUpTrendFalse
     */
    public function testIsUpTrendFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isUpTrend($courses);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsUpTrendFalse() {
        return [
            [[100,  94, 120, 130, 140, 750], 5],
            [[100, 110,  90, 130, 140, 750], 5],
            [[100, 110, 120, 113, 140, 750], 5],
        ];
    }
    
    /**
     * @dataProvider additionIsUpTrendTrue
     */
    public function testIsUpTrendTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isUpTrend($courses);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsUpTrendTrue() {
        return [
            [[100, 110, 120, 130, 140, 150], 5],
            [[100, 110, 120, 130, 140, 750], 5],
            [[100,  95, 120, 130, 140, 750], 5],
            [[100,  105, 100, 103, 100, 95], 5],
        ];
    }
    // =========================================================================
    
    
    /**
     * @dataProvider additionIsDownTrendFalse
     */
    public function testIsDownTrendFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isDownTrend($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsDownTrendFalse() {
        return [
            [[100, 106, 90,  85, 80, 75], 5],
            [[100, 95, 99.8, 85, 80, 75], 5],
            [[100, 95,  99,  85, 80, 85], 5],
        ];
    }
    
    /**
     * @dataProvider additionIsDownTrendTrue
     */
    public function testIsDownTrendTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isDownTrend($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsDownTrendTrue() {
        return [
            [[100, 95, 90, 85, 80, 75], 5],
            [[100, 95, 90, 85, 80, 15], 5],
        ];
    }
    
    // =========================================================================
    
    
    /**
     * @dataProvider additionIsEqualTrendFalse
     */
    public function testIsEqualTrendFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isEqualTrend($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsEqualTrendFalse() {
        return [
            [[100, 106, 104,  100, 95, 105], 5],
            [[100, 105, 104,  100, 94, 105], 5],
        ];
    }
    
    /**
     * @dataProvider additionIsEqualTrendTrue
     */
    public function testIsEqualTrendTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isEqualTrend($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsEqualTrendTrue() {
        return [
            [[100, 103, 97, 103, 100, 97]  , 3],
            [[100, 95, 99, 95, 97, 105], 5],
        ];
    }
    
    //==========================================================================
        
    /**
     * @dataProvider additionIsDoubleTopFalse
     */
    public function testIsDoubleTopFalse($courses, $percent, $sureTrend) {
        $actual = Service_GraphAnalisis::isDoubleTop($courses, $percent, $sureTrend);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsDoubleTopFalse() {
        return [
            // not up trend. mode=1. find stable up trend. /
            [[100, 94, 105, 110, 115, 120], 5, 4],
            [[100, 105, 96, 110, 115, 120], 5, 4],
            [[100, 105, 110, 107, 95, 120], 5, 5],
            // low line neck. mode=2. find down trend. /\
            [[100, 105, 110, 107, 120,99, 94, 105],5, 4],
            [[100, 105, 110, 107, 120,94, 105],5, 4],
            // hight line Up support line. mode=3. find up trend. /\/
            [[100, 105, 110, 107, 120,  96,  127],5, 4],
            [[100, 105, 110, 107, 120,  99, 97, 99, 96,  105, 110, 115, 120, 130, 140],5, 4],
            [[100, 105, 110, 107, 120,  99, 97, 99, 96,  105, 110, 115, 120, 126, 140],5, 4],
            // hight line Up support line. mode=4. find down trend. /\/\
            [[100, 105, 110, 120,  96,  122, 127],5, 3],
            [[100, 105, 110, 120,  96,  122, 119, 121, 97],5, 3],
            [[100, 105, 110, 120,  96,  122, 119, 121, 97, 96],5, 3],
            [[100, 103, 103, 105, 102, 115, 103, 97, 96, 98, 95, 107,  105, 117, 100, 95] , 5, 3],
        ];
    }
    
    /**
     * @dataProvider additionIsDoubleTopTrue
     */
    public function testIsDoubleTopTrue($courses, $percent, $sureTrend) {
        $actual = Service_GraphAnalisis::isDoubleTop($courses, $percent, $sureTrend);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsDoubleTopTrue() {
        return [
            [[100, 103, 103, 105, 102, 115, 103, 102, 103, 102, 102, 107,  105, 117, 105, 101] , 5, 3],
            [[100, 103, 103, 105, 102, 115, 103, 97, 96, 98, 95, 107,  105, 117, 100, 94, 92] , 5, 3],
            [[100, 105, 110, 115, 120, 125, 130, 150, 130, 120, 125, 135, 140, 155, 125, 119] , 5, 3],
            [[100, 105, 110, 115, 120, 125, 130, 150, 130, 155, 125, 119] , 5, 3],
        ];
    }
    
}
