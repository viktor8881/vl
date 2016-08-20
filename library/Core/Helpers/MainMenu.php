<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of linkUser
 *
 * @author Viktor Ivanov
 */

require_once 'Zend/View/Helper/Placeholder/Container/Standalone.php';

class Core_Helper_MainMenu extends Zend_View_Helper_Placeholder_Container_Standalone {
    
    private $_links = array(
        '/balance'          => 'Главная', 
        '/investments'      => 'Инвестиции',
        '/tasks/index/list' => 'Задания');
    
    public function mainMenu()
    {
        $currentUrl = $this->view->url();
        $xhtml = '<ul class="nav navbar-nav">';
        foreach ($this->_links as $url=>$name) {
            $class = $currentUrl==$url?' class="active"':'';
            $xhtml .= '<li'.$class.'><a href="'.$url.'">'._($name).'</a></li> ';
        }
        $xhtml .= '</ul>';
        return $xhtml;
    }
    
}
