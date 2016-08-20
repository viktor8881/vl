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
class View_Helper_Account_BtnChange extends Zend_View_Helper_Abstract
{
    
    public function account_BtnChange($value) {
        $xhtml = '<div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                '._('Изменить').' <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>'.$this->view->account_LinkAdd('Пополнить').'</li>';
        if(Core_Math::compareMoney($value, 0) > 0) {
            $xhtml .= '<li>'.$this->view->account_LinkSub('Списать').'</li>';
        }
        $xhtml .= '</ul></div>';
        return $xhtml;
    }
        
}
