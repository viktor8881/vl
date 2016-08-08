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
    
    public function runByTask(Task_Model_Abstract $task) {
        if ($task->isPercent()) {
            $this->runPercentByTask($task);
        }elseif($task->isOvertime()){
            $this->runOvertimeByTask($task);
        }else{
            throw new RuntimeException('Unknow type task');
        }
    }
    
    public function runPercentByTask(Task_Model_Percent $task) {
        if ($task->countCurrencies()) {
            $dateNow = new Core_Date();
            $dateLater = clone $dateNow;
            $dateLater->sub(new DateInterval('P'.$task->getPeriod().'D'));
            foreach ($task->getCurrencies() as $currency) {
                $rows = $this->getManager('courseCurrency')->fetchAllByPeriodByCode($dateLater, $dateNow, $currency->getCode());
                if ($rows->count() > 1) {
                    $courseToday = $rows->first();
                    $courseStartPeriod = $rows->last();
                    $minValue = $courseToday->getValueForOne()*(1+$percent/100);
                    if ($minValue <= $courseStartPeriod->getValueForOne()) {
                        $subData = array('percent'=>$task->getPercent(),
                                'period'    =>$task->getPeriod(),
                                'startDate' =>$courseToday->getDate(),
                                'startValue'=>$courseToday->getValue(),
                                'endDate'   =>$courseStartPeriod->getDate(),
                                'endValue'  =>$courseStartPeriod->getValue());
                        $data = array('id'=>$this->getId(),
                            'type'=>AnalisisMetal_Model_Abstract::TYPE_PERCENT,
                            'currency_code'=>$currency->getMetalCode(),
                            'body'=>  json_encode($subData),
                            'created'=>new Core_Date());
                        
                        $analisis = $this->getManager('analisisCurrency')->createModel($data);
                        $this->getManager('analisisCurrency')->insert($analisis);
                    }
                    //
                    $minValue = $courseToday->getValueForOne()*(1-$percent/100);
                    if ($minValue >= $courseStartPeriod->getValueForOne()) {
                        $curr = new Mail_CurrencyModel();
                        $curr->setName($currency->getName())
                                ->setStartDate($courseToday->getDate())
                                ->setStartValue($courseToday->getValue())
                                ->setEndDate($courseStartPeriod->getDate())
                                ->setEndValue($courseStartPeriod->getValue());
                        $messageDown->addCurrency($curr);                    
                    }
                }
            }
        }
        if ($task->countMetals()) {
            
        }
    }
    
    public function runOvertimeByTask(Task_Model_OverTime $task) {
        
    }

    
}
