<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task_Factory
 *
 * @author Viktor
 */
class Task_Factory implements Core_Domen_IFactory {
    
    
    public function create(array $values = null) {
        if (!array_key_exists('type', $values)) {
            throw new RuntimeException('Not found "type" of task.');
        }
        switch ($values['type']) {
            case Task_Model_Abstract::TYPE_PERCENT:
                return new Task_Model_Percent($values);
                break;
            case Task_Model_Abstract::TYPE_OVER_TIME:
                return new Task_Model_OverTime($values);
                break;
            default:
                throw new RuntimeException('Unknown "type" of task.');
                break;
        }
    }

}
