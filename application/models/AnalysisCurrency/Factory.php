<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisCurrency_Factory
 *
 * @author Viktor
 */
class AnalysisCurrency_Factory implements Core_Domen_IFactory {
    
    
    public function create(array $values = null) {
        if (!array_key_exists('type', $values)) {
            throw new Exception('Not found "type" of analysis.');
        }
        switch ($values['type']) {
            case AnalysisCurrency_Model_Abstract::TYPE_FIGURE:
                return new AnalysisCurrency_Model_Figure($values);
                break;
            case AnalysisCurrency_Model_Abstract::TYPE_PERCENT:
                return new AnalysisCurrency_Model_Percent($values);
                break;
            case AnalysisCurrency_Model_Abstract::TYPE_OVER_TIME:
                return new AnalysisCurrency_Model_OverTime($values);
                break;
            default:
                throw new Exception('Unknown "type" of task.');
                break;
        }
    }

}
