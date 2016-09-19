<?php

class Course_MetalController extends Core_Controller_Action
{
    
    const DATE_START = '01.01.2010';
    
    public function indexAction() {
        $items = $this->getManager('metal')->fetchAll();
        
        $id = (int)$this->getParam('id', 2);
        if (!($current = $items->getValue($id))) {
            throw new RuntimeException(_('Метал не найдена.'));
        }
        $dateStart = $this->getParam('start', self::DATE_START);
        $dateEnd   = $this->getParam('end', date('d.m.Y'));
        if (!Zend_Validate::is($dateStart, 'Date', array(), 'Core_Validate') or !Zend_Validate::is($dateEnd, 'Date', array(), 'Core_Validate')) {
            throw new RuntimeException(_('Не верный формат периода.'));
        }
        $this->view->period = ['start'=>$dateStart, 'end'=>$dateEnd];
        $this->view->items = $items;
        $this->view->currentItem = $current;
        $this->view->courses = $this->getManager('CourseMetal')->fetchAllByPeriodByCode(new Core_Date($dateStart), new Core_Date($dateEnd), $current->getCode());
    }
    
}