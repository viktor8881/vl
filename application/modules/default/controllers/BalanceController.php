<?php

class BalanceController extends Core_Controller_Action
{

    public function indexAction() {
        $this->view->accountValue = $this->getManager('account')->getValue();
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
    }
    
}

