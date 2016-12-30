<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_GraphAnalysis
 *
 * @author Viktor
 */
//use PHPUnit\Framework\TestCase;


class Service_AnalysisTest extends Core_TestAbstract {
    
    
    private $serviceTest;
    
    public function setUp() {
        parent::setUp();
        $this->serviceTest = new Service_Analysis();
    }

    /**
     * @dataProvider data_TestIsValidTaskPercentOnlyUpIsTrue
     */
    public function testIsValidTaskPercentOnlyUpIsTrue($percent, $firstValue, $lastValue)
    {
        $mockTask = $this->createMock('Task_Model_Percent', ['getPercent', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPercent')
             ->will($this->returnValue($percent));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Percent::MODE_ONLY_UP));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskPercent');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $firstValue, $lastValue]);
        
        $this->assertTrue($except);
    }
    
    public function data_TestIsValidTaskPercentOnlyUpIsTrue() {
        return [
            [1, 80, 90],
            [1, 80, 80.800001]
        ];
    }
    
    /**
     * @dataProvider data_TestIsValidTaskPercentOnlyUpIsFalse
     */
    public function testIsValidTaskPercentOnlyUpIsFalse($percent, $firstValue, $lastValue)
    {
        $mockTask = $this->createMock('Task_Model_Percent', ['getPercent', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPercent')
             ->will($this->returnValue($percent));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Percent::MODE_ONLY_UP));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskPercent');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $firstValue, $lastValue]);
        
        $this->assertFalse($except);
    }
    
    public function data_TestIsValidTaskPercentOnlyUpIsFalse() {
        return [
            [1, 80, 80.800000],
            [1, 80, 70]
        ];
    }
    
    // =========================================================================
    
