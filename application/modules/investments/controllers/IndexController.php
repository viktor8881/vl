<?php

class Investments_IndexController extends Core_Controller_Action
{

    public function indexAction() {
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new InvestmentCurrency_Order_Id('DESC'));
        $paginator = $this->_helper->paginator(5,1,5);
        $this->view->investCurrencies = $this->getManager('investmentCurrency')->fetchAll($paginator, $orders);
        
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new InvestmentMetal_Order_Id('DESC'));
        $this->view->investMetals = $this->getManager('investmentMetal')->fetchAll($paginator, $orders);
    }
    
}

