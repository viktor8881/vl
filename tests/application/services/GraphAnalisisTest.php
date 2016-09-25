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
            [[100,  105, 110.25, 115.76, 121.55, 127.62], 5],
            [[100,  105, 175.00, 115.75, 121.55, 127.63], 5],
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
            [[100,  105, 110.25, 115.76, 121.55, 127.63], 5], // low border
            [[100,  125, 110.25, 175, 121.55, 127.63], 5], // with peak 175
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
            [[100, 95.1, 90.25, 85.74, 81.45], 5],
            [[100, 95.0, 70.25, 85.75, 81.45], 5],
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
            [[100, 95, 90.25, 85.74, 81.45], 5],
            [[100, 95, 70, 85.74, 81.45], 5],
        ];
    }
    
    // =========================================================================
    
    /**
     * @dataProvider additionIsEqualChannelFalse
     */
    public function testIsEqualChannelFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isEqualChannel($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsEqualChannelFalse() {
        return [
            [[100, 106, 104,  100, 95, 105], 5],
            [[100, 105, 104,  100, 94, 105], 5],
        ];
    }
    
    /**
     * @dataProvider additionIsEqualChannelTrue
     */
    public function testIsEqualChannelTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isEqualChannel($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsEqualChannelTrue() {
        return [
            [[100, 103, 97, 103, 100, 97]  , 3],
            [[100, 95, 99, 95, 97, 105], 5],
        ];
    }
    
    //==========================================================================
    
    /**
     * @dataProvider additionIsUpChannelTrue
     */
    public function testIsUpChannelTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isUpChannel($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsUpChannelTrue() {
        return [
            [[100, 106.09, 109.27, 112.55, 115.93], 3], // по верхней границе
            [[100, 102.91, 105.99, 109.17, 112.45], 3], // по нижней границе
            [[100, 105.00, 109.25, 112.10, 113.00], 3], // в разброс
        ];
    }
    
    /**
     * @dataProvider additionIsUpChannelFalse
     */
    public function testIsUpChannelFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isUpChannel($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsUpChannelFalse() {
        return [
            // превышение верхней границы
            [[100, 106.10], 3], 
            [[100, 106.09, 109.28], 3],
            // превышение нижней границы
            [[100, 99.83], 4], 
            [[100, 99.84, 103.82],4],
        ];
    }
    
    // =========================================================================
    
    
    /**
     * @dataProvider additionIsDownChannelTrue
     */
    public function testIsDownChannelTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isDownChannel($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsDownChannelTrue() {
        return [
            [[100, 99.91, 96.91, 94.01, 91.19], 3], // по верхней границе
            [[100, 94.09, 91.27, 88.53, 85.87], 3], // по нижней границе
            [[100, 95.00, 92.25, 92.10, 87.00], 3], // в разброс
        ];
    }    
    
    /**
     * @dataProvider additionIsDownChannelFalse
     */
    public function testIsDownChannelFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isDownChannel($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsDownChannelFalse() {
        return [
            // превышение верхней границы
            [[100, 99.92], 3], 
            [[100, 99.91, 96.92], 3],
//            // превышение нижней границы
            [[100, 92.15], 4], 
            [[100, 92.16, 88.46],4],
        ];
    }
    
    //===========================================================================
    /**
     * @dataProvider additionIsDobleTopTrue
     */
    public function testIsDobleTopTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isDoubleTop($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsDobleTopTrue() {
        return [
            [[90, 150, 120, 150, 120], 3, 20], // идеал
            [[90, 150, 120, 154.5, 119], 3, 20], // по верхней границе
            [[90, 150, 120, 145.5, 119], 3, 20], // по нижней границе
        ];
    }
    
    /**
     * @dataProvider additionIsDobleTopFalse
     */
    public function testIsDobleTopFalse($courses, $percent, $percentDiff) {
        $actual = Service_GraphAnalisis::isDoubleTop($courses, $percent, $percentDiff);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsDobleTopFalse() {
        return [
            [[90, 100, 120, 150, 120], 3, 20], 
            [[90, 150, 150, 150, 120], 3, 20], 
            [[90, 150, 120, 120, 120], 3, 20], 
            [[90, 150, 120, 150, 150], 3, 20], 
            [[90, 150, 120, 150, 121], 3, 20], 
            
            [[90, 105, 100, 105, 99], 3, 20], 
            [[90, 150, 120, 154.6, 119], 3, 20], // по верхней границе
            [[90, 150, 120, 145.4, 119], 3, 20], // по нижней границе
            
            [[97, 150, 119, 150, 119], 3, 20],
        ];
    }
  
    
    //===========================================================================
   
    /**
     * @dataProvider additionIsDobleBottomTrue
     */
    public function testIsDobleBottomTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isDoubleBottom($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsDobleBottomTrue() {
        return [
            [[100, 50, 80, 50, 80], 3], // идеал
            [[100, 50, 80, 51.5, 81], 3], // по верхней границе
            [[100, 50, 80, 48.5, 81], 3], // по нижней границе
        ];
    }
    
    /**
     * @dataProvider additionIsDobleBottomFalse
     */
    public function testIsDobleBottomFalse($courses, $percent, $percentDiff) {
        $actual = Service_GraphAnalisis::isDoubleBottom($courses, $percent, $percentDiff);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsDobleBottomFalse() {
        return [
            [[100, 100, 80, 50, 81], 3, 20], 
            [[100,  50, 50, 50, 81], 3, 20], 
            [[100,  50, 80, 80, 81], 3, 20], 
            [[100,  50, 80, 50, 50], 3, 20], 
            [[100,  50, 80, 50, 79], 3, 20], 
            
            [[100, 95, 100, 95,   99], 3, 20], 
            [[100, 50, 80,  51.6, 81], 3, 20], // по верхней границе
            [[100, 50, 80, 48.4, 81], 3, 20], // по нижней границе
        ];
    }
    
    //===========================================================================
   
    /**
     * @dataProvider additionIsHeadShouldersTrue
     */
    public function testIsHeadShouldersTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isHeadShoulders($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsHeadShouldersTrue() {
        return [
            [[99, 150, 110, 220, 110, 170, 109.99], 3], // идеал
        ];
    }
    
    
    /**
     * @dataProvider additionIsHeadShouldersFalse
     */
    public function testIsHeadShouldersFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isHeadShoulders($courses, $percent);
        $this->assertFalse($actual);
        return true;
    }
    
    public function additionIsHeadShouldersFalse() {
        return [
            //  0 > 1
            [[99, 98, 110, 220, 110, 170, 109.99], 3],
            // 0 > 2
            [[99, 150, 110, 220, 110, 170, 109.99], 3]
            
            // 0 > 3
            // 0 > 4
            // 0 > 5
            // 0 > 6
            
            // 1 < 2
            // 1 > 3
            // 1 < 4
            // 1 > 5
            // 1 < 6
            
            // 2 > 3
            // 2 ~ 4
            // 2 > 5
            // 2 < 6
            
            // 3 < 4
            // 3 < 5
            // 3 < 6
            
            // 4 > 5
            // 4 < 6
            
            // 5 < 6
            
//            [[99, 150, 110, 220, 110, 170, 109.99], 3]
        ];
    }
    
    //===============================================================================
    
//        
//    /**
//     * @dataProvider additionIsDoubleTopFalse
//     */
//    public function testIsDoubleTopFalse($courses, $percent, $sureTrend) {
//        $actual = Service_GraphAnalisis::isDoubleTop($courses, $percent, $sureTrend);
//        $this->assertFalse($actual);        
//        return true;
//    }
//    
//    public function additionIsDoubleTopFalse() {
//        return [
//            // not up trend. mode=1. find stable up trend. /
//            [[100, 94, 105, 110, 115, 120], 5, 4],
//            [[100, 105, 96, 110, 115, 120], 5, 4],
//            [[100, 105, 110, 107, 95, 120], 5, 5],
//            // low line neck. mode=2. find down trend. /\
//            [[100, 105, 110, 107, 120,99, 94, 105],5, 4],
//            [[100, 105, 110, 107, 120,94, 105],5, 4],
//            // hight line Up support line. mode=3. find up trend. /\/
//            [[100, 105, 110, 107, 120,  96,  127],5, 4],
//            [[100, 105, 110, 107, 120,  99, 97, 99, 96,  105, 110, 115, 120, 130, 140],5, 4],
//            [[100, 105, 110, 107, 120,  99, 97, 99, 96,  105, 110, 115, 120, 126, 140],5, 4],
//            // hight line Up support line. mode=4. find down trend. /\/\
//            [[100, 105, 110, 120,  96,  122, 127],5, 3],
//            [[100, 105, 110, 120,  96,  122, 119, 121, 97],5, 3],
//            [[100, 105, 110, 120,  96,  122, 119, 121, 97, 96],5, 3],
//            [[100, 103, 103, 105, 102, 115, 103, 97, 96, 98, 95, 107,  105, 117, 100, 95] , 5, 3],
//        ];
//    }
//    
//    /**
//     * @dataProvider additionIsDoubleTopTrue
//     */
//    public function testIsDoubleTopTrue($courses, $percent, $sureTrend) {
//        $actual = Service_GraphAnalisis::isDoubleTop($courses, $percent, $sureTrend);
//        $this->assertTrue($actual);        
//        return true;
//    }
//    
//    public function additionIsDoubleTopTrue() {
//        return [
//            [[100, 103, 103, 105, 102, 115, 103, 102, 103, 102, 102, 107,  105, 117, 105, 101] , 5, 3],
//            [[100, 103, 103, 105, 102, 115, 103, 97, 96, 98, 95, 107,  105, 117, 100, 94, 92] , 5, 3],
//            [[100, 105, 110, 115, 120, 125, 130, 150, 130, 120, 125, 135, 140, 155, 125, 119] , 5, 3],
//            [[100, 105, 110, 115, 120, 125, 130, 150, 130, 155, 125, 119] , 5, 3],
//        ];
//    }
//    
}
