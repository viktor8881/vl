<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Helper_IconAdd
 *
 * @author Viktor Ivanov
 */
class Core_Helper_IconAdd extends Zend_View_Helper_Abstract {
    
    
    public function iconAdd($title=null)
    {
        $title = ($title)?'title="'._($this->view->escape($title)).'"':null;
        return '<span class="glyphicon glyphicon-plus" '.$title.'></span>';
    }
    
}

?>
