<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 *
 * @author Viktor Ivanov
 */
class View_Helper_Task_TypeName extends Zend_View_Helper_Abstract
{
    
    public function task_TypeName($type) 
    {
        if ($type == Task_Model_Abstract::TYPE_PERCENT) {
            return _('изменение на процент за период');
        }elseif ($type == Task_Model_Abstract::TYPE_OVER_TIME) {
            return _('рост/падение в течении времени');
        }
        return '';
    }
        
}
