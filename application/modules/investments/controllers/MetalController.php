<?php

class Investments_MetalController extends Core_Controller_Action
{

    public function listAction() {
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new InvestmentMetal_Order_Id('DESC'));
        $page = $this->getParam('page', 1);
        $paginator = $this->_helper->paginator($this->getManager('investmentMetal')->count(), $page);
        $this->view->paginator = $paginator;
        $this->view->investments = $this->getManager('investmentMetal')->fetchAll($paginator, $orders);
    }
    
    public function addAction() {
        $this->view->pageHeader('Купить метал');
        $form = new Form_Metal();
        $form->setMetal($this->getManager('metal')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $invest = $this->getManager('investmentMetal')->createModel($form->getValuesForModel());
                $invest->setType(InvestmentMetal_Model::TYPE_BUY);
                try {
                    $this->getManager('investmentMetal')->insertPay($invest);
                    $this->_redirect('/investments/metal/list');
                } catch (Exception $exc) {
                    throw new RuntimeException(_('Ошибка добавления инвестиции.'));
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function subAction() {
        $this->view->pageHeader('Продать метал');
        $form = new Form_Metal();
        $form->setMetal($this->getManager('metal')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $invest = $this->getManager('investmentMetal')->createModel($form->getValuesForModel());
                $invest->setType(InvestmentMetal_Model::TYPE_SELL);
                try {
                    $this->getManager('investmentMetal')->insertSell($invest);
                    $this->_redirect('/investments/metal/list');
                } catch (Exception $exc) {
                    throw new RuntimeException(_('Ошибка добавления инвестиции.'));
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function editAction() {
        $this->view->pageHeader('Редактировать');
        $invest = $this->getManager('investmentMetal')->get((int)$this->getParam('id'));
        if (!$invest) {
            throw new RuntimeException(_('Инвестиция не найдена.'));
        }
        $form = new Form_Metal();
        $form->setMetal($this->getManager('metal')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $oldCountInvest = $invest->getCount();
                $invest->setOptions($form->getValuesForModel());
                try {
                    $this->getManager('investmentMetal')->update($invest);
                    if ($invest->getCount() != $oldCountInvest) {
                        $balance = $invest->getCount()-$oldCountInvest;
                        if ($invest->isSell()) {
                            $balance *= -1;
                        }
                        $this->getManager('balanceMetal')->updateBalanceByCode($invest->getMetalCode(), $balance);
                    }
                    $this->_redirect('/investments/');
                } catch (Exception $exc) {
                    throw new RuntimeException(_('Ошибка редактирования инвестиции.'));
                }
            }
        }else{
            $form->setValuesToModel($invest);
        }        
        $this->view->form = $form;
    }
    
    public function deleteAction() {
        $invest = $this->getManager('investmentMetal')->get((int)$this->getParam('id'));
        if (!$invest) {
            throw new RuntimeException(_('Инвестиция не найдена.'));
        }
        try {
            $this->getManager('investmentMetal')->delete($invest);            
            $this->getManager('balanceMetal')->updateBalanceByInvest($invest);
            $this->_redirect('/investments/');
        } catch (Exception $exc) {
            throw new RuntimeException(_('Ошибка удаления инвестиции.'));
        }
    }
    

}

