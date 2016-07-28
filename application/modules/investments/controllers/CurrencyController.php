<?php

class Investments_CurrencyController extends Core_Controller_Action
{

    public function listAction() {
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new InvestmentMetal_Order_Id('DESC'));
        $page = $this->getParam('page', 1);
        $paginator = $this->_helper->paginator($this->getManager('investmentCurrency')->count(), $page);
        $this->view->paginator = $paginator;
        $this->view->investments = $this->getManager('investmentCurrency')->fetchAll($paginator, $orders);
    }

    public function indexAction() {
        die('stop');
    }
    
    public function addAction() {
        $this->view->headTitle('Купить валюту');
        $form = new Form_Currency();
        $form->setCurrency($this->getManager('currency')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $invest = $this->getManager('investmentCurrency')->createModel($form->getValuesForModel());
                $invest->setType(InvestmentCurrency_Model::TYPE_BUY);
                try {
                    $this->getManager('investmentCurrency')->insertPay($invest);
                    $this->_redirect('/investments/currency/list');
                } catch (Exception $exc) {
                    pr($exc); exit;
                    throw new RuntimeException('Ошибка добавления инвестиции.');
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function subAction() {
        $this->view->headTitle('Продать валюту');
        $form = new Form_Currency();
        $form->setCurrency($this->getManager('currency')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $invest = $this->getManager('investmentCurrency')->createModel($form->getValuesForModel());
                $invest->setType(InvestmentCurrency_Model::TYPE_SELL);
                try {
                    $this->getManager('investmentCurrency')->insertSell($invest);
                    $this->_redirect('/investments/currency/list');
                } catch (Exception $exc) {
                    pr($exc); exit;
                    throw new RuntimeException('Ошибка добавления инвестиции.');
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function editAction() {
        $this->view->headTitle('Редактировать');
        $invest = $this->getManager('investmentCurrency')->get((int)$this->getParam('id'));
        if (!$invest) {
            throw new RuntimeException('Инвестиция не найдена.');
        }
        $form = new Form_Currency();
        $form->setCurrency($this->getManager('currency')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $oldCountInvest = $invest->getCount();
                $invest->setOptions($form->getValuesForModel());
                try {
                    $this->getManager('investmentCurrency')->update($invest);
                    if ($invest->getCount() != $oldCountInvest) {
                        $balance = $invest->getCount()-$oldCountInvest;
                        if ($invest->isSell()) {
                            $balance *= -1;
                        }
                        $this->getManager('balanceCurrency')->updateBalanceByCode($invest->getCurrencyCode(), $balance);
                    }
                    $this->_redirect('/investments/');
                } catch (Exception $exc) {
                    throw new RuntimeException('Ошибка редактирования инвестиции.');
                }
            }
        }else{
            $form->setValuesToModel($invest);
        }        
        $this->view->form = $form;
    }
    
    public function deleteAction() {
        $invest = $this->getManager('investmentCurrency')->get((int)$this->getParam('id'));
        if (!$invest) {
            throw new RuntimeException('Инвестиция не найдена.');
        }
        try {
            $this->getManager('investmentCurrency')->delete($invest);
            $this->getManager('balanceCurrency')->updateBalanceByInvest($invest);
            $this->_redirect('/investments/');
        } catch (Exception $exc) {
            throw new RuntimeException('Ошибка удаления инвестиции.');
        }
    }

}

