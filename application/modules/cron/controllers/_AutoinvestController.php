<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cron_AutoinvestController
 *
 * @author Viktor
 */
class Cron_AutoinvestController extends Core_Controller_Action {
    
    
    public function indexAction() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $fileName = $bootstrap->getOptions()['path']['temp'].'date.tmp';

        $date = new Core_Date(file_get_contents($fileName));
        if ($this->getManager('courseCurrency')->hasByDate($date) && $this->getManager('courseMetal')->hasByDate($date)) {
            // run analysis
            $serviceAnalyses = $this->getService('analysis');
            $tasks = $this->getManager('task')->fetchAll();
            foreach ($tasks as $task) {
                $serviceAnalyses->runByTask($task, $date);
            }

            $serviceAutoInvest = $this->getService('autoinvestment');
            $serviceAutoInvest->run($date);
        }
        $date->add(new DateInterval('P1D'));
        file_put_contents($fileName, $date->formatDMY());

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    
    public function mailAction() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $fileName = $bootstrap->getOptions()['path']['temp'].'date.tmp';
        $date = new Core_Date(file_get_contents($fileName));
        while(true) {
            if (!$this->getManager('courseCurrency')->hasByDate($date) or !$this->getManager('courseMetal')->hasByDate($date)) {
                $date->sub(new DateInterval('P1D'));
                continue;
            }else{
                break;
            }
        }

        $balanceCurrency    = $this->getManager('BalanceCurrency')->fetchAll();
        $balanceMetal       = $this->getManager('BalanceMetal')->fetchAll();
        $courseCurrency     = $this->getManager('courseCurrency')->fetchAllByDate($date);
        $courseMetal        = $this->getManager('courseMetal')->fetchAllByDate($date);
        $currentValue       = $this->getManager('Account')->getValue();
        Core_Mail::sendAutoInvest($balanceCurrency, $balanceMetal, $courseCurrency, $courseMetal, $date, $currentValue);
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
}
