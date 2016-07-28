<?php

class Tasks_IndexController extends Core_Controller_Action
{
    
    public function addPercentAction()
    {
        $this->view->headTitle('Задания по проценту');
        $form = new Form_Percent();
        $form->setMetal($this->getManager('metal')->fetchAllToArray())
                ->setCurrency($this->getManager('currency')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $task = $this->getManager('taskPercent')->createModel($form->getValuesForModel());
                try {
                    $this->getManager('taskPercent')->insertPay($task);
                    $this->_redirect('/tasks/index/list');
                } catch (Exception $exc) {
                    throw new RuntimeException('Ошибка добавления инвестиции.');
                }
            }
        }
        $this->view->form = $form;
    }
    
}