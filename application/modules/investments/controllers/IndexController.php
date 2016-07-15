<?php

class Investments_IndexController extends Core_Controller_Action
{


    public function indexAction() {
        die('stop');
    }
    
    public function addAction() {
        $this->view->headTitle('Добавить');
        $form = new Form_Add();
        $form->setCurrency($this->getManager('currency')->listCurrencies());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {

            }
        }
        $this->view->form = $form;
    }

}

