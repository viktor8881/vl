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
class View_Helper_Account_LinkSub extends Zend_View_Helper_Abstract
{
    
    public function account_LinkSub($name) {
        return  '<a href="/default/account/sub">'.$this->view->iconSub($name).' '._($name).'</a>';
    }
        
}
