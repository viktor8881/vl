<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Queue
 *
 * @author Viktor
 */
class Core_Queue extends Zend_Queue {
    
    const TASK_ANALYSIS = 'task_analysis';
    const TASK_SEND_MESSAGE = 'task_send_message';


    public function __construct($name='default_queue', $timeout=null) {
        if (is_null($timeout) or !is_integer($timeout)) {
            $timeout = 7 * 24 * 60;
        }
        $options = array(
                'name'=>$name,
                'timeout'=>$timeout,
                'dbAdapter' => Zend_Db_Table::getDefaultAdapter(),
                'adapterNamespace'=>'Core_Queue_Adapter',
                'options' => array(
                    Zend_Db_Select::FOR_UPDATE => true
                )
            ); 
        parent::__construct('Db', $options);
    }
    
    
    public function hasRunAnalysis() {
        return $this->getAdapter()->hasTasksMessage(self::TASK_ANALYSIS);
    }
    
    public function sendRunAnalysis($checkExist=false) {
        if ($checkExist && $this->hasRunAnalysis()) {
            return false;
        }
        parent::send(self::TASK_ANALYSIS);
        return true;
    }
    
}
