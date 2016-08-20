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
class View_Helper_Account_LinkAdd extends Zend_View_Helper_Abstract
{
    
    public function account_LinkAdd($name) {
        return  '<a href="/default/account/add">'.$this->view->iconAdd($name).' '._($name).'</a>';
    }
        
}
