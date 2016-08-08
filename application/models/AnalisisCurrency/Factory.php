<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalisisCurrency_Factory
 *
 * @author Viktor
 */
class AnalisisCurrency_Factory implements Core_Domen_IFactory {
    
    
    public function create(array $values = null) {
        if (!array_key_exists('type', $values)) {
            throw new RuntimeException('Not found "type" of analisis.');
        }
        switch ($values['type']) {
            case AnalisisCurrency_Model_Abstract::TYPE_PERCENT:
                return new AnalisisCurrency_Model_Percent($values);
                break;
            case AnalisisCurrency_Model_Abstract::TYPE_OVER_TIME:
                return new AnalisisCurrency_Model_OverTime($values);
                break;
            default:
                throw new RuntimeException('Unknown "type" of task.');
                break;
        }
    }

}
