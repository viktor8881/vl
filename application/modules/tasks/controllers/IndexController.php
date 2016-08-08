<?php

class Tasks_IndexController extends Core_Controller_Action
{
    
    public function listAction()
    {
        $this->view->tasks = $this->getManager('task')->fetchAllCustom();
    }

   
    
}