<?php

class BalanceController extends Core_Controller_Action
{

    public function indexAction() {
        $balances = new Model_CardsBalance();
        foreach ($this->getManager('balanceCurrency')->fetchAll() as $balance) {
            if (Core_Math::compare($balance->getBalance(), 0, 6) == 0) { continue; }
            $course = $this->getManager('courseCurrency')->lastByCurrencyCode($balance->getCurrencyCode());
            $sum = $this->getManager('investmentCurrency')->getSumByBalance($balance);
            $model = new Model_CardCurrencyBalance();
            $model->setBalance($balance)
                    ->setCurrentCourse($course)
                    ->setSumInvest($sum);
            $balances->addBalanceCurrency($model);
        }
        
        foreach ($this->getManager('balanceMetal')->fetchAll() as $balance) {
            if (Core_Math::compare($balance->getBalance(), 0, 6) == 0) { continue; }
            $course = $this->getManager('courseMetal')->lastByMetalCode($balance->getMetalCode());
            $sum = $this->getManager('investmentMetal')->getSumByBalance($balance);
            $model = new Model_CardMetalBalance();
            $model->setBalance($balance)
                    ->setCurrentCourse($course)
                    ->setSumInvest($sum);
            $balances->addBalanceMetal($model);
        }
        $this->view->balances = $balances;
        
//        pr($card);
//        $filters = new Core_Domen_Filter_Collection();
//        $filters->addFilter(new BalanceCurrency_Filter_GrBalance(0));
//        $this->view->balanceCurrency = $this->getManager('balanceCurrency')->fetchAllByFilter($filters);
//        $this->view->courseCurrency = $this->getManager('courseCurrency')->getLast();
//        
//        $filters = new Core_Domen_Filter_Collection();
//        $filters->addFilter(new BalanceMetal_Filter_GrBalance(0));
//        $this->view->balanceMetal = $this->getManager('balanceMetal')->fetchAllByFilter($filters);
//        $this->view->courseMetal = $this->getManager('courseMetal')->getLast();
    }
    
}

