<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvestmentMetal_Manager
 *
 * @author Viktor
 */
class InvestmentMetal_Manager extends Core_Domen_Manager_Abstract {
    
    public function insertPay(InvestmentMetal_Model $model) {
        $res = parent::insert($model);
        if ($res) {
            $this->getManager('balanceMetal')->addToInvestment($model);
        }
        return $res;
    }
    
    public function insertSell(InvestmentMetal_Model $model) {
        $res = parent::insert($model);
        if ($res) {
            $this->getManager('balanceMetal')->subToInvestment($model);
        }
        return $res;
    }
    
    public function getSumByBalance(BalanceMetal_Model $balance) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new InvestmentMetal_Filter_Code($balance->getMetalCode()))
                ->addFilter(new InvestmentMetal_Filter_Type(InvestmentMetal_Model::TYPE_BUY));
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new InvestmentMetal_Order_Id('DESC'));
        $investments = $this->fetchAllByFilter($filters, null, $orders);        
        return $investments->getSumByBalance($balance->getBalance());
    }

    
}
