<?php

class Tasks_IndexController extends Core_Controller_Action
{
    
    public function listAction()
    {
        $this->view->pageHeader('Задания');
        $this->view->tasks = $this->getManager('task')->fetchAllCustomOrderByOwerTime();
    }

   
    
}