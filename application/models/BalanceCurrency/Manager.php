<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BalanceCurrency_Manager
 *
 * @author Viktor
 */
class BalanceCurrency_Manager extends Core_Domen_Manager_Abstract {
    
    public function getByCode($code) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new BalanceCurrency_Filter_Code($code));
        return $this->getByFilter($filters);
    }

    public function addToInvestment(InvestmentCurrency_Model $model) {
        $balance = $this->getByCode($model->getCurrencyCode());
        if (!$balance) {
            $balance = $this->createModel();
            $balance->setCurrencyCode($model->getCurrencyCode())
                    ->setBalance(0);
            parent::insert($balance);
        }
        $balance->addBalance($model->getCount());
        return parent::update($balance);
    }

    public function subToInvestment(InvestmentCurrency_Model $model) {
        $balance = $this->getByCode($model->getCurrencyCode());
        if (!$balance) {
            $balance = $this->createModel();
            $balance->setCurrencyCode($model->getCurrencyCode())
                    ->setBalance(0);
            parent::insert($balance);
        }
        $balance->subBalance($model->getCount());
        return parent::update($balance);
    }
    
    public function updateBalanceByCode($code, $balanceValue) {
        $balance = $this->getByCode($code);
        if (!$balance) {
            throw new RuntimeException('Баланс не найден.');
        }
        $balance->addBalance($balanceValue);
        return parent::update($balance);
    }
    
    public function updateBalanceByInvest(InvestmentCurrency_Model $invest) {
        $balance = $invest->getCount();
        if ($invest->isBuy()) {
            $balance *= -1;
        }
        return $this->updateBalanceByCode($invest->getCurrencyCode(), $balance);
    }
            
    
}
