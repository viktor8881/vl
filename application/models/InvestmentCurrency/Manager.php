<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvestmentCurrency_Manager
 *
 * @author Viktor
 */
class InvestmentCurrency_Manager extends Core_Domen_Manager_Abstract {
    
    public function insertBuy(InvestmentCurrency_Model $model) {
        $res = parent::insert($model);
        if ($res) {
            $this->getManager('balanceCurrency')->addToInvestment($model);
        }
        return $res;
    }
    
    public function insertSell(InvestmentCurrency_Model $model) {
        $res = parent::insert($model);
        if ($res) {
            $this->getManager('balanceCurrency')->subToInvestment($model);
        }
        return $res;
    }
    
    public function delete(\Core_Domen_IModel $model) {
        if (!($model instanceof InvestmentCurrency_Model)) {
            throw new Exception("Error delete. Wrong type.");
        }
        if ($model->isBuy()) {
            $this->getManager('balanceCurrency')->subToInvestment($model);
        }else{
            $this->getManager('balanceCurrency')->addToInvestment($model);
        }
        return parent::delete($model);
    }

    public function getSumByBalance(BalanceCurrency_Model $balance) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new InvestmentCurrency_Filter_Code($balance->getCurrencyCode()))
                ->addFilter(new InvestmentCurrency_Filter_Type(InvestmentCurrency_Model::TYPE_BUY));
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new InvestmentCurrency_Order_Id('DESC'));
        $investments = $this->fetchAllByFilter($filters, null, $orders);        
        return $investments->getSumByBalance($balance->getBalance());
    }
    
}
