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
            [[99, 150, 110, 220, 110, 170, 99.01], 3], // идеал
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
            [[99, 98.99, 110, 220, 110, 170, 99.01], 3],
            // 0 > 2
            [[99, 150, 98.99, 220, 110, 170, 99.01], 3],
            // 0 > 3
             [[99, 150, 110, 98.99, 110, 170, 99.01], 3],
            // 0 > 4
            [[99, 150, 110, 220, 98.99, 170, 99.01], 3],
            // 0 > 5
             [[99, 150, 110, 220, 110, 98.99, 99.01], 3],
            // 0 > 6
             [[99, 150, 110, 220, 110, 170, 98.99], 3],
            
            // 1 < 2
             [[99, 150, 150.01, 220, 110, 170, 99.01], 3],
            // 1 > 3
             [[99, 150, 110, 149.99, 110, 170, 99.01], 3],
            // 1 < 4
             [[99, 150, 110, 220, 150.01, 170, 99.01], 3],
            // 1 > 5
             [[99, 150, 110, 220, 110, 149.99, 99.01], 3],
            // 1 < 6
             [[99, 150, 110, 220, 110, 170, 98.99], 3],
            
            // 2 > 3
             [[99, 150, 110, 109.99, 110, 170, 99.01], 3],
            // 2 ~ 4
             [[99, 150, 110, 220, 113.3, 170, 99.01], 3], // за вверх границы
             [[98, 150, 110, 220, 106.7, 170, 99], 3], // за низ границы
            // 2 > 5
             [[99, 150, 110, 220, 110, 109.99, 99.01], 3],
            // 2 < 6
             [[99, 150, 110, 220, 110, 170, 110.01], 3],
            
            // 3 < 4
             [[99, 150, 110, 220, 220.01, 170, 99.01], 3],
            // 3 < 5
             [[99, 150, 110, 220, 110, 220.01, 99.01], 3],
            // 3 < 6
             [[99, 150, 110, 220, 110, 170, 220.01], 3],
            
            // 4 > 5
             [[99, 150, 110, 220, 110, 110.01, 99.01], 3],
            // 4 < 6
             [[99, 150, 110, 220, 110, 170, 110.01], 3],
            
            // 5 < 6
             [[99, 150, 110, 220, 110, 170, 170], 3],
        ];
    }
    
    
    //===========================================================================
   
    /**
     * @dataProvider additionIsReverseHeadShouldersTrue
     */
    public function testIsReverseHeadShouldersTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isReverseHeadShoulders($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsReverseHeadShouldersTrue() {
        return [
            [[500, 250, 300, 150, 300, 200, 301], 3], // идеал
        ];
    }
    
    /**
     * @dataProvider additionIsReverseHeadShouldersFalse
     */
    public function testIsReverseHeadShouldersFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isReverseHeadShoulders($courses, $percent);
        $this->assertFalse($actual);
        return true;
    }
    
    public function additionIsReverseHeadShouldersFalse() {
        return [
            // 0 < 1
            [[500, 500.01, 300, 150, 300, 200, 301], 3], 
            // 0 < 2
             [[500, 250, 500.01, 150, 300, 200, 301], 3], 
            // 0 < 3
             [[500, 250, 300, 500.01, 300, 200, 301], 3], 
            // 0 < 4
             [[500, 250, 300, 150, 500.01, 200, 301], 3], 
            // 0 < 5
             [[500, 250, 300, 150, 300, 500.01, 301], 3], 
            // 0 < 6
            [[500, 250, 300, 150, 300, 200, 500.01], 3], 
            
            // 1 > 2
             [[500, 250, 249.99, 150, 300, 200, 301], 3], 
            // 1 < 3
             [[500, 250, 300, 250.01, 300, 200, 301], 3], 
            // 1 > 4
             [[500, 300.01, 300, 150, 300, 200, 301], 3], 
            // 1 < 5
             [[500, 250, 300, 150, 300, 250.01, 301], 3], 
            // 1 > 6
            [[500, 301.01, 300, 150, 300, 200, 301], 3], 
            
            // 2 < 3
             [[500, 250, 300, 300.01, 300, 200, 301], 3], 
            // 2 ~ 4
             [[500, 250, 300, 150, 309, 200, 301], 3], // за вверх границы
             [[500, 250, 300, 150, 291, 200, 301], 3], // за низ границы
            // 2 < 5
             [[500, 250, 300, 150, 300, 300.01, 301], 3], 
            // 2 > 6
            [[500, 301.01, 300, 150, 300, 200, 301], 3], 
             
            // 3 > 4
             [[500, 250, 300, 300.01, 300, 200, 301], 3],
            // 3 > 5
             [[500, 250, 300, 200.01, 300, 200, 301], 3],
            // 3 > 6
            [[500, 250, 300, 301.01, 300, 200, 301], 3],
             
            // 4 < 5
             [[500, 250, 300, 150, 300, 300.01, 301], 3], 
            // 4 > 6
            [[500, 250, 300, 150, 301.01, 200, 301], 3], 
             
            // 5 > 6
            [[500, 250, 300, 150, 300, 301.01, 301], 3], 
        ];
    }
    
    //==========================================================================
    
    /**
     * @dataProvider additionIsTripleTopTrue
     */
    public function testIsTripleTopTrue($courses, $percentBottom, $percentTop) {
        $actual = Service_GraphAnalisis::isTripleTop($courses,  $percentBottom, $percentTop);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsTripleTopTrue() {
        return [
            [[100, 250, 150, 250, 150, 250, 140], 3, 5], // идеал
        ];
    }
    
    /**
     * @dataProvider additionIsTripleTopFalse
     */
    public function testIsTripleTopFalse($courses, $percentBottom, $percentTop) {
        $actual = Service_GraphAnalisis::isTripleTop($courses, $percentBottom, $percentTop);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsTripleTopFalse() {
        return [
            // 0 > 1
             [[100, 99.99, 150, 250, 150, 250, 140], 3, 5],
            // 0 > 2
             [[100, 250, 99.99, 250, 150, 250, 140], 3, 5],
            // 0 > 3
             [[100, 250, 150, 99.99, 150, 250, 140], 3, 5],
            // 0 > 4
             [[100, 250, 150, 250, 99.99, 250, 140], 3, 5],
            // 0 > 5
             [[100, 250, 150, 250, 150, 99.99, 140], 3, 5],
            // 0 > 6
             [[100, 250, 150, 250, 150, 250, 99.99], 3, 5],
            
            // 1 < 2
             [[100, 250, 250.01, 250, 150, 250, 140], 3, 5],
            // 1 ~ 3 по низу
             [[100, 250, 150, 237.50, 150, 250, 140], 3, 5],
            // 1 ~ 3 по верху
             [[100, 250, 150, 269.86, 150, 250, 140], 3, 5],
            // 1 < 4
             [[100, 250, 150, 250, 250.01, 250, 140], 3, 5],
            // 1 ~ 5 по низу
             [[100, 250, 150, 250, 150, 237.50, 140], 3, 5],
            // 1 ~ 5 по верху
             [[100, 250, 150, 250, 150, 262.5, 140], 3, 5],
            // 1 < 6
             [[100, 250, 150, 250, 150, 250, 250.01], 3, 5],
             
            // 2 > 3
             [[100, 250, 250.01, 250, 150, 250, 140], 3, 5],
            // 2 > 5
             [[100, 250, 250.01, 250, 150, 250, 140], 3, 5],
            // 2 ~ 4 по низу
             [[100, 250, 150, 250, 145.5, 250, 140], 3, 5],
            // 2 ~ 4 по верху
             [[100, 250, 150, 250, 154.5, 250, 140], 3, 5],
            // 2 < 6
             [[100, 250, 139.99, 250, 150, 250, 140], 3, 5],
             
            // 3 < 4
             [[100, 250, 150, 250, 250, 250, 140], 3, 5],
            // 3 ~ 5 по низу
             [[100, 250, 150, 250, 150, 237.50, 140], 3, 5],
            // 3 ~ 5 по верху
             [[100, 250, 150, 250, 150, 262.5, 140], 3, 5],
            // 3 < 6
             [[100, 250, 150, 250, 150, 250, 250], 3, 5],
             
            // 4 > 5
             [[100, 250, 150, 250, 150, 150, 140], 3, 5],
            // 4 < 6
             [[100, 250, 150, 250, 150, 250, 150], 3, 5],
             
            // 5 < 6
            [[100, 250, 150, 250, 150, 140, 140], 3, 5],
        ];
    }
    
    //==========================================================================
    
    /**
     * @dataProvider additionIsTripleBottomTrue
     */
    public function testIsTripleBottomTrue($courses, $percentBottom, $percentTop) {
        $actual = Service_GraphAnalisis::isTripleBottom($courses, $percentBottom, $percentTop);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsTripleBottomTrue() {
        return [
            [[500, 150, 250, 150, 250, 150, 270], 3, 5], // идеал
        ];
    }
    
    /**
     * @dataProvider additionIsTripleBottomFalse
     */
    public function testIsTripleBottomFalse($courses, $percentBottom, $percentTop) {
        $actual = Service_GraphAnalisis::isTripleBottom($courses, $percentBottom, $percentTop);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsTripleBottomFalse() {
        return [
            // 0 < 1
            [[149.99, 150, 250, 150, 250, 150, 270], 3, 5], 
            // 0 < 2
            [[249.99, 150, 250, 150, 250, 150, 270], 3, 5], 
            // 0 < 3
            [[149.99, 150, 250, 150, 250, 150, 270], 3, 5], 
            // 0 < 4
            [[249.99, 150, 250, 150, 250, 150, 270], 3, 5], 
            // 0 < 5
            [[149.99, 150, 250, 150, 250, 150, 270], 3, 5], 
            // 0 < 6
            [[259.99, 150, 250, 150, 250, 150, 270], 3, 5], 
             
            // 1 > 2
            [[500, 250.01, 250, 150, 250, 150, 270], 3, 5], 
            // 1 ~ 3 по низу
            [[500, 150, 250, 145.50, 250, 150, 270], 3, 5], 
            // 1 ~ 3 по верху
            [[500, 150, 250, 154.50, 250, 150, 270], 3, 5], 
            // 1 > 4
            [[500, 150, 250, 150, 149.99, 150, 270], 3, 5], 
            // 1 ~ 5 по низу
            [[500, 150, 250, 150, 250, 145.50, 270], 3, 5], 
            // 1 ~ 5 по верху
            [[500, 150, 250, 150, 250, 154.5, 270], 3, 5], 
            // 1 > 6
            [[500, 150, 250, 150, 250, 150, 149.99], 3, 5], 
             
            // 2 < 3
            [[500, 150, 250, 250.01, 250, 150, 270], 3, 5], 
            // 2 ~ 4 по низу
            [[500, 150, 250, 150, 237.50, 150, 270], 3, 5], 
            // 2 ~ 4 по верху
            [[500, 150, 250, 150, 262.5, 150, 270], 3, 5], 
            // 2 < 5
            [[500, 150, 250, 150, 250, 250.01, 270], 3, 5], 
            // 2 > 6
            [[500, 150, 250, 150, 250, 150, 249.99], 3, 5], 
             
            // 3 > 4
            [[500, 150, 250, 150, 149.99, 150, 270], 3, 5], 
            // 3 ~ 5 по низу
            [[500, 150, 250, 150, 250, 145.50, 270], 3, 5], 
            // 3 ~ 5 по верху
            [[500, 150, 250, 150, 250, 154.5, 270], 3, 5], 
            // 3 > 6
            [[500, 150, 250, 150, 250, 150, 149.99], 3, 5], 
             
            // 4 < 5
            [[500, 150, 250, 150, 250, 250.01, 270], 3, 5], 
            // 4 > 6
            [[500, 150, 250, 150, 250, 150, 249.99], 3, 5], 
             
            // 5 > 6
            [[500, 150, 250, 150, 250, 150, 150.01], 3, 5], 
        ];
    }
    
    // =========================================================================

    /**
     * @dataProvider additionIsAscendingTriangleTrue
     */
    public function testIsAscendingTriangleTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isAscendingTriangle($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsAscendingTriangleTrue() {
        return [
            [[100, 20, 50, 22, 50, 24, 50, 26], 1],
            [[10,      50, 22, 50, 24, 50, 26], 1]
            ];
    }        
    
    /**
     * @dataProvider additionIsAscendingTriangleFalse
     */
    public function testIsAscendingTriangleFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isAscendingTriangle($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsAscendingTriangleFalse() {
        return [
            // count < 5
            [[1, 2, 3, 4], 3], 
            // точка входа ($startKey) не определена
            [[1, 1, 3, 4, 5], 3], 
            // $startKey+1 > $startKey+3 ($startKey = 1)
            [[100, 20, 50, 22, 50, 21.99, 50, 26], 1],
            [[10,  50, 22, 50, 21.99, 50, 26], 1],
            // не горизонт
            [[100, 20, 50, 22, 50.51, 24, 50, 26], 1],
            [[10, 50, 22, 50.51, 24, 50, 26], 1],
            // не верный подъем
            [[100, 20, 50, 22, 50, 24, 50, 28.57], 1],// по верху
            [[100, 20, 50, 22, 50, 24, 50, 23.79], 1],// по низу
            // прорыв по верху
            [[100, 20, 50, 22, 50, 24, 50, 26, 55], 1],
            [[10,      50, 22, 50, 24, 50, 26, 55], 1],
            // прорыв по низу
            [[100, 20, 50, 22, 50, 24, 50, 26, 50, 23], 1],
            [[10,      50, 22, 50, 24, 50, 26, 55, 23], 1],
            ];
    }
    
    
    // =========================================================================

    /**
     * @dataProvider additionIsDescendingTriangleTrue
     */
    public function testIsDescendingTriangleTrue($courses, $percent) {
        $actual = Service_GraphAnalisis::isDescendingTriangle($courses, $percent);
        $this->assertTrue($actual);        
        return true;
    }
    
    public function additionIsDescendingTriangleTrue() {
        return [
            [[100, 20, 50, 20, 48, 20, 46, 20], 1],
            [[10,  50, 20, 48, 20, 46, 20, 44], 1]
            ];
    }
        
    /**
     * @dataProvider additionIsDescendingTriangleFalse
     */
    public function testIsDescendingTriangleFalse($courses, $percent) {
        $actual = Service_GraphAnalisis::isDescendingTriangle($courses, $percent);
        $this->assertFalse($actual);        
        return true;
    }
    
    public function additionIsDescendingTriangleFalse() {
        return [
            // count < 5
            [[1, 2, 3, 4], 3], 
            // точка входа ($startKey) не определена
            [[1, 1, 3, 4, 5], 3], 
            // $startKey+3 > $startKey+1 ($startKey = 1)
            [[100, 20, 50, 20, 50.01, 20, 46, 20], 1],
            [[10,  50, 20, 48, 20, 48.01, 20, 44], 1],
            // не горизонт
            [[100, 20, 50, 20.21, 48, 20, 46, 20], 1],
            [[10,  50, 20, 48, 20, 46, 20.21, 44], 1],
            [[100, 20, 50, 20, 48, 19.79, 46, 20], 1],
            [[10,  50, 20, 48, 20, 46, 19.79, 44], 1],
            // не верный спуск
            [[100, 20, 50, 20, 48, 20, 47.84, 20], 1],// по верху
            [[100, 20, 50, 20, 48, 20, 44.00, 20], 1],// по низу
            // прорыв по верху
            [[100, 20, 50, 20, 48, 20, 46, 20, 50], 1],
            [[10,  50, 20, 48, 20, 46, 20, 44, 50], 1],
            // прорыв по низу
            [[100, 20, 50, 20, 48, 20, 46, 20, 15], 1],
            [[10,  50, 20, 48, 20, 46, 20, 44, 15], 1]
            ];
    }
    
}
