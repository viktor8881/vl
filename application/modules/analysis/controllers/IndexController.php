<?php

class Analysis_IndexController extends Core_Controller_Action
{

    public function indexAction() {
        $this->view->metals = $this->getManager('AnalysisMetal')->listByMetalsOnLastDates();
        $this->view->currencies = $this->getManager('AnalysisCurrency')->listByCurrencyOnLastDates();
    }
    
}