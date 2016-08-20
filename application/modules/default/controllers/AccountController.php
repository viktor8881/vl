<?php

class AccountController extends Core_Controller_Action
{
    
    public function addAction() {
        $this->view->pageHeader('Пополнить основной счет');
        $valueAcc = $this->getManager('account')->getValue();
        $form = new Form_Account();
        $form->setLabelBalance('Пополнить на');
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                if ($valueAcc == $form->getValueCurrentBalance()) {
                    try {
                        $this->getManager('account')->addPay($form->getValueBalance());
                        $this->_redirect('/default/balance');
                    } catch (Exception $exc) {
                        throw new RuntimeException(_('Ошибка добавления в основной счет.'));
                    }
                }else{
                    $this->view->warningMessage = 'С момента последнего обращения, значение основного счета было изменено. Для изменения счета повторите попытку.';
                }
            }   
        }
        $form->setCurrentBalance($valueAcc);
        $this->view->form = $form;
    }
    
    public function subAction() {
        $this->view->pageHeader('Списать с основного счет');
        $valueAcc = $this->getManager('account')->getValue();
        $form = new Form_Account();
        $form->setLabelBalance('Вычесть');
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                if ($valueAcc == $form->getValueCurrentBalance()) {
                    try {
                        $this->getManager('account')->subPay($form->getValueBalance());
                        $this->_redirect('/default/balance');
                    } catch (Exception $exc) {
                        throw new RuntimeException(_('Ошибка списания в основной счет.'));
                    }
                }else{
                    $this->view->warningMessage = 'С момента последнего обращения, значение основного счета было изменено. Для изменения счета повторите попытку.';
                }
            }   
        }
        $form->setCurrentBalance($valueAcc);
        $this->view->form = $form;
    }
    
}