    /**
     * @dataProvider data_TestIsValidTaskPercentOnlyDownIsTrue
     */
    public function testIsValidTaskPercentOnlyDownIsTrue($percent, $firstValue, $lastValue)
    {
        $mockTask = $this->createMock('Task_Model_Percent', ['getPercent', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPercent')
             ->will($this->returnValue($percent));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Percent::MODE_ONLY_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskPercent');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $firstValue, $lastValue]);
        
        $this->assertTrue($except);
    }
    
    public function data_TestIsValidTaskPercentOnlyDownIsTrue() {
        return [
            [1, 80, 70],
            [1, 80, 79.199999],
        ];
    }
    
    /**
     * @dataProvider data_TestIsValidTaskPercentOnlyDownIsFalse
     */
    public function testIsValidTaskPercentOnlyDownIsFalse($percent, $firstValue, $lastValue)
    {
        $mockTask = $this->createMock('Task_Model_Percent', ['getPercent', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPercent')
             ->will($this->returnValue($percent));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Percent::MODE_ONLY_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskPercent');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $firstValue, $lastValue]);
        
        $this->assertFalse($except);
    }
    
    public function data_TestIsValidTaskPercentOnlyDownIsFalse() {
        return [
            [1, 80, 90],
            [1, 80, 79.2],
        ];
    }
    // =========================================================================
    
    /**
     * @dataProvider data_TestIsValidTaskPercentUpDownIsTrue
     */
    public function testIsValidTaskPercentUpDownIsTrue($percent, $firstValue, $lastValue)
    {
        $mockTask = $this->createMock('Task_Model_Percent', ['getPercent', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPercent')
             ->will($this->returnValue($percent));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Percent::MODE_UP_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskPercent');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $firstValue, $lastValue]);
        
        $this->assertTrue($except);
    }
    
    public function data_TestIsValidTaskPercentUpDownIsTrue() {
        return [
            [1, 80, 90],
            [1, 80, 80.800001],
            [1, 80, 70],
            [1, 80, 79.199999],
        ];
    }
    
    
    /**
     * @dataProvider data_TestIsValidTaskPercentUpDownIsFalse
     */
    public function testIsValidTaskPercentUpDownIsFalse($percent, $firstValue, $lastValue)
    {
        $mockTask = $this->createMock('Task_Model_Percent', ['getPercent', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPercent')
             ->will($this->returnValue($percent));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Percent::MODE_UP_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskPercent');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $firstValue, $lastValue]);
        
        $this->assertFalse($except);
    }
    
    public function data_TestIsValidTaskPercentUpDownIsFalse() {
        return [
            [1, 80, 80.800000],
            [1, 80, 80],
            [1, 80, 79.2],
        ];
    }

    
    //==========================================================================
    //==========================================================================
    
    /**
     * @dataProvider data_TestIsValidTaskOvertimeOnlyUpIsTrue
     */
    public function testIsValidTaskOvertimeOnlyUpIsTrue($period, $listValue)
    {
        $mockTask = $this->createMock('Task_Model_Overtime', ['getPeriod', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPeriod')
             ->will($this->returnValue($period));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Overtime::MODE_ONLY_UP));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskOvertime');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $listValue]);
        
        $this->assertTrue($except);
    }
    
    public function data_TestIsValidTaskOvertimeOnlyUpIsTrue() {
        return [
            [2, [80, 90]],
            [2, [80, 80.000001]]
        ];
    }
        
    /**
     * @dataProvider data_TestIsValidTaskOvertimeOnlyUpIsFalse
     */
    public function testIsValidTaskOvertimeOnlyUpIsFalse($period, $listValue)
    {
        $mockTask = $this->createMock('Task_Model_Overtime', ['getPeriod', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPeriod')
             ->will($this->returnValue($period));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Overtime::MODE_ONLY_UP));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskOvertime');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $listValue]);
        
        $this->assertFalse($except);
    }
    
    public function data_TestIsValidTaskOvertimeOnlyUpIsFalse() {
        return [
            [3, [80, 90]],  // не хватает значений 
            [2, [80, 80]],
        ];
    }
    // =========================================================================
    
    /**
     * @dataProvider data_TestIsValidTaskOvertimeOnlyDownIsTrue
     */
    public function testIsValidTaskOvertimeOnlyDownIsTrue($period, $listValue)
    {
        $mockTask = $this->createMock('Task_Model_Overtime', ['getPeriod', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPeriod')
             ->will($this->returnValue($period));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Overtime::MODE_ONLY_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskOvertime');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $listValue]);
        
        $this->assertTrue($except);
    }
    
    public function data_TestIsValidTaskOvertimeOnlyDownIsTrue() {
        return [
            [2, [80, 70]],
            [2, [80, 79.999999]]
        ];
    }
    
    /**
     * @dataProvider data_TestIsValidTaskOvertimeOnlyDownIsFalse
     */
    public function testIsValidTaskOvertimeOnlyDownIsFalse($period, $listValue)
    {
        $mockTask = $this->createMock('Task_Model_Overtime', ['getPeriod', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPeriod')
             ->will($this->returnValue($period));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Overtime::MODE_ONLY_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskOvertime');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $listValue]);
        
        $this->assertFalse($except);
    }
    
    public function data_TestIsValidTaskOvertimeOnlyDownIsFalse() {
        return [
            [2, [80, 80]],
            [2, [80, 90]]
        ];
    }
    // =========================================================================
    
    /**
     * @dataProvider data_TestIsValidTaskOvertimeUpDownIsTrue
     */
    public function testIsValidTaskOvertimeUpDownIsTrue($period, $listValue)
    {
        $mockTask = $this->createMock('Task_Model_Overtime', ['getPeriod', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPeriod')
             ->will($this->returnValue($period));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Overtime::MODE_UP_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskOvertime');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $listValue]);
        
        $this->assertTrue($except);
    }
    
    public function data_TestIsValidTaskOvertimeUpDownIsTrue() {
        return [
            [2, [80, 90]],
            [2, [80, 80.000001]],
            [2, [80, 79.999999]],
            [2, [80, 70]]
        ];
    }
    
    /**
     * @dataProvider data_TestIsValidTaskOvertimeUpDownIsFalse
     */
    public function testIsValidTaskOvertimeUpDownIsFalse($period, $listValue)
    {
        $mockTask = $this->createMock('Task_Model_Overtime', ['getPeriod', 'getMode'], [], '', false);         
        $mockTask->expects($this->any())
             ->method('getPeriod')
             ->will($this->returnValue($period));
        $mockTask->expects($this->any())
             ->method('getMode')
             ->will($this->returnValue(Task_Model_Overtime::MODE_UP_DOWN));
        
        $methodTest = self::callProtectedMethod('Service_Analysis', 'isValidTaskOvertime');
        $except = $methodTest->invokeArgs($this->serviceTest, [$mockTask, $listValue]);
        
        $this->assertFalse($except);
    }
    
    public function data_TestIsValidTaskOvertimeUpDownIsFalse() {
        return [
            [2, [80, 80]]            
        ];
    }
    
    
}
