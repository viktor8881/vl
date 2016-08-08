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
class View_Helper_Task_LinkDelete extends Zend_View_Helper_Abstract
{
    
    public function task_LinkDelete(Task_Model_Abstract $model, $name='') 
    {
        $xhtml = '';
        if ($model->isPercent()) {
            $xhtml .= '<a href="/tasks/percent/delete/id/'.$model->getId().'">'.$this->view->iconDelete($name).' '.$name.'</a>';
        }elseif ($model->isOvertime()) {
            $xhtml .= '<a href="/tasks/overtime/delete/id/'.$model->getId().'">'.$this->view->iconDelete($name).' '.$name.'</a>';
        }
        return $xhtml;
    }
        
}
