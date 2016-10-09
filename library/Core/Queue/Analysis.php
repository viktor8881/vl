<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Queue_Analysis
 *
 * @author Viktor
 */
class Core_Queue_Analysis extends Core_Queue {

    
    const TASK_ANALYSIS = 'task_analysis';
    const TASK_SEND_MESSAGE = 'task_send_message';
    
    
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
    
    public function hasTaskEmail() {
        return $this->getAdapter()->hasTasksMessage(self::TASK_SEND_MESSAGE);
    }
    
    public function sendTaskEmail($checkExist=false) {
        if ($checkExist && $this->hasTaskEmail()) {
            return false;
        }
        parent::send(self::TASK_SEND_MESSAGE);
        return true;
    }
    
}
