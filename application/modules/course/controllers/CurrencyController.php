<?php

class Course_CurrencyController extends Core_Controller_Action
{
    
    private static $DATA_DEF;
    
    public function init() {
        parent::init();
        $dateNow = new Core_Date();
        self::$DATA_DEF = $dateNow->sub(new DateInterval('P1Y'))->formatDMY();
    }
    
    public function indexAction() {
        $items = $this->getManager('currency')->fetchAll();
        
        $id = (int)$this->getParam('id', 2);
        if (!($current = $items->getValue($id))) {
            throw new Core_Domen_NotFoundException(_('Валюта не найдена.'));
        }   
        $dateStart = $this->getParam('start', self::$DATA_DEF);
        $dateEnd   = $this->getParam('end', date('d.m.Y'));
        if (!Zend_Validate::is($dateStart, 'Date', array(), 'Core_Validate') or !Zend_Validate::is($dateEnd, 'Date', array(), 'Core_Validate')) {
            throw new Core_Domen_NotFoundException(_('Не верный формат периода.'));
        }
        $this->view->period = ['start'=>$dateStart, 'end'=>$dateEnd];
        $this->view->items = $items;
        $this->view->currentItem = $current;
        $this->view->courses = $this->getManager('CourseCurrency')->fetchAllByPeriodByCode(new Core_Date($dateStart), new Core_Date($dateEnd), $current->getCode());
    }
    
}