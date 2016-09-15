<?php

class Course_MetalController extends Core_Controller_Action
{
    
    public function indexAction() {
        $items = $this->getManager('metal')->fetchAll();
        
        $id = (int)$this->getParam('id', 2);
        if (!($current = $items->getValue($id))) {
            throw new RuntimeException(_('Метал не найдена.'));
        }   
        $this->view->items = $items;
        $this->view->currentItem = $current;
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new CourseMetal_Filter_Code($current->getCode()));
        $orders = new Core_Domen_Order_Collection();
        $orders->addOrder(new CourseMetal_Order_Id('DESC'));
        $paginator = $this->_helper->paginator($this->getManager('CourseMetal')->count(), $this->getParam('page', 1));
        $this->view->paginator = $paginator;
        $this->view->courses = $this->getManager('CourseMetal')->fetchAllByFilter($filters, $paginator, $orders);
    }
    
}