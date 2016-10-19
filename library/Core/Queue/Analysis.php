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

    // заполнение данными для поиска фигур. Поиск фигур
    const TASK_FILL_DATA = 'fill_data';
    // выполнение анализа
    const TASK_ANALYSIS = 'task_analysis';
    // отправка сообщений
    const TASK_SEND_MESSAGE = 'task_send_message';
    
        
    public function hasFillData() {
        return $this->getAdapter()->hasTasksMessage(self::TASK_FILL_DATA);
    }
    
    public function sendFillData($checkExist=false) {
        if ($checkExist && $this->hasFillData()) {
            return false;
        }
        parent::send(self::TASK_FILL_DATA);
        return true;
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
