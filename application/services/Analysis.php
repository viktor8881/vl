<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_Analysis
 *
 * @author Viktor
 */
class Service_Analysis implements Service_Interface {
    
    
    private function getManager($name) {
        return Core_Container::getManager($name);
    }
    
    public function runByTask(Task_Model_Abstract $task, Core_Date $date) {
        $count = 0;
        if ($task->isPercent()) {
            $count += $this->runPercentByTask($task, $date);
        }elseif($task->isOvertime()){
            $count += $this->runOvertimeByTask($task, $date);
        }else{
            throw new Exception('Unknow type task');
        }
        return $count;
    }
    
    protected function runPercentByTask(Task_Model_Percent $task, Core_Date $date) {
        $countRec = 0;
        $dateLater = clone $date;
        $dateLater->sub(new DateInterval('P'.$task->getPeriod().'D'));
        foreach ($task->getCurrencies() as $currency) {
            $rows = $this->getManager('courseCurrency')->fetchAllByPeriodByCode($dateLater, $date, $currency->getCode());
            if (!($rows->count()>1)) {
                continue;
            }
            $courseFirst = $rows->first();
            $courseLast = $rows->last();
            if ($this->isValidTaskPercent($task, $courseFirst->getValue(), $courseLast->getValue())) {
                $subData = array('percent'=>$task->getPercent(),
                        'period'    =>$task->getPeriod(),
                        'startDate' =>$courseFirst->getDateFormatDMY(),
                        'startValue'=>$courseFirst->getValue(),
                        'endDate'   =>$courseLast->getDateFormatDMY(),
                        'endValue'  =>$courseLast->getValue()
                    );
                $data = array('type'=>AnalysisCurrency_Model_Abstract::TYPE_PERCENT,
                    'currency_code'=>$currency->getCode(),
                    'body'=>  json_encode($subData),
                    'created'=>$date);
                $analysis = $this->getManager('analysisCurrency')->createModel($data);
                $this->getManager('analysisCurrency')->insert($analysis);
                $countRec++;
            }
        }        
        foreach ($task->getMetals() as $metal) {
            $rows = $this->getManager('courseMetal')->fetchAllByPeriodByCode($dateLater, $date, $metal->getCode());
            if (!($rows->count()>1)) {
                continue;
            }
            $courseFirst = $rows->first();
            $courseLast = $rows->last();
            if ($this->isValidTaskPercent($task, $courseFirst->getValue(), $courseLast->getValue())) {
                $subData = array('percent'=>$task->getPercent(),
                        'period'    =>$task->getPeriod(),
                        'startDate' =>$courseFirst->getDateFormatDMY(),
                        'startValue'=>$courseFirst->getValue(),
                        'endDate'   =>$courseLast->getDateFormatDMY(),
                        'endValue'  =>$courseLast->getValue());
                $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_PERCENT,
                    'metal_code'=>$metal->getCode(),
                    'body'=>  json_encode($subData),
                    'created'=>$date);
                $analysis = $this->getManager('analysisMetal')->createModel($data);
                $this->getManager('analysisMetal')->insert($analysis);
                $countRec++;
            }
        }
        return $countRec;
    }
    
    
    protected function isValidTaskPercent(Task_Model_Percent $task, $firstValue, $lastValue) {
        $percent = $task->getPercent();
        $upValue    = $firstValue*(1+$percent/100);
        $downValue  = $firstValue*(1-$percent/100);
        switch ($task->getMode()) {
            case Task_Model_Percent::MODE_ONLY_UP:
                if (Core_Math::round($upValue, 6) < Core_Math::round($lastValue, 6)) {
                    return true;
                }
                break;
            case Task_Model_Percent::MODE_ONLY_DOWN:
                if (Core_Math::round($downValue, 6) > Core_Math::round($lastValue, 6)) {
                    return true;
                }
                break;
            case Task_Model_Percent::MODE_UP_DOWN:
                if (Core_Math::round($upValue, 6) < Core_Math::round($lastValue, 6) or 
                        Core_Math::round($downValue, 6) > Core_Math::round($lastValue, 6) ) {
                    return true;
                }
                break;
        }
        return false;
    }


    protected function runOvertimeByTask(Task_Model_OverTime $task, DateTime $date) {
        $countRec = 0;
        if ($task->countCurrencies()) {
            foreach ($task->getCurrencies() as $currency) {
                $listValues = $this->getManager('courseCurrency')->listValueForAnalysisByCodeToDate($currency->getCode(), $date);
                if ($this->isValidTaskOvertime($task, $listValues)) {
                    $subData = array('period'=>$task->getPeriod(),
                            'listData' =>$listValues);
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_OVER_TIME,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);

                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                    $countRec++;
                }
            }
        }
        if ($task->countMetals()) {
            foreach ($task->getMetals() as $metal) {
                $listValues = $this->getManager('courseMetal')->listValueForAnalysisByCodeToDate($metal->getCode(), $date);
                if ($this->isValidTaskOvertime($task, $listValues)) {
                    $subData = array('period'=>$task->getPeriod(),
                            'listData' =>$listValues);
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_OVER_TIME,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);

                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                    $countRec++;
                }
            }
        }
        return $countRec;
    }
    
    protected function isValidTaskOvertime(Task_Model_OverTime $task, array $values=[]) {
        if (count($values) >= $task->getPeriod()) {
            $firstValue = reset($values);
            $lastValue = end($values);
            switch ($task->getMode()) {
                case Task_Model_OverTime::MODE_ONLY_UP:
                    if (Core_Math::round($firstValue, 6) < Core_Math::round($lastValue, 6)) {
                        return true;
                    }
                    break;
                case Task_Model_OverTime::MODE_ONLY_DOWN:
                    if (Core_Math::round($firstValue, 6) > Core_Math::round($lastValue, 6)) {
                        return true;
                    }
                    break;
                case Task_Model_OverTime::MODE_UP_DOWN:
                    if (Core_Math::round($firstValue, 6) != Core_Math::round($lastValue, 6)) {
                        return true;
                    }
                    break;
            }
        }
        return false;
    }
    
    
}
