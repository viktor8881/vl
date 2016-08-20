<?php

class Tasks_OvertimeController extends Core_Controller_Action
{
    

    public function addAction()
    {
        $this->view->pageHeader('Создать "Задания по росту/падению в течении периода"');
        $form = new Form_OverTime();
        $form->setMetal($this->getManager('metal')->fetchAllToArray())
                ->setCurrency($this->getManager('currency')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $task = $this->getManager('task')->createModel($form->getValuesForModel());
                try {
                    $this->getManager('task')->insert($task);
                    $this->_redirect('/tasks/index/list');
                } catch (Exception $exc) {
                    throw new RuntimeException(_('Ошибка добавления инвестиции.'));
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function editAction() {
        $this->view->pageHeader('Редактировать задание');
        $task = $this->getManager('task')->get((int)$this->getParam('id'));
        if (!$task or !$task->isOverTime()) {
            throw new RuntimeException(_('Задание не найдено.'));
        }
        $form = new Form_OverTime();
        $form->setMetal($this->getManager('metal')->fetchAllToArray())
                ->setCurrency($this->getManager('currency')->fetchAllToArray());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $task->setOptions($form->getValuesForModel());
                try {
                    $this->getManager('task')->update($task);
                    $this->_redirect('/tasks/index/list');
                } catch (Exception $exc) {
                    throw new RuntimeException(_('Ошибка редактирования задания.'));
                }
            }
        }else{
            $form->setValuesToModel($task);
        }        
        $this->view->form = $form;
    }
    
    public function deleteAction() {
        $task = $this->getManager('task')->get((int)$this->getParam('id'));
        if (!$task or !$task->isOverTime()) {
            throw new RuntimeException(_('Задание не найдено.'));
        }
        try {
            $this->getManager('task')->delete($task);
            $this->_redirect('/tasks/index/list');
        } catch (Exception $exc) {
            throw new RuntimeException(_('Ошибка удаления задания.'));
        }
    }
    
}