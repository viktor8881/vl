<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_Autoinvestment
 *
 * @author Viktor
 */
class Service_Autoinvestment {
    
    const PERCENT_RATE    = 0.95;
    const OVERTIME_RATE   = 1;
    
    // сколько едениц прибавить для расчета суммы списания
    const ADD_DIFF = 10;
    
    // list positive analysis
    private $listPositive = array();
    // list negative analysis
    private $listNegative = array();
    
    private $listMetalSub       = array();
    private $listCurrencySub    = array();
    private $listMetalAdd       = array();
    private $listCurrencyAdd    = array();
    
    
    
    private function getManager($name) {
        return Core_Container::getManager($name);
    }
    
    public function run(Core_Date $date) {
        foreach($this->getManager('analysisCurrency')->fetchAllByDate($date) as $analysis) {
            $weight = $this->getWeightAnalysis($analysis);
            if ($analysis->isQuotesFall()) {
                $this->listNegative[$weight] = $analysis;
            }else{
                $this->listPositive[$weight] = $analysis;
            }
        }
        foreach($this->getManager('analysisMetal')->fetchAllByDate($date) as $analysis) {
            if ($analysis->isQuotesFall()) {
                $this->listNegative[$weight] = $analysis;
            }else{
                $this->listPositive[$weight] = $analysis;
            }
        }
        // найдем сколько нужно списать
        if (count($this->listNegative)) {
            ksort($this->listNegative);
            $sumNeg = abs(array_sum(array_keys($this->listNegative)))+self::ADD_DIFF;
            foreach ($this->listNegative as $index=>$negA) {
                // определяем тип инвестиции
                if ($negA instanceof AnalysisMetal_Model_Abstract) {
                    $code = $negA->getMetalCode();
                    $balance = $this->getManager('BalanceMetal')->getByCode($code);
                    if ($balance) {
                        if (Core_Math::compareMoney($balance->getBalance(), 0) == 1) {
                            $balanceValue = $balance->getBalance() * (abs($index)/$sumNeg);
                            $balance->sub($balanceValue);
                            $this->getManager('BalanceMetal')->update($balance);
                            // пополняем основной счет по курсу
                            $this->getManager('account')->addPay($this->getManager('BalanceMetal')->getSellCodeByDate($code, $date) * $balanceValue);
                        }
                    }
                }elseif ($negA instanceof AnalysisCurrency_Model_Abstract) {
                    $code = $negA->getCurrencyCode();
                    $balance = $this->getManager('BalanceCurrency')->getByCode($code);
                    if ($balance) {
                        $balanceValue = $balance->getBalance();
                        if (Core_Math::compareMoney($balanceValue, 0) == 1) {
                            $balanceValue = $balance->getBalance() * (abs($index)/$sumNeg);
                            $balance->sub($balanceValue);
                            $this->getManager('BalanceCurrency')->update($balance);
                            // пополняем основной счет по курсу
                            $this->getManager('account')->addPay($this->getManager('BalanceCurrency')->getValueCodeByDate($code, $date) * $balanceValue);
                        }
                    }
                }
            }
        }
        // переводим на положительный прогноз
//        if (!count($this->listPositive)) {
//            
//            foreach ($this->listMetalSub as $code=>$value) {
//                $balance = $this->getManager('BalanceMetal')->getByCode($code);
//                $balance->sub($value);
//            }
//        }else{
//            
//        }
        
    }
    
    public function getWeightAnalysis($analisys) {
        if ($analisys->isPercent()) {
            return $analisys->getDiffPercent() * $analisys->getPeriod() * self::PERCENT_RATE;
        }else{
            return $analisys->getDiffPercent() * $analisys->countData() * self::OVERTIME_RATE;
        }
    }

    
    
    
    // =========================================================================
    
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
                        'created'=>new Core_Date());
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
                        'created'=>new Core_Date());
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
