<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Queue_TechnicalAnalysis
 *
 * @author Viktor
 */
class Core_Queue_TechnicalAnalysis extends Core_Queue {

    const TASK_ANALYSIS_CURRENCY = 'task_analysis_currency';
    const TASK_ANALYSIS_METAL = 'task_analysis_metal';
    const TASK_SEND_MESSAGE = 'task_send_message';
    
 
    public function sendTaskAnalysisMetal() {
        if ($this->getAdapter()->hasTasksMessage(self::TASK_ANALYSIS_METAL)) {
            return false;
        }
        parent::send(self::TASK_ANALYSIS_METAL);
        return true;
    }
 
    public function sendTaskAnalysisCurrency() {
        if ($this->getAdapter()->hasTasksMessage(self::TASK_ANALYSIS_CURRENCY)) {
            return false;
        }
        parent::send(self::TASK_ANALYSIS_CURRENCY);
        return true;
    }
    
    public function sendTaskEmail() {
        if ($this->getAdapter()->hasTasksMessage(self::TASK_SEND_MESSAGE)) {
            return false;
        }
        parent::send(self::TASK_SEND_MESSAGE);
        return true;
    }
    
}
