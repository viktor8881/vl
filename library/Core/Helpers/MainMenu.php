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
        '/tasks/index/list' => 'Задания',
        '/analysis/'        => 'Анализ',
//        array('/course/currency/index/'=>'Курс валюты',
//            '/course/metal/index/'=>'Курс металла',)
        );
    
    public function mainMenu()
    {
        $currentUrl = $this->view->url();
        $xhtml = '<ul class="nav navbar-nav">';
        foreach ($this->_links as $url=>$name) {
                $class = $currentUrl==$url?' class="active"':'';
            $xhtml .= '<li'.$class. '><a href="'.$url.'">'._($name).'</a></li> ';
        }
        $xhtml .= '<li class="dropdown">'
                . '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'._('Курсы').' <span class="caret"></span></a>'
                . '<ul class="dropdown-menu">'
                    . '<li><a href="/course/currency/index/">'._('Курсы валют').'</a></li>'
                    . '<li><a href="/course/metal/index/">'._('Курсы металлов').'</a></li>'
                . '</ul>'
                . '</li> ';
//        $xhtml .= '<li class="dropdown">'
//                . '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'._('Анализ').' <span class="caret"></span></a>'
//                . '<ul class="dropdown-menu">'
//                    . '<li><a href="/analysis/currency/index/">'._('Анализ валют').'</a></li>'
//                    . '<li><a href="/analysis/metal/index/">'._('Анализ металлов').'</a></li>'
//                . '</ul>'
//                . '</li> ';
        $xhtml .= '</ul>';
        return $xhtml;
    }
    
}
