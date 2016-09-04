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
    const ADD_DIFF_SELL = 13;
    const ADD_DIFF_BAY  = 17;
    
    // list positive analysis
    private $listPositive = array();
    // list negative analysis
    private $listNegative = array();
    
    
    
    private function getManager($name) {
        return Core_Container::getManager($name);
    }
    
    public function run(Core_Date $date) {
        foreach($this->getManager('analysisCurrency')->fetchAllByDate($date) as $analysis) {
            $weight = $this->getWeightAnalysis($analysis);
            if ($analysis->isQuotesFall()) {
                $listNegative[$weight] = $analysis;
            }else{
                $listPositive[$weight] = $analysis;
            }
        }
        foreach($this->getManager('analysisMetal')->fetchAllByDate($date) as $analysis) {
            $weight = $this->getWeightAnalysis($analysis);
            if ($analysis->isQuotesFall()) {
                $listNegative[$weight] = $analysis;
            }else{
                $listPositive[$weight] = $analysis;
            }
        }
        // найдем сколько нужно списать
        if (count($listNegative)) {
            $sumNeg = abs(array_sum(array_keys($listNegative)))+self::ADD_DIFF_SELL;
            foreach ($listNegative as $index=>$negA) {
                // определяем тип инвестиции
                if ($negA instanceof AnalysisMetal_Model_Abstract) {
                    $code = $negA->getMetalCode();
                    $balance = $this->getManager('BalanceMetal')->getByCode($code);
                    if ($balance && Core_Math::compareMoney($balance->getBalance(), 0) == 1) {
                        // сколько продать
                        $count = Core_Math::roundMoney($balance->getBalance() * (abs($index)/$sumNeg));
                        $invest = $this->getManager('InvestmentMetal')->createModel();
                        $invest->setType(InvestmentMetal_Model::TYPE_SELL)
                                ->setCount($count)
                                ->setMetalCode($code)
                                ->setCourse($this->getManager('CourseMetal')->getValueCodeByDate($code, $date))
                                ->setDate($date);
                        $this->getManager('InvestmentMetal')->insertSell($invest);
                    }
                }elseif ($negA instanceof AnalysisCurrency_Model_Abstract) {
                    $code = $negA->getCurrencyCode();
                    $balance = $this->getManager('BalanceCurrency')->getByCode($code);
                    if ($balance && Core_Math::compareMoney($balance->getBalance(), 0) == 1) {
                        // сколько продать
                        $count = Core_Math::roundMoney($balance->getBalance() * (abs($index)/$sumNeg));
                        $invest = $this->getManager('InvestmentCurrency')->createModel();
                        $invest->setType(InvestmentCurrency_Model::TYPE_SELL)
                                ->setCount($count)
                                ->setMetalCode($code)
                                ->setCourse($this->getManager('CourseCurrency')->getValueCodeByDate($code, $date))
                                ->setDate($date);
                        $this->getManager('InvestmentCurrency')->insertSell($invest);
                    }
                }
            }
        }
        if (count($listPositive)) {
            $accValue = $this->getManager('account')->getValue();
            $sumPos = array_sum(array_keys($listPositive)) + self::ADD_DIFF_BAY;    
            foreach ($listPositive as $index=>$posA) {
                // определяем тип инвестиции
                if ($posA instanceof AnalysisMetal_Model_Abstract) {
                    $code = $posA->getMetalCode();
                    // курс
                    $course = $this->getManager('CourseMetal')->getValueCodeByDate($code, $date);
                    // сколько купить
                    $count = Core_Math::roundMoney(($accValue * $index / $sumPos)/$course);
                    $invest = $this->getManager('InvestmentMetal')->createModel();
                    $invest->setType(InvestmentMetal_Model::TYPE_BUY)
                            ->setCount($count)
                            ->setMetalCode($code)
                            ->setCourse($course)
                            ->setDate($date);
                    $this->getManager('InvestmentMetal')->insertBuy($invest);
                }elseif ($posA instanceof AnalysisCurrency_Model_Abstract) {
                    $code = $posA->getCurrencyCode();
                    // курс
                    $course = $this->getManager('CourseCurrency')->getValueCodeByDate($code, $date);
                    // сколько купить
                    $count = Core_Math::roundMoney(($accValue * $index / $sumPos)/$course);
                    $invest = $this->getManager('InvestmentCurrency')->createModel();
                    $invest->setType(InvestmentCurrency_Model::TYPE_BUY)
                            ->setCount($count)
                            ->setCurrencyCode($code)
                            ->setCourse($course)
                            ->setDate($date);
                    $this->getManager('InvestmentCurrency')->insertBuy($invest);
                }
            }
        }
    }
    
    public function getWeightAnalysis($analisys) {
        if ($analisys->isPercent()) {
            return $analisys->getDiffPercent() * $analisys->getPeriod() * self::PERCENT_RATE;
        }else{
            return $analisys->getDiffPercent() * $analisys->countData() * self::OVERTIME_RATE;
        }
    }

    
}
