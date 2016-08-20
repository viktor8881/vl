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
class View_Helper_Task_LinkEdit extends Zend_View_Helper_Abstract
{
    
    public function task_LinkEdit(Task_Model_Abstract $model, $name='') 
    {
        $xhtml = '';
        if ($model->isPercent()) {
            $xhtml .= '<a href="/tasks/percent/edit/id/'.$model->getId().'">'.$this->view->iconEdit($name).' '._($name).'</a>';
        }elseif ($model->isOvertime()) {
            $xhtml .= '<a href="/tasks/overtime/edit/id/'.$model->getId().'">'.$this->view->iconEdit($name).' '._($name).'</a>';
        }
        return $xhtml;
    }
        
}
