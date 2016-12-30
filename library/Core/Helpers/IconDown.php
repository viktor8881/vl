<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Helper_IconDown
 *
 * @author Viktor Ivanov
 */
class Core_Helper_IconDown extends Zend_View_Helper_Abstract {
    
    
    public function iconDown($title=null) {
        $title = ($title)?'title="'._($this->view->escape($title)).'"':null;
        return '<span title="'._($title).'">▼</span>';
    }
    
}
