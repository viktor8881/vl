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
            throw new RuntimeException('Unknow type task');
        }
        return $count;
    }
    
    public function runPercentByTask(Task_Model_Percent $task, Core_Date $date) {
        $countRec = 0;
//        $dateNow = new Core_Date();
        $dateNow = $date;
        $dateLater = clone $dateNow;
        $dateLater->sub(new DateInterval('P'.$task->getPeriod().'D'));
        if ($task->countCurrencies()) {
            foreach ($task->getCurrencies() as $currency) {
                $rows = $this->getManager('courseCurrency')->fetchAllByPeriodByCode($dateLater, $dateNow, $currency->getCode());
                if ($rows->count() > 1) {
                    $percent = $task->getPercent();
                    $courseToday = $rows->first();
                    $courseStartPeriod = $rows->last();
                    $upValue = $courseToday->getValueForOne()*(1+$percent/100);
                    $downValue = $courseToday->getValueForOne()*(1-$percent/100);
                    if ($task->isModeOnlyUp() && $upValue > $courseStartPeriod->getValueForOne()) {
                        continue;
                    }elseif ($task->isModeOnlyDown() && $downValue < $courseStartPeriod->getValueForOne()) {
                        continue;
                    }
                    $subData = array('percent'=>$task->getPercent(),
                            'period'    =>$task->getPeriod(),
                            'startDate' =>$courseToday->getDateFormatDMY(),
                            'startValue'=>$courseToday->getValue(),
                            'endDate'   =>$courseStartPeriod->getDateFormatDMY(),
                            'endValue'  =>$courseStartPeriod->getValue()
                        );
                    $data = array('type'=>AnalysisCurrency_Model_Abstract::TYPE_PERCENT,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$dateNow);
                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                    $countRec++;
                }
            }
        }
        if ($task->countMetals()) {
            foreach ($task->getMetals() as $metal) {
                $rows = $this->getManager('courseMetal')->fetchAllByPeriodByCode($dateLater, $dateNow, $metal->getCode());
                if ($rows->count() > 1) {
                    $percent = $task->getPercent();
                    $courseToday = $rows->first();
                    $courseStartPeriod = $rows->last();
                    $upValue = $courseToday->getValue()*(1+$percent/100);
                    $downValue = $courseToday->getValue()*(1-$percent/100);
                    
                    if ($task->isModeOnlyUp() && $upValue > $courseStartPeriod->getValue()) {
                        continue;
                    }elseif ($task->isModeOnlyDown() && $downValue < $courseStartPeriod->getValue()) {
                        continue;
                    }
                    $subData = array('percent'=>$task->getPercent(),
                            'period'    =>$task->getPeriod(),
                            'startDate' =>$courseToday->getDateFormatDMY(),
                            'startValue'=>$courseToday->getValue(),
                            'endDate'   =>$courseStartPeriod->getDateFormatDMY(),
                            'endValue'  =>$courseStartPeriod->getValue());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_PERCENT,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$dateNow);
                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                    $countRec++;
                }
            }
        }
        return $countRec;
    }
    
    
    
    public function runOvertimeByTask(Task_Model_OverTime $task, DateTime $date) {
        $countRec = 0;        
//        $dateNow = new Core_Date();
        $dateNow = $date;
        if ($task->countCurrencies()) {
            foreach ($task->getCurrencies() as $currency) {
                $rows = $this->getManager('courseCurrency')->fetchAllForAnalysisByCodeToDate($currency->getCode(), $dateNow);
                if ($rows->count() > $task->getPeriod()) {
                    if ($task->isModeOnlyUp() && $rows->isQuotesGrowth()) {
                        continue;
                    }elseif ($task->isModeOnlyDown() && $rows->isQuotesFall()) {
                        continue;
                    }
                    
                    $subData = array('period'=>$task->getPeriod(),
                            'listData' =>$rows->listDateCourse());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_OVER_TIME,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>new Core_Date());

                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                    $countRec++;
                }
            }
        }
        if ($task->countMetals()) {
            foreach ($task->getMetals() as $metal) {
                $rows = $this->getManager('courseMetal')->fetchAllForAnalysisByCodeToDate($metal->getCode(), $dateNow);
                if ($rows->count() > $task->getPeriod()) {
                    if ($task->isModeOnlyUp() && $rows->isQuotesGrowth()) {
                        continue;
                    }elseif ($task->isModeOnlyDown() && $rows->isQuotesFall()) {
                        continue;
                    }
                    $subData = array('period'=>$task->getPeriod(),
                            'listData' =>$rows->listDateCourse());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_OVER_TIME,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>new Core_Date());

                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                    $countRec++;
                }
            }
        }
        return $countRec;
    }
    
    
}
